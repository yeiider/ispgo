<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
        }
        .header, .footer {
            background-color: #ffffff;
            text-align: center;
            padding: 10px 0;
        }
        .header img {
            max-width: 100px;
        }
        .footer p {
            font-size: 12px;
            color: #999999;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00b0ff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
       @yield('styles')
    </style>
</head>
<body>
<div class="container">

    @yield('content')

    <div class="footer">
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </div>
</div>
</body>
</html>
