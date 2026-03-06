<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>{{ __('Factura') }} #{{ $data['invoice']->number }}</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", "Arial", sans-serif;
            background: #f9fafb;
            color: #1f2937;
            line-height: 1.5;
        }

        .invoice-wrapper {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }

        .header {
            background: #1f2937;
            color: #ffffff;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .company-info h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .company-info small {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .logo-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border-radius: 50%;
            border: 2px solid #ffffff33;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff10;
        }

        .logo-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .print-button-container {
            text-align: right;
            padding: 1rem 2rem 0;
        }

        .print-button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .pay-button {
            background-color: #8e0e0e;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .print-button:hover {
            background-color: #1d4ed8;
        }

        @media print {
            .print-button-container {
                display: none;
            }
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            padding: 2rem;
            font-size: 0.9rem;
            background-color: #f3f4f6;
        }

        .meta > div {
            flex: 1 1 300px;
        }

        .meta h3 {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .meta p {
            margin: 0.25rem 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        thead {
            background: #e5e7eb;
            color: #374151;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        th, td {
            padding: 1rem;
            border: 1px solid #e5e7eb;
        }

        td.number {
            text-align: right;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .totals {
            width: 40%;
            margin: 2rem 2rem 0 auto;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .totals td {
            border-left: none;
            padding: 0.75rem 1rem;
        }

        .totals tr:last-child td {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 1rem;
        }

        .footer {
            padding: 1.5rem 2rem;
            font-size: 0.8rem;
            color: #6b7280;
            background: #f9fafb;
            text-align: center;
        }

        .footer a {
            color: #2563eb;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="invoice-wrapper">

    <!-- CABECERA -->
    <header class="header">
        <div class="company-info">
            <h1>{{ $data['companyName'] }}</h1>
            <small>{{ $data['companyEmail'] }} — {{ $data['companyPhone'] }}</small>
            @if($data['companyUrl'])
                <small style="display: block; margin-top: 0.25rem;">{{ $data['companyUrl'] }}</small>
            @endif
        </div>
        <div class="logo-container">
            <img src="{{ $data['img'] }}" alt="Logo empresa">
        </div>
    </header>

    <!-- BOTÓN DE IMPRESIÓN -->
    <div class="print-button-container">
        <button class="print-button" onclick="window.print()">🖨 Imprimir factura</button>
        <button class="pay-button" onclick="window.location.href='https://raicesc.net/pagos'">🪙 Paga Aquí</button>
    </div>

    <!-- INFORMACIÓN DEL CLIENTE Y FACTURA -->
    <section class="meta">
        <div>
            <h3>Cliente</h3>
            <p><strong>Nombre:</strong> {{ $data['invoice']->full_name }}</p>
            <p><strong>Documento de identificación:</strong> {{ $data['invoice']->customer->identity_document ?? 'N/A' }}</p>
            <p><strong>Teléfono:</strong> {{ $data['invoice']->customer->phone_number }}</p>
            <p><strong>Correo:</strong> {{ $data['invoice']->email_address }}</p>
            @php
                $address = null;
                if ($data['invoice']->service && $data['invoice']->service->address) {
                    $address = $data['invoice']->service->address;
                } elseif ($data['invoice']->customer) {
                    $address = $data['invoice']->customer->addresses()->where('address_type', 'billing')->first();
                    if (!$address) {
                        $address = $data['invoice']->customer->addresses()->first();
                    }
                }
            @endphp
            @if($address)
                <p><strong>Dirección:</strong> {{ $address->address }}, {{ $address->city }}, {{ $address->state_province }}</p>
            @endif
        </div>
        <div>
            <h3>Factura</h3>
            <p><strong>Número:</strong> {{ $data['invoice']->increment_id }}</p>
            <p><strong>Fecha de emisión:</strong> {{ \Carbon\Carbon::parse($data['invoice']->issue_date)->format('d/m/Y') }}</p>
            <p><strong>Estado:</strong> {{ ucfirst(__($data['invoice']->status)) }}</p>
            @if($data['billing_period'])
                <p><strong>Periodo facturado:</strong> {{ \Carbon\Carbon::createFromFormat('Y-m', $data['billing_period'])->format('m/Y') }}</p>
            @endif
            <p><strong>Fecha oportuna de pago:</strong> {{ \Carbon\Carbon::parse($data['invoice']->due_date)->format('d/m/Y') }}</p>
            @if($data['cut_off_date'])
                <p><strong>Fecha de corte:</strong> {{ \Carbon\Carbon::parse($data['cut_off_date'])->format('d/m/Y') }}</p>
            @endif
        </div>
    </section>

    <!-- DETALLE DE ITEMS -->
    <section style="padding: 0 2rem 2rem">
        <table>
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                <th>Descripción</th>
                <th style="width: 80px">Cant.</th>
                <th style="width: 120px">Precio U.</th>
                <th style="width: 120px">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data['items'] as $index => $item)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="number">{{ $item->quantity }}</td>
                    <td class="number">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="number">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

    <!-- RESUMEN -->
    <section>
        <table class="totals">
            <tbody>
            <tr>
                <td>Subtotal</td>
                <td class="number">{{ number_format($data['invoice']->amount_before_discounts, 2) }}</td>
            </tr>
            <tr>
                <td>Impuestos ({{ $data['tax_rate'] }} %)</td>
                <td class="number">{{ number_format($data['invoice']->tax_total, 2) }}</td>
            </tr>
            @if($data['invoice']->discount > 0)
                <tr>
                    <td>Descuento</td>
                    <td class="number">-{{ number_format($data['invoice']->discount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Total</td>
                <td class="number">{{ $data['invoice']->total }}</td>
            </tr>
            </tbody>
        </table>
    </section>
    <!-- NOTAS DE PAGO -->
    <section class="notas-pago" style="padding: 1.5rem 2rem; background: #fefce8; border-top: 1px solid #fcd34d; margin-top: 2rem;">
        <h3 style="color: #92400e; font-size: 1rem; margin-bottom: 0.5rem;">PAGOS EN NUESTRAS OFICINAS:</h3>
        <p><strong>Guachené:</strong> Calle 8#6-52 B/r Las Palmas</p>
        <p><strong>Padilla:</strong> Calle 9 #2-15 esquina B/r Carlos Lleras</p>
        <p><strong>Ciudad del Sur:</strong> Calle 86a #22-03 esquina</p>
        <p><strong>Horarios:</strong> Lunes a Viernes: 08:00 AM - 12:00 PM y 2:00 PM - 5:00 PM</p>
        <p><strong>Sábado:</strong> 08:00 AM - 12:00 PM</p>
    </section>


    <!-- PIE -->
    <footer class="footer">
        Gracias por su confianza. Para cualquier duda, puede contactarnos en
        <a href="mailto:{{ $data['companyEmail'] }}">{{ $data['companyEmail'] }}</a>
    </footer>

</div>

</body>
</html>
