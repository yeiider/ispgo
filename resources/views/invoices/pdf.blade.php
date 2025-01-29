<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .card img {
            max-width: 100px;
        }

        h2 {
            font-size: 24px;
            font-weight: bold;
        }

        .text-muted {
            color: #6c757d;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
        }

        .bg-success {
            background-color: #28a745;
            color: #fff;
        }

        .btn-dark {
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-dark:hover {
            background-color: #23272b;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        hr {
            margin: 20px 0;
            border: 0;
            border-top: 1px solid #ddd;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow p-4 text-center">


            <h2 class="fw-bold mt-3">Factura</h2>
            <p class="text-muted">Referencia {{ $invoice->increment_id }}</p>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p><strong>TOTAL</strong><br>{{ $invoice->amount }}</p>
                </div>
                <div>
                    <p><strong>ESTADO DE PAGO:</strong><br> <span class="badge bg-success"><</span></p>
                </div>
                <div>
                    <p><strong>CLIENTE:</strong><br> Yeider Adrian Mina</p>
                </div>
            </div>

            <hr>

            <h5>Resumen</h5>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Monto</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Impuestos</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td><strong>Cantidad</strong></td>
                    <td><strong>$105,000.00</strong></td>
                </tr>
                <tr>
                    <td>Descuento</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td><strong>SubTotal</strong></td>
                    <td><strong>$105,000.00</strong></td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>$105,000.00</strong></td>
                </tr>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <p>Escanea el código QR para más información</p>
                <img src="{!! \App\Helpers\QrCodeHelper::generateQrCode("0000000015") !!}" alt="QR Code" class="img-fluid">
            </div>

            <button class="btn btn-dark mt-3">Continuar</button>

            <hr>

            <h5>Términos y Condiciones</h5>
            <p class="text-muted">Esta factura es un documento válido y debe ser pagada en la fecha indicada. Cualquier consulta o reclamo debe realizarse dentro de los 5 días hábiles posteriores a la emisión. El incumplimiento en el pago puede generar intereses adicionales.</p>

            <h5>Datos de la Empresa</h5>
            <p class="text-muted">
                <strong>ISP Solutions S.A.S</strong><br>
                NIT: 900123456-7<br>
                Dirección: Av. Principal 456, Bogotá<br>
                Teléfono: +57 1 234 5678<br>
                Email: contacto@isp-solutions.com
            </p>

            <p class="mt-4">Si tiene alguna pregunta, comuníquese a <a href="mailto:contacto@isp-solutions.com">contacto@isp-solutions.com</a> o llame al <strong>+57 1 234 5678</strong></p>
        </div>
    </div>
</body>
</html>
