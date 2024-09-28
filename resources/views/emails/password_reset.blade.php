<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Restablecimiento de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333333;
        }

        p {
            color: #666666;
            line-height: 1.5;
        }

        a {
            display: inline-block;
            background-color: #0067a5;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #045888;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Solicitud de Restablecimiento de Contraseña</h1>
    <p>Hemos recibido una solicitud para restablecer tu contraseña. Puedes hacerlo a través del siguiente enlace:</p>
    <a href="{{ url('/customer/password/create', $token) }}">Restablecer Contraseña</a>
    <p>Si no has solicitado un restablecimiento de contraseña, no es necesario que hagas nada.</p>
    <p>Gracias,</p>
    <strong>El equipo de {{ config('app.name') }}</strong>
</div>
</body>
</html>
