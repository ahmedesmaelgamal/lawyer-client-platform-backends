<div class="banner-main">
    <div class="container">
        <div class="banner">
            <div class="d-flex justify-content-center mb-3">
                <img src="{{ getFile($section->image) }}" alt="no banner"
                     style="height: 160px; width: 100%;">
            </div>
            <div>
                <h1 class="text-center">{{ $section->title }}</h1>
                <p class="text-center">{{ $section->sub }}</p>
            </div>
            <div class="d-flex justify-content-center">
                <button type="button" onclick="window.location.href='/about'" class="btn-main" >{{ trns('about_Us') }}</button>
            </div>
        </div>
    </div>
</div>
