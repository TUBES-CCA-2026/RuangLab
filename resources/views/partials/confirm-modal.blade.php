{{-- Modal konfirmasi generik, dipakai lewat atribut data-confirm="pesan" pada <form> --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body text-center p-4">
                <div id="confirmModalIcon" class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                    <i class="bi bi-exclamation-triangle fs-2 text-danger"></i>
                </div>
                <h6 class="fw-bold mb-2">Konfirmasi</h6>
                <p id="confirmModalMessage" class="text-secondary small mb-4"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmModalOkBtn" class="btn btn-danger px-4">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var modalEl = document.getElementById('confirmModal');
    if (!modalEl || typeof bootstrap === 'undefined') return;

    var modal      = new bootstrap.Modal(modalEl);
    var messageEl  = document.getElementById('confirmModalMessage');
    var iconWrap   = document.getElementById('confirmModalIcon');
    var okBtn      = document.getElementById('confirmModalOkBtn');
    var pendingForm = null;

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm')) return;
        if (form.dataset.confirmed === 'true') return; // sudah dikonfirmasi, biarkan submit asli jalan

        e.preventDefault();
        pendingForm = form;

        var variant = form.getAttribute('data-confirm-variant') || 'danger';
        var icon    = form.getAttribute('data-confirm-icon') || (variant === 'danger' ? 'bi-exclamation-triangle' : 'bi-question-circle');

        messageEl.textContent = form.getAttribute('data-confirm');
        iconWrap.className = 'rounded-circle bg-' + variant + '-subtle d-inline-flex align-items-center justify-content-center mb-3';
        iconWrap.innerHTML  = '<i class="bi ' + icon + ' fs-2 text-' + variant + '"></i>';
        okBtn.className     = 'btn btn-' + variant + ' px-4';
        okBtn.textContent    = form.getAttribute('data-confirm-label') || 'Ya, Lanjutkan';

        modal.show();
    });

    okBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        modal.hide();
        pendingForm.dataset.confirmed = 'true';
        (pendingForm.requestSubmit ? pendingForm.requestSubmit() : pendingForm.submit());
        pendingForm = null;
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        pendingForm = null;
    });
})();
</script>
