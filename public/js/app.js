document.addEventListener('DOMContentLoaded', function () {
    // Initialize all Bootstrap toasts
    const toastElements = document.querySelectorAll('.toast');
    toastElements.forEach(function (el) {
        const toast = new bootstrap.Toast(el, { delay: 4500 });
        toast.show();
    });

    // Quantity increment / decrement buttons
    document.querySelectorAll('[data-qty-btn]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;

            const min = parseInt(input.getAttribute('min') || '1', 10);
            const max = parseInt(input.getAttribute('max') || '999999', 10);
            let current = parseInt(input.value || min, 10);

            if (this.getAttribute('data-qty-btn') === 'plus' && current < max) {
                current++;
            } else if (this.getAttribute('data-qty-btn') === 'minus' && current > min) {
                current--;
            }
            input.value = current;
            input.dispatchEvent(new Event('change'));
        });
    });
});
