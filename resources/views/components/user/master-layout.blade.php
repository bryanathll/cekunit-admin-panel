<!DOCTYPE html>
<html lang="en">

@include('includes.user.head')

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black"
    data-headerbg="color_1">

    <div id="main-wrapper">
        @include('components.user.nav-header')

        @include('components.user.header')

        @include('components.user.sidebar')

        @yield('content')

        @include('components.user.footer')

    </div>
    @include('includes.user.script')
</body>

</html>
