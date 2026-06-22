@if(count($urls) > 0)
    @php $galleryId = 'itemGallery' . ($id ?? uniqid()); @endphp
    <div class="item-photo-gallery">
        <button type="button"
            class="btn p-0 border-0 w-100 d-block gallery-main-link"
            data-bs-toggle="modal"
            data-bs-target="#{{ $galleryId }}Modal"
            aria-label="Enlarge photo">
            <img src="{{ $urls[0] }}" class="w-100 rounded-top" alt="{{ $alt ?? 'Item photo' }}"
                style="max-height:340px;object-fit:cover;cursor:zoom-in">
        </button>
        @if(count($urls) > 1)
            <div class="p-3 border-top bg-light">
                <p class="small text-muted mb-2">
                    <i class="bi bi-images me-1"></i>
                    {{ count($urls) }} photos — tap to enlarge
                </p>
                <div class="row g-2">
                    @foreach($urls as $index => $url)
                        <div class="col-3 col-sm-2">
                            <button type="button"
                                class="btn p-0 border-0 w-100 gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                                data-gallery="{{ $galleryId }}"
                                data-url="{{ $url }}"
                                aria-label="View photo {{ $index + 1 }}">
                                <img src="{{ $url }}" alt="" class="img-fluid rounded border"
                                    style="aspect-ratio:1;object-fit:cover;width:100%">
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="px-3 py-2 border-top bg-light">
                <p class="small text-muted mb-0"><i class="bi bi-zoom-in me-1"></i> Tap photo to enlarge</p>
            </div>
        @endif
    </div>

    <div class="modal fade gallery-photo-modal" id="{{ $galleryId }}Modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2 text-center">
                    <img src="{{ $urls[0] }}" class="img-fluid rounded gallery-modal-img" alt="{{ $alt ?? 'Item photo' }}">
                </div>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
        <script>
            document.querySelectorAll('.gallery-thumb').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var galleryId = btn.getAttribute('data-gallery');
                    var url = btn.getAttribute('data-url');
                    var gallery = btn.closest('.item-photo-gallery');
                    var mainImg = gallery.querySelector('.gallery-main-link img');
                    var modalImg = document.querySelector('#' + galleryId + 'Modal .gallery-modal-img');
                    mainImg.src = url;
                    if (modalImg) {
                        modalImg.src = url;
                    }
                    gallery.querySelectorAll('.gallery-thumb').forEach(function (t) {
                        t.classList.remove('active');
                    });
                    btn.classList.add('active');
                });
            });

            document.querySelectorAll('.gallery-photo-modal').forEach(function (modal) {
                modal.addEventListener('show.bs.modal', function () {
                    var gallery = modal.previousElementSibling;
                    if (! gallery) {
                        return;
                    }
                    var mainImg = gallery.querySelector('.gallery-main-link img');
                    var modalImg = modal.querySelector('.gallery-modal-img');
                    if (mainImg && modalImg) {
                        modalImg.src = mainImg.src;
                    }
                });
            });
        </script>
        @endpush
    @endonce
@endif
