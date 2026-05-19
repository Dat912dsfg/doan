document.addEventListener('DOMContentLoaded', function() {
    console.log("Đang kiểm tra dữ liệu nhập!");

    const userForm = document.getElementById('userForm');
    const getEl = (id) => document.getElementById(id);

    const inputs = {
        maNguoiDung: getEl('maNguoiDung'),
        fullname: getEl('fullname'),
        email: getEl('email'),
        phone: getEl('phone'),
        role: getEl('role'),
        password: getEl('password')
    };

    function setStatus(element, errorId, isValid) {
        if (!element) return true;
        
        const errorMsg = document.getElementById(errorId);
        if (isValid) {
            element.classList.remove('border-red-600', 'input-invalid');
            element.classList.add('border-green-600', 'input-valid');
            if (errorMsg) errorMsg.classList.add('hidden');
        } else {
            element.classList.remove('border-green-600', 'input-valid');
            element.classList.add('border-red-600', 'input-invalid');
            if (errorMsg) errorMsg.classList.remove('hidden');
        }
        return isValid;
    }

    const validators = {
        maNguoiDung: () => setStatus(inputs.maNguoiDung, 'maNguoiDung_error', inputs.maNguoiDung.value.trim().length > 0),
        fullname: () => setStatus(inputs.fullname, 'fullname_error', inputs.fullname.value.trim().length > 0),
        email: () => {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return setStatus(inputs.email, 'email_error', regex.test(inputs.email.value));
        },
        phone: () => {
            if (!inputs.phone || !inputs.phone.value) return true;
            const regex = /^(0[3|5|7|8|9])[0-9]{8}$/;
            return setStatus(inputs.phone, 'phone_error', regex.test(inputs.phone.value));
        },
        role: () => setStatus(inputs.role, 'role_error', inputs.role.value !== "")
    };

    if (inputs.maNguoiDung) inputs.maNguoiDung.addEventListener('input', validators.maNguoiDung);
    if (inputs.fullname) inputs.fullname.addEventListener('input', validators.fullname);
    if (inputs.email) inputs.email.addEventListener('input', validators.email);
    if (inputs.phone) inputs.phone.addEventListener('input', validators.phone);
    if (inputs.role) inputs.role.addEventListener('change', validators.role);

    const toggleBtn = getEl('togglePassword');
    if (toggleBtn && inputs.password) {
        toggleBtn.addEventListener('click', function() {
            const type = inputs.password.type === 'password' ? 'text' : 'password';
            inputs.password.type = type;
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            const mv = inputs.maNguoiDung ? validators.maNguoiDung() : true;
            const fv = inputs.fullname ? validators.fullname() : true;
            const ev = inputs.email ? validators.email() : true;
            const pv = inputs.phone ? validators.phone() : true;
            const rv = inputs.role ? validators.role() : true;

            if (!(mv && fv && ev && pv && rv)) {
                e.preventDefault();
                alert('Vui lòng kiểm tra lại các trường thông tin màu đỏ!');
            }
        });
    } else {
        console.error("Không tìm thấy thẻ form có ID 'userForm'!");
    }
});