{{-- SweetAlert2 CDN, Styling, & Engineering Cafe Reusable Helper --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-popup {
        border-radius: 1.5rem !important;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
    }
    .swal2-actions {
        gap: 0.75rem !important;
        margin-top: 1.25rem !important;
    }
    .swal2-styled.swal2-confirm {
        border-radius: 0.75rem !important;
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        padding: 0.625rem 1.25rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1) !important;
    }
    .swal2-styled.swal2-cancel {
        border-radius: 0.75rem !important;
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        padding: 0.625rem 1.25rem !important;
    }
    .swal2-toast {
        border-radius: 1rem !important;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        padding: 0.75rem 1rem !important;
    }
</style>
<script>
    // 1. Toast Notification Helper (Pojok Kanan Atas)
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // Helper Global: showToast('Pesan', 'success' | 'error' | 'warning' | 'info')
    function showToast(message, icon = 'success') {
        Toast.fire({
            icon: icon,
            title: message
        });
    }

    // 2. Modal Konfirmasi Reusable Bertema Engineering Cafe
    function confirmDialog({
        title = 'Apakah Anda yakin?',
        text = '',
        icon = 'warning',
        confirmButtonText = 'Ya, Lanjutkan',
        cancelButtonText = 'Batal',
        confirmButtonColor = '#DA1212',
        cancelButtonColor = '#94A3B8',
        onConfirm = () => {}
    }) {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: cancelButtonColor,
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl shadow-2xl p-6 font-sans border border-slate-100',
                title: 'text-lg font-black text-slate-800',
                htmlContainer: 'text-xs text-slate-500 font-medium leading-relaxed',
                confirmButton: 'px-5 py-2.5 rounded-xl text-white font-bold text-xs shadow-md transition active:scale-95',
                cancelButton: 'px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-bold text-xs hover:bg-slate-50 transition active:scale-95'
            }
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
            return result;
        });
    }

    // 3. Listener Global untuk Form yang memiliki atribut [data-confirm]
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.dataset && form.dataset.confirm && !form.dataset.confirmed) {
            e.preventDefault();
            confirmDialog({
                title: form.dataset.confirmTitle || 'Konfirmasi Tindakan',
                text: form.dataset.confirm,
                icon: form.dataset.confirmIcon || 'warning',
                confirmButtonText: form.dataset.confirmBtn || 'Ya, Lanjutkan',
                confirmButtonColor: form.dataset.confirmColor || '#DA1212',
                onConfirm: () => {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        }
    });

    // 4. Auto-trigger Laravel Session Alerts
    @if (session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    @if (session('error'))
        showToast(@json(session('error')), 'error');
    @endif
</script>
