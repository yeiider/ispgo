<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body>
@inertia
<script type="text/javascript" src="https://checkout.wompi.co/widget.js"></script>
</body>
</html>
