function validatePermissionKey() {
    const keyInput = document.getElementById('permissionKey');
    const errorSpan = document.getElementById('permissionKey_error');
    const helperSpan = document.getElementById('permissionKey_helper');
    if (!keyInput) return true; // Chế độ sửa không có ID này

    const value = keyInput.value.trim();
    if (!value) return showError(keyInput, errorSpan, helperSpan, 'Mã quyền không được để trống');
    if (value.includes(' ')) return showError(keyInput, errorSpan, helperSpan, 'Không được chứa dấu cách');
    if (!value.includes('.')) return showError(keyInput, errorSpan, helperSpan, 'Phải có dạng "resource.action"');
    if (!/^[a-z0-9._]+$/.test(value)) return showError(keyInput, errorSpan, helperSpan, 'Chỉ dùng chữ thường, số, dấu chấm và gạch dưới');

    showSuccess(keyInput, errorSpan, helperSpan, 'Mã quyền hợp lệ');
    return true;
}

function validatePermissionName() {
    const nameInput = document.getElementById('permissionName');
    const errorSpan = document.getElementById('permissionName_error');
    const value = nameInput.value.trim();

    if (!value) {
        nameInput.classList.add('border-red-600');
        errorSpan.classList.remove('hidden');
        errorSpan.textContent = 'Tên hiển thị không được để trống';
        return false;
    } else if (value.length < 3) {
        nameInput.classList.add('border-red-600');
        errorSpan.classList.remove('hidden');
        errorSpan.textContent = 'Tên hiển thị phải từ 3 ký tự';
        return false;
    }
    nameInput.classList.remove('border-red-600');
    nameInput.classList.add('border-green-600');
    errorSpan.classList.add('hidden');
    return true;
}

function showError(input, errorEl, helperEl, msg) {
    input.classList.add('border-red-600');
    errorEl.classList.remove('hidden');
    errorEl.textContent = msg;
    if (helperEl) helperEl.classList.add('hidden');
    return false;
}

function showSuccess(input, errorEl, helperEl, msg) {
    input.classList.remove('border-red-600');
    input.classList.add('border-green-600');
    errorEl.classList.add('hidden');
    if (helperEl) {
        helperEl.classList.remove('hidden');
        helperEl.classList.add('text-green-600');
        helperEl.innerHTML = ` ${msg}`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const keyInput = document.getElementById('permissionKey');
    const nameInput = document.getElementById('permissionName');
    const form = document.querySelector('form');

    if (keyInput) {
        keyInput.addEventListener('input', function() { this.value = this.value.toLowerCase(); validatePermissionKey(); });
        keyInput.addEventListener('blur', validatePermissionKey);
    }
    if (nameInput) {
        nameInput.addEventListener('input', validatePermissionName);
        nameInput.addEventListener('blur', validatePermissionName);
    }

    form.addEventListener('submit', function(e) {
        const k = validatePermissionKey();
        const n = validatePermissionName();
        if (!k || !n) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại thông tin!');
        }
    });
});