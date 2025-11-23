     <!-- footer -->
     <div class="footer">
         <div class="container">
             <div class="row">
                 <div class="col-lg-3 col-md-6 col-12 mb-3">
                     <a href="{{ route('web.home') }}">
                         <img src="{{getFile($setting->logo)}}" style="height: 50px;"
                             alt="no logo">
                     </a>
                 </div>
                 <div class="col-lg-3 col-md-6 col-12 mb-3">
                     <h5 class="text-light mb-4">{{ trns('Products') }}</h5>
                     @foreach ($categories as $category)
                         <ul class="list-unstyled lh-lg p-0">
                             <li><a href="{{ route('web.products', $category->id) }}"
                                     class="text-decoration-none">{{ $category->title }}</a></li>
                         </ul>
                     @endforeach
                 </div>
                 <div class="col-lg-3 col-md-6 col-12 mb-3">
                     <h5 class="text-light mb-4">{{ trns('Company') }}</h5>
                     <ul class="list-unstyled lh-lg p-0">
                         <li><a href="{{ route('web.about') }}" class="text-decoration-none"> {{trns('about_Us')}}</a></li>
                     </ul>
                 </div>
                 <div class="col-lg-3 col-md-6 co-12 mb-4">
                     <h5 class="text-light mb-4">{{ trns('Contact Us') }}</h5>
                     <div class="mb-3">
                         <i class="fa-solid fa-location-dot text-light ms-2"></i>
                         <span class="text-light">{{ $setting->address }}</span>
                     </div>
                     <div>
                         <i class="fa-solid fa-mobile-screen-button text-light ms-2"></i>
                         {{-- <a href="tel:+96895992950" class="text-light text-decoration-none">{{$setting->phones[1]}}</a> --}}
                         @if (is_array($setting->phones) && !empty($setting->phones))
                         <a href="tel:+2{{ $setting->phones[0]}}"
                             class="text-light text-decoration-none">{{ $setting->phones[0] }}</a>
                     @else
                         <a href="tel:+96895992950" class="text-light text-decoration-none">Default Number</a>
                     @endif

                     </div>
                 </div>
                 <div class="col-12">
                     <div class="icon mb-3">
                         <a href="{{ $setting->instagram }}" class="text-decoration-none" target="_blank">
                             <i class="fa-brands fa-instagram fa-lg me-4"></i>
                         </a>
                         <a href="{{ $setting->linkedin }}" class="text-decoration-none" target="_blank">
                             <i class="fa-brands fa-linkedin-in fa-lg me-4"></i>
                         </a>
                         <a  href="https://wa.me/0968{{ $setting->phones[1]}}" class="text-decoration-none" target="_blank">
                            <i class="fa-brands fa-whatsapp fa-lg me-4"></i>
                        </a>
                     </div>
                 </div>
             </div>
             <hr style="background-color: white;">
             <div class="">
                 <div class="content text-light fs-6 mb-2">
                     {{ trns('© All rights reserved Smart Tech') }}
                 </div>
             </div>
         </div>
     </div>

     <!-- scroll to top -->
     <a href="#" class="scroll-top">
         <i class="fa-solid fa-angle-up fa-lg text-white"></i>
     </a>
