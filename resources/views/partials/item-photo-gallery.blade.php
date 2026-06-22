@if(count($urls) > 0)
    @php $galleryId = 'itemGallery' . ($id ?? uniqid()); @endphp
    <div class="item-photo-gallery">
        <a href="{{ $urls[0] }}" target="_blank" rel="noopener" class="d-block gallery-main-link">
            <img src="{{ $urls[0] }}" class="w-100 rounded-top" alt="{{ $alt ?? 'Item photo' }}"
                style="max-height:340px;object-fit:cover;cursor:zoom-in">
        </a>
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
        @endif
    </div>

    @once
        @push('scripts')
        <script>
            document.querySelectorAll('.gallery-thumb').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var galleryId = btn.getAttribute('data-gallery');
                    var url = btn.getAttribute('data-url');
                    var gallery = btn.closest('.item-photo-gallery');
                    var main = gallery.querySelector('.gallery-main-link');
                    var mainImg = main.querySelector('img');
                    main.href = url;
                    mainImg.src = url;
                    gallery.querySelectorAll('.gallery-thumb').forEach(function (t) {
                        t.classList.remove('active');
                    });
                    btn.classList.add('active');
                });
            });
        </script>
        @endpush
    @endonce
@endif
