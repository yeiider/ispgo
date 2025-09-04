<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de pago #{{ $invoice->increment_id }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #111; }
        .ticket {
            width: 58mm; /* 58-65mm printers */
            max-width: 65mm;
            background: #fff;
            margin: 10px auto;
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .muted { color: #6b7280; font-size: 12px; }
        .divider { border-top: 1px dashed #999; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; font-size: 12px; margin: 2px 0; }
        h1 { font-size: 16px; margin: 0; }
        h2 { font-size: 13px; margin: 4px 0 0; }
        .total { font-size: 14px; margin-top: 6px; }
        img.qr { display:block; margin: 6px auto; width: 120px; height: 120px; }
        .print-btn { display: block; width: 100%; margin: 10px auto; padding: 8px; background: #2563eb; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        @media print {
            @page { size: auto; margin: 2mm; }
            body { background: #fff; }
            .print-btn { display: none; }
            .ticket { box-shadow: none; border: 0; margin: 0 auto; }
        }
    </style>
</head>
<body>
<button class="print-btn" onclick="window.print()">Imprimir</button>
<div class="ticket">
    <div class="center">
        <h1>{{ $config['name'] }}</h1>
        <div class="muted">{{ $config['address'] }}</div>
        <div class="muted">{{ $config['site'] }}</div>
    </div>

    <div class="divider"></div>

    <div>
        <div class="row"><span class="bold">Recibo:</span> <span>#{{ $invoice->increment_id }}</span></div>
        <div class="row"><span class="bold">Cliente:</span> <span>{{ ucwords($invoice->full_name) }}</span></div>
        @if(optional($invoice->service)->plan)
            <div class="row"><span class="bold">Plan:</span> <span>{{ $invoice->service->plan->name }}</span></div>
        @endif
        @if($invoice->payment_method)
            <div class="row"><span class="bold">Método:</span> <span>{{ $invoice->payment_method }}</span></div>
        @endif
        <div class="row"><span class="bold">Fecha:</span> <span>{{ optional($invoice->updated_at)->format('Y-m-d H:i') }}</span></div>
    </div>

    <div class="divider"></div>

    <div>
        <div class="row"><span>Subtotal</span> <span>${{ number_format((float)$invoice->subtotal, 0, ',', '.') }}</span></div>
        <div class="row"><span>Impuesto</span> <span>${{ number_format((float)$invoice->tax, 0, ',', '.') }}</span></div>
        @if((float)$invoice->discount > 0)
            <div class="row"><span>Descuento</span> <span>-${{ number_format((float)$invoice->discount, 0, ',', '.') }}</span></div>
        @endif
        <div class="row total bold"><span>Total Pagado</span> <span>${{ number_format((float)$invoice->total, 0, ',', '.') }}</span></div>
    </div>

    <div class="divider"></div>

    <img class="qr" src="{{ $qrCode }}" alt="QR">
    <div class="center muted">¡Gracias por su pago!</div>
</div>
</body>
</html>

