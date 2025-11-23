<!DOCTYPE html lang={{ app()->getLocale()}}>
<html>
@include('web.layouts.head')
<body>
@include('web.layouts.header')
@yield('content')

@include('web.layouts.footer')
@include('web.layouts.js')
</body>
</html>
