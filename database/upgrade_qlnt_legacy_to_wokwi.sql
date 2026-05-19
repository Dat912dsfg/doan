USE `QLNT`;

CREATE TABLE IF NOT EXISTS `kich_ban_hanh_dong` (
  `idHanhDong` int(11) NOT NULL AUTO_INCREMENT,
  `idKichBan` int(11) NOT NULL,
  `thuTuChay` int(11) NOT NULL DEFAULT 1,
  `loaiHanhDong` varchar(50) NOT NULL DEFAULT 'device_control',
  `idThietBi` int(11) DEFAULT NULL,
  `lenh` varchar(256) NOT NULL,
  `giaTriLenh` varchar(256) DEFAULT NULL,
  `moTa` varchar(256) DEFAULT NULL,
  `trangThai` smallint(6) DEFAULT 1,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idHanhDong`),
  KEY `idx_kbhd_kichban` (`idKichBan`),
  KEY `idx_kbhd_thietbi` (`idThietBi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `phong_kich_ban`
  ADD COLUMN IF NOT EXISTS `ngayCapNhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `ngayKichHoat`;

ALTER TABLE `kich_ban_tu_dong`
  ADD COLUMN IF NOT EXISTS `ngayApDung` varchar(50) DEFAULT NULL AFTER `gioKetThuc`,
  ADD COLUMN IF NOT EXISTS `thoiGianChoChay` int(11) DEFAULT 0 AFTER `ngayApDung`,
  ADD COLUMN IF NOT EXISTS `soLanDaChay` int(11) DEFAULT 0 AFTER `thoiGianChoChay`,
  ADD COLUMN IF NOT EXISTS `thoiGianChayLanCuoi` timestamp NULL DEFAULT NULL AFTER `soLanDaChay`,
  ADD COLUMN IF NOT EXISTS `phienBan` int(11) DEFAULT 1 AFTER `thoiGianChayLanCuoi`;

ALTER TABLE `cam_bien`
  ADD COLUMN IF NOT EXISTS `giaTriMin` float DEFAULT NULL AFTER `donVi`,
  ADD COLUMN IF NOT EXISTS `giaTriMax` float DEFAULT NULL AFTER `giaTriMin`,
  ADD COLUMN IF NOT EXISTS `ngayCapNhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `ngayTao`;

ALTER TABLE `cau_hinh`
  ADD COLUMN IF NOT EXISTS `moTa` varchar(256) DEFAULT NULL AFTER `khoiBaoChay`;

SET @has_duplicate_cauhinh := (
  SELECT COUNT(*) FROM (
    SELECT idPhong
    FROM cau_hinh
    WHERE idPhong IS NOT NULL
    GROUP BY idPhong
    HAVING COUNT(*) > 1
  ) AS duplicate_rooms
);

SET @add_unique_cauhinh := IF(
  @has_duplicate_cauhinh = 0,
  'ALTER TABLE `cau_hinh` ADD UNIQUE KEY `uk_cau_hinh_idPhong` (`idPhong`)',
  'SELECT ''Skip unique index on cau_hinh.idPhong because duplicate rows exist'' AS message'
);
PREPARE stmt_cauhinh_unique FROM @add_unique_cauhinh;
EXECUTE stmt_cauhinh_unique;
DEALLOCATE PREPARE stmt_cauhinh_unique;

ALTER TABLE `canh_bao`
  ADD COLUMN IF NOT EXISTS `idPhong` int(11) DEFAULT NULL AFTER `idCanhBao`,
  ADD COLUMN IF NOT EXISTS `tenCanhBao` varchar(100) DEFAULT NULL AFTER `idCamBien`,
  ADD COLUMN IF NOT EXISTS `loaiCanhBao` varchar(50) DEFAULT 'threshold' AFTER `noiDung`,
  ADD COLUMN IF NOT EXISTS `dieuKien` varchar(256) DEFAULT NULL AFTER `mucDo`,
  ADD COLUMN IF NOT EXISTS `trangThaiHoatDong` smallint(6) DEFAULT 0 AFTER `trangThaiXuLy`,
  ADD COLUMN IF NOT EXISTS `thoiGianBatDau` timestamp NULL DEFAULT NULL AFTER `trangThaiHoatDong`,
  ADD COLUMN IF NOT EXISTS `thoiGianKhoaPhuc` timestamp NULL DEFAULT NULL AFTER `thoiGianBatDau`,
  ADD COLUMN IF NOT EXISTS `nguoiXuLy` int(11) DEFAULT NULL AFTER `thoiGianKhoaPhuc`,
  ADD COLUMN IF NOT EXISTS `ghiChu` varchar(256) DEFAULT NULL AFTER `nguoiXuLy`,
  ADD COLUMN IF NOT EXISTS `ngayCapNhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `ngayTao`;

UPDATE `kich_ban_tu_dong`
SET `loaiKichBan` = CASE
    WHEN LOWER(COALESCE(`loaiKichBan`, '')) = 'sensor' THEN 'event'
    WHEN LOWER(COALESCE(`loaiKichBan`, '')) = 'timer' THEN 'schedule'
    ELSE COALESCE(`loaiKichBan`, 'event')
END;

UPDATE `kich_ban_tu_dong`
SET `ngayApDung` = TRIM(BOTH ',' FROM
    REPLACE(
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(
                    REPLACE(COALESCE(`ngayLap`, ''), '[', ''),
                  ']', ''),
                '\"', ''),
              '"', ''),
            ' ', ''),
          'mon,', 'mon,'),
        ',tue', ',tue'),
      ',wed', ',wed'),
    ',thu', ',thu'))
WHERE (`ngayApDung` IS NULL OR `ngayApDung` = '')
  AND COALESCE(`ngayLap`, '') <> '';

UPDATE `kich_ban_tu_dong`
SET `ngayApDung` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(COALESCE(`ngayApDung`, ''), ']', ''), '[', ''), '"', ''), ' ', ''), ',,', ','), ',,', ','), ',,', ',')
WHERE COALESCE(`ngayApDung`, '') <> '';

UPDATE `kich_ban_tu_dong`
SET `dieuKien` = CASE
    WHEN `loaiKichBan` = 'schedule' THEN CONCAT(
      'timer|start=', COALESCE(TIME_FORMAT(`gioBatDau`, '%H:%i'), '00:00'),
      '|end=', COALESCE(TIME_FORMAT(`gioKetThuc`, '%H:%i'), '23:59'),
      '|days=', COALESCE(NULLIF(`ngayApDung`, ''), 'mon,tue,wed,thu,fri,sat,sun')
    )
    ELSE CONCAT(
      'sensor|type=', COALESCE(NULLIF(`camBien`, ''), 'temp'),
      '|op=', CASE WHEN COALESCE(`toanTu`, '') = '=' THEN '=' ELSE COALESCE(NULLIF(`toanTu`, ''), '>') END,
      '|value=', COALESCE(`giaTriKichHoat`, 0)
    )
END
WHERE (`dieuKien` IS NULL OR `dieuKien` = '' OR `dieuKien` NOT LIKE 'sensor|%' AND `dieuKien` NOT LIKE 'timer|%');

UPDATE `kich_ban_tu_dong`
SET `hanhDong` = CONCAT(
  'action|device=', COALESCE(NULLIF(`thietBi`, ''), 'relay'),
  '|state=', UPPER(COALESCE(NULLIF(`lenhThucThi`, ''), 'ON'))
)
WHERE (`hanhDong` IS NULL OR `hanhDong` = '' OR `hanhDong` NOT LIKE 'action|%');

INSERT INTO `kich_ban_hanh_dong` (`idKichBan`, `thuTuChay`, `loaiHanhDong`, `idThietBi`, `lenh`, `giaTriLenh`, `moTa`, `trangThai`)
SELECT kb.`idKichBan`,
       1,
       'device_control',
       NULL,
       UPPER(COALESCE(NULLIF(kb.`lenhThucThi`, ''), 'ON')),
       COALESCE(NULLIF(kb.`thietBi`, ''), 'relay'),
       kb.`moTa`,
       1
FROM `kich_ban_tu_dong` kb
LEFT JOIN `kich_ban_hanh_dong` kbhd ON kbhd.`idKichBan` = kb.`idKichBan`
WHERE kbhd.`idHanhDong` IS NULL;

UPDATE `canh_bao` cb
INNER JOIN `cam_bien` c ON c.`idCamBien` = cb.`idCamBien`
INNER JOIN `thiet_bi` tb ON tb.`idThietBi` = c.`idThietBi`
SET cb.`idPhong` = tb.`idPhong`
WHERE cb.`idPhong` IS NULL;

UPDATE `canh_bao`
SET `tenCanhBao` = COALESCE(NULLIF(`tenCanhBao`, ''), 'Cảnh báo hệ thống'),
    `loaiCanhBao` = COALESCE(NULLIF(`loaiCanhBao`, ''), 'threshold'),
    `dieuKien` = COALESCE(NULLIF(`dieuKien`, ''), 'legacy_threshold'),
    `trangThaiHoatDong` = CASE WHEN COALESCE(`trangThaiXuLy`, 0) = 0 THEN 1 ELSE 0 END,
    `thoiGianBatDau` = COALESCE(`thoiGianBatDau`, `ngayTao`)
WHERE `tenCanhBao` IS NULL
   OR `loaiCanhBao` IS NULL
   OR `dieuKien` IS NULL
   OR `thoiGianBatDau` IS NULL;

UPDATE `cam_bien`
SET `loaiCamBien` = CASE
    WHEN LOWER(COALESCE(`loaiCamBien`, '')) IN ('nhiệt độ', 'nhiet do', 'temp', 'temperature') THEN 'temp'
    WHEN LOWER(COALESCE(`loaiCamBien`, '')) IN ('độ ẩm', 'do am', 'hum', 'humidity') THEN 'hum'
    WHEN LOWER(COALESCE(`loaiCamBien`, '')) IN ('gas', 'khí gas', 'khi gas') THEN 'gas'
    WHEN LOWER(COALESCE(`loaiCamBien`, '')) IN ('chuyển động', 'chuyen dong', 'pir', 'motion') THEN 'pir'
    WHEN LOWER(COALESCE(`loaiCamBien`, '')) IN ('khói', 'khoi', 'smoke') THEN 'smoke'
    ELSE `loaiCamBien`
END;

UPDATE `cam_bien`
SET `giaTriMin` = CASE
    WHEN `loaiCamBien` = 'temp' THEN COALESCE(`giaTriMin`, 0)
    WHEN `loaiCamBien` = 'hum' THEN COALESCE(`giaTriMin`, 0)
    WHEN `loaiCamBien` = 'gas' THEN COALESCE(`giaTriMin`, 0)
    WHEN `loaiCamBien` = 'pir' THEN COALESCE(`giaTriMin`, 0)
    ELSE `giaTriMin`
END,
`giaTriMax` = CASE
    WHEN `loaiCamBien` = 'temp' THEN COALESCE(`giaTriMax`, 50)
    WHEN `loaiCamBien` = 'hum' THEN COALESCE(`giaTriMax`, 100)
    WHEN `loaiCamBien` = 'gas' THEN COALESCE(`giaTriMax`, 5000)
    WHEN `loaiCamBien` = 'pir' THEN COALESCE(`giaTriMax`, 1)
    ELSE `giaTriMax`
END;
