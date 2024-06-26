<!DOCTYPE html>
<html lang="en">
<head>
 @include('partial.header')
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      @include('partial.topbar')
      @include('partial.sidebar')
      @include('sweetalert::alert')
      <!-- Main Content -->
      @yield('content')
      @include('partial.footer')
    </div>
  </div>

 @include('partial.script')
</body>
</html>
