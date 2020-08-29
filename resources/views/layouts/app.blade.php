<!doctype html>
<html>
<head>
<script src="{{ asset('js/app.js') }}" defer></script>
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="text-center">
  <div class="container">
    @yield('content')
  </div>
</body>
</html>