<div class="col-md-6 col-12 mb-3">
    <div class="service-card" style="background-color: #E3E3E3;">
        <div>
            <h2 class="text-center fw-bold">{{ $section->title }}</h2>
            <p class="text-center">{{ $section->sub }}</p>
        </div>
        <div class="d-flex justify-content-center">
            <button type="button" class="btn-main" onclick="window.location.href='{{  $section->link }}'">{{ trns('shop_now') }}</button>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <img
                src="{{ getFile($section->image) }}"
                alt="no banner">
        </div>
    </div>
</div>
