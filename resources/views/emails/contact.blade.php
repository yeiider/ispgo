<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje de Contacto</title>

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
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
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
            background-color: #0ea5e9;
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
        <h1>Nuevo Mensaje de Contacto</h1>
        <p><strong>Nombre:</strong> {{ $contactData['firstname'] }}</p>
        <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
        <p><strong>Teléfono:</strong> {{ $contactData['phoneNumber'] }}</p>
        <p><strong>Mensaje:</strong></p>
        <p>{{ $contactData['details'] }}</p>
    </div>
</body>
</html>
