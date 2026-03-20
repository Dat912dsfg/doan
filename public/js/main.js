function triggerModal(options) {
    const modalTitle = document.getElementById('modal-title');
    const modalDescription = document.getElementById('modal-description');
    const confirmBtn = document.getElementById('modal-confirm-btn');

    if (modalTitle) {
        modalTitle.textContent = options.title || 'Xác nhận';
    }

    if (modalDescription) {
        modalDescription.innerHTML = options.description || 'Bạn có chắc chắn muốn thực hiện?';
    }

    if (confirmBtn) {
        confirmBtn.className = `w-full px-5 py-3 text-sm text-center text-white rounded-lg sm:w-auto transition-colors ${options.btnClass || 'bg-purple-600'}`;
        confirmBtn.textContent = options.confirmText || 'Xác nhận';

        confirmBtn.onclick = function(e) {
            if (options.confirmAction && typeof options.confirmAction === 'function') {
                e.preventDefault();
                options.confirmAction();
            } else if (options.confirmUrl) {
                window.location.href = options.confirmUrl;
            }
        };
    }
}