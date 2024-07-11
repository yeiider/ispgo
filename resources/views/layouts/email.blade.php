<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
       @yield('styles')
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('logo.png') }}" alt="Logo">
    </div>

    @yield('content')

    <div class="footer">
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </div>
</div>
</body>
</html>
