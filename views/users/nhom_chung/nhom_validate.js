function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(tab + '-tab-content').classList.remove('hidden');
    
    document.querySelectorAll('[id$="-tab"]').forEach(el => {
        el.classList.remove('text-red-600', 'border-red-600', 'dark:text-red-500', 'dark:border-red-500');
        el.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
    });
    
    const activeTab = document.getElementById(tab + '-tab');
    activeTab.classList.remove('border-transparent');
    activeTab.classList.add('text-red-600', 'border-red-600');
}

function updateModuleCheckbox(module) {
    const checkboxes = document.querySelectorAll(`.perm-checkbox[data-module="${module}"]`);
    const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    
    if (moduleCheckbox) {
        moduleCheckbox.checked = checkedCount === checkboxes.length;
        moduleCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
    }
}

function toggleModule(module, checked) {
    document.querySelectorAll(`.perm-checkbox[data-module="${module}"]`).forEach(cb => cb.checked = checked);
}

function checkAllPermissions() {
    document.querySelectorAll('.perm-checkbox, .module-checkbox').forEach(cb => {
        cb.checked = true;
        cb.indeterminate = false;
    });
}

function uncheckAllPermissions() {
    document.querySelectorAll('.perm-checkbox, .module-checkbox').forEach(cb => {
        cb.checked = false;
        cb.indeterminate = false;
    });
}

function updatePreview() {
    const nameInput = document.getElementById('tenNhom');
    const previewText = document.getElementById('previewText');
    const badge = document.getElementById('badgePreview');
    if (!nameInput || !previewText || !badge) return;

    previewText.textContent = nameInput.value.trim() || "Tên nhóm...";
    const color = document.querySelector('input[name="badge_color"]:checked')?.value || 'blue';
    
    badge.className = `inline-flex items-center px-3 py-2 text-sm font-semibold rounded-full shadow-sm transition-all duration-300 border bg-${color}-100 text-${color}-700 border-${color}-200 dark:bg-${color}-900 dark:text-${color}-300 dark:border-${color}-800`;
}

function validateGroupCode() {
    const groupCode = document.getElementById('maNhom');
    const errorSpan = document.getElementById('maNhom_error');
    if (!groupCode || groupCode.readOnly) return true;

    const value = groupCode.value.trim();
    const regex = /^[A-Z0-9_]+$/;

    if (!value) {
        showError(groupCode, errorSpan, 'Mã nhóm không được để trống');
        return false;
    } else if (value.includes(' ')) {
        showError(groupCode, errorSpan, 'Mã nhóm không được chứa dấu cách');
        return false;
    } else if (!regex.test(value)) {
        showError(groupCode, errorSpan, 'Chỉ dùng chữ hoa, số và gạch dưới');
        return false;
    } else if (value.length < 3) {
        showError(groupCode, errorSpan, 'Mã nhóm phải có ít nhất 3 ký tự');
        return false;
    } else {
        showSuccess(groupCode, errorSpan);
        return true;
    }
}

function validateGroupName() {
    const groupName = document.getElementById('tenNhom');
    const errorSpan = document.getElementById('tenNhom_error');
    if (!groupName) return true;

    const value = groupName.value.trim();

    if (!value) {
        showError(groupName, errorSpan, 'Tên nhóm không được để trống');
        return false;
    } else if (value.length < 3) {
        showError(groupName, errorSpan, 'Tên nhóm phải có ít nhất 3 ký tự');
        return false;
    } else {
        showSuccess(groupName, errorSpan);
        return true;
    }
}

function showError(input, errorEl, message) {
    input.classList.add('border-red-600');
    input.classList.remove('border-green-600');
    if (errorEl) {
        errorEl.classList.remove('hidden');
        errorEl.textContent = message;
    }
}

function showSuccess(input, errorEl) {
    input.classList.remove('border-red-600');
    input.classList.add('border-green-600');
    if (errorEl) errorEl.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    updatePreview();
    document.querySelectorAll('.module-checkbox').forEach(cb => updateModuleCheckbox(cb.dataset.module));

    const groupCode = document.getElementById('maNhom');
    const groupName = document.getElementById('tenNhom');
    const form = document.getElementById('mainForm');

    if (groupCode) {
        groupCode.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            validateGroupCode();
        });
    }

    if (groupName) {
        groupName.addEventListener('input', () => {
            validateGroupName();
            updatePreview();
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            const isCodeValid = validateGroupCode();
            const isNameValid = validateGroupName();

            if (!isCodeValid || !isNameValid) {
                e.preventDefault();
                switchTab('info');
                if (!isCodeValid && groupCode) groupCode.focus();
                else if (!isNameValid && groupName) groupName.focus();
                alert('Vui lòng kiểm tra lại thông tin bị lỗi!');
            }
        });
    }
});