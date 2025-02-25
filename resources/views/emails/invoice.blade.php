<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Factura'). ' #'.$invoice->increment_id }}</title>
</head>
<body>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 700px;
        margin: 20px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        --wbkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 0;
    }

    .hero {
        background-size: cover;
        --wbkit-background-size: cover;
        background-position: left;
        height: 500px;
        border-radius: 10px 10px 0 0;
    }

    .header img {
        max-width: 110px;
    }

    .title {
        background: #004dcd;
        background: linear-gradient(90deg, #004dcd 0%, #00448c 94%);
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .bg-gradient {
        background: #004dcd;
        background: linear-gradient(90deg, #004dcd 0%, #00448c 94%);
        border-top-right-radius: 10px;
    }

    .sub-header .content p {
        font-size: 14px;
        font-weight: bold;
    }

    .sub-header .content a {
        color: white;
    }

    .content-info {
        padding: 20px;
    }

    .info {
        margin: 20px 0;
        display: flex;
    }

    .info div {
        text-align: center;
        padding: 0 30px;
        color: #666;
        width: calc(100% / 3);
    }

    .info div strong {
        color: #00448c;
    }

    .info div.total-amount {
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
    }

    .info div p {
        margin: 8px 0;
        font-size: 14px;
        color: #0a0a0a;
    }

    .payment-methods {
        text-align: center;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        display: flex;
        --webkit-display: flex;
        align-content: center;
        justify-content: space-between;
        -webkit-justify-content: space-between;
        align-items: center;
        -webkit-align-items: center;
        flex-wrap: wrap;
        --webkit-flex-wrap: wrap;
    }

    .payment-methods .title {
        border-radius: 6px;
        width: 100%;
    }

    .payment-methods .payment-method {
        width: 26%;
        margin-right: 10px;
    }

    .payment-methods .payment-method .qr {
        border: solid 1px #ccc;
        margin-bottom: 10px;
    }

    .payment-methods .payment-method .qr img {
        width: 100%;
        height: auto;
    }

    .payment-methods .payment-method .wompi {
        border: solid 1px #ccc;
    }

    .payment-methods .payment-method .wompi svg {
        height: 50px;
    }

    .payment-methods .payment-info {
        width: 70%;
    }

    .payment-methods .payment-info p {
        margin: 3px 0;
        font-size: 14px;
    }

    .payment-methods .payment-info p:first-child {
        font-size: 14px;
        color: #0a0a0a;
        font-weight: bold;
    }

    .buttons {
        text-align: center;
        margin-top: 20px;
    }

    .buttons a {
        text-decoration: none;
        background: #d80027;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        margin: 5px;
        display: inline-block;
    }

    .footer {
        text-align: center;
        font-size: 14px;
        margin-top: 20px;
        color: #666;
    }
</style>
<div class="container">
    <div class="hero" style="background-image: url({{$img_header}})"></div>
    <!-- Renderizar contenido aquí -->
    <div class="content-info">
        <div class="info">
            <div class="month">
                <img src="{{asset('/img/calendar-range.svg')}}" alt="calendar-range"/>
                <p class="info-title"><strong>Mes</strong></p>
                <p>{{ $invoice->issue__month_formatted}}</p>
            </div>
            <div class="total-amount">
                <img src="{{asset('/img/hand-coins.svg')}}" alt="calendar-range"/>
                <p class="info-title"><strong>Valor a pagar</strong></p>
                <p>{{$invoice->total_formatted}}</p>
            </div>
            <div>
                <img src="{{asset('/img/calendar-clock.svg')}}" alt="calendar-range"/>
                <p class="info-title"><strong>Límite de pago</strong></p>
                <p>{{$invoice->due_date_formatted}}</p>
            </div>
        </div>
        <p class="title"><strong>Canales y medios disponibles para pagar tu factura</strong></p>
        <div class="payment-methods">
            <div class="payment-method">
                <div class="qr">
                    <img src="{{$invoice->qr_image}}" alt="QR"/>
                </div>
                <div class="wompi">
                    <img src="{{asset('/img/wompi.svg')}}" alt="wompi"/>
                </div>
            </div>
            <div class="payment-info">
                <p><strong>PAGOS EN NUESTRAS OFICINAS:</strong></p>
                <p>Guachené Calle 8#6-52 B/r Las Palmas</p>
                <p>Padilla: calle 9 #2-15 esquina B/r Carlos Lleras</p>
                <p>Ciudad del Sur: Calle 86a #22-03 esquina</p>
                <p><strong>Horarios:</strong> Lunes a Viernes: 08:00 AM - 12:00 PM y 2:00 PM - 5:00 PM</p>
                <p>Sábado: 08:00 AM - 12:00 PM</p>
            </div>
        </div>
        <div class="buttons">
            <a class="bg-gradient" href="{{$invoice->url_preview}}" target="_blank">VER TU FACTURA</a>
            <a class="bg-gradient" href="{{$invoice->url_pay}}" target="_blank">PAGAR TU FACTURA</a>
        </div>
        <div class="footer">
            ¡Tu pago quedará registrado de inmediato!
        </div>
    </div>
</div>
</body>
</html>
