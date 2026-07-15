@if (session('success') || session('error'))
<div class="rl-toast-container position-fixed top-0 end-0 p-3">
    @if (session('success'))
        <div class="toast align-items-center border-0 rl-toast rl-toast-success" role="alert" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="toast align-items-center border-0 rl-toast rl-toast-error" role="alert" data-bs-delay="5500">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>
<script>
    document.querySelectorAll('.rl-toast-container .toast').forEach(function (el) {
        new bootstrap.Toast(el).show();
    });
</script>
@endif
