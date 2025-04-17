<div aria-live="polite" aria-atomic="true" class="position-absolute p-3 top-3 start-50 translate-middle-x" style="z-index: 1080; max-width: 20rem;">
    {{-- Success --}}
    @if (session('success'))
        <div class="toast bg-white border-0 show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto d-flex align-items-center"> <i data-feather="check-circle" width="20" class="me-1"></i> Berhasil</strong>
                <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {!! session('success') !!}
            </div>
        </div>
    @endif

    {{-- Error --}}
    @if (session('error'))
        <div class="toast bg-white border-0 show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto d-flex align-items-center"> <i data-feather="alert-octagon" width="20" class="me-1"></i> Gagal</strong>
                <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {!! session('error') !!}
            </div>
        </div>
    @endif
</div>
