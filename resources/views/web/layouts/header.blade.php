<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="{{ route('web.home') }}">
        <img src="{{ getFile($setting->logo) }}" style="height: 50px;" alt="no logo">
      </a>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="{{ LaravelLocalization::getLocalizedURL('ar') }}">{{ trns('Arabic') }}</a>
        </li>
        <li class="nav-item" style="{{  lang() == 'en' ? 'border-left: 1px solid white; padding-left: 10px;' : 'border-right: 1px solid white; padding-right: 10px;'}};">
          <a class="nav-link" aria-current="page" href="{{ LaravelLocalization::getLocalizedURL('en') }}">{{ trns('English') }}</a>
        </li>
      </ul>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav {{ lang() == 'ar' ? 'me-auto' : 'ms-auto' }}">
            {{-- <li class="slide">
                <a class="side-menu__item {{ routeActive('web.home') }}" href="{{ route('web.home') }}">
                    <i class="fe fe-log-out side-menu__icon"></i>
                    <span class="side-menu__label">{{ trns('الرئيسية') }}</span>
                </a>
            </li> --}}
          <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'web.home' ? 'linkactive' : ''}}" aria-current="page" href="{{ route('web.home') }}">{{ trns('home') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'web.store' ? 'linkactive' : ''}}" aria-current="page" href="{{ route('web.store') }}">{{ trns('store') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'web.about' ? 'linkactive' : ''}}" aria-current="page" href="{{ route('web.about') }}">{{ trns('about_Us') }}</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
