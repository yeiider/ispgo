<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>{{ __('Factura') }} #{{ $invoice->number }}</title>

    <!-- Reset mínimo + tipografías -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: "Arial", sans-serif;
            background: #f3f4f6;
            color: #1f2937;
            line-height: 1.4
        }

        h1, h2, h3, h4 {
            font-weight: 600
        }

        /* Contenedor principal */
        .invoice-wrapper {
            max-width: 850px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08)
        }

        /* Cabecera */
        .header {
            background: #111827;
            color: #fff;
            padding: 2rem;
        }

        .header h1 {
            font-size: 1.75rem;
            margin-bottom: .25rem
        }

        .header small {
            font-size: .875rem;
            opacity: .75
        }

        /* Sección datos ― cliente & factura ― */
        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            padding: 2rem;
            font-size: .875rem;
        }

        .meta > div {
            flex: 1 1 250px
        }

        .meta h3 {
            font-size: .75rem;
            text-transform: uppercase;
            margin-bottom: .5rem;
            color: #4b5563
        }

        .meta p {
            margin-bottom: .25rem
        }

        /* Tabla de ítems */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem;
            margin: 0;
        }

        thead {
            background: #f3f4f6;
            text-transform: uppercase;
            font-size: .75rem;
            letter-spacing: .5px;
        }

        th, td {
            padding: .75rem 1rem;
            border: 1px solid #e5e7eb
        }

        td.number {
            text-align: right
        }

        tbody tr:nth-child(odd) {
            background: #fafafa
        }

        /* Resumen económico */
        .totals {
            width: 40%;
            margin-left: auto;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .totals td {
            border-left: none
        }

        .totals tr:last-child td {
            font-size: 1rem;
            font-weight: 600;
            background: #f3f4f6;
        }

        /* Pie */
        .footer {
            padding: 1.5rem 2rem;
            font-size: .75rem;
            color: #6b7280;
            background: #fafafa;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="invoice-wrapper">

    {{-- CABECERA --}}
    <header class="header">
        <h1>{{ $companyName }}</h1>
        <small> — {{ $companyPhone }}</small>
    </header>

    {{-- INFORMACIÓN PRINCIPAL --}}
    <section class="meta">
        <!-- Datos del cliente -->
        <div>
            <h3>Cliente</h3>
            <p>{{ $invoice->client_name }}</p>
            <p>{{ $invoice->client_address }}</p>
            <p>{{ $invoice->client_email }}</p>
        </div>

        <!-- Datos de la factura -->
        <div>
            <h3>Factura</h3>
            <p><strong>Nº:</strong> {{ $invoice->number }}</p>
            <p><strong>Fecha emisión:</strong> {{ $invoice->date }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($invoice->status) }}</p>
        </div>
    </section>

    {{-- TABLA DE ÍTEMS --}}
    <section style="padding:0 2rem 2rem">
        <table>
            <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Descripción</th>
                <th style="width:100px">Cant.</th>
                <th style="width:120px">Precio U.</th>
                <th style="width:120px">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $index => $item)
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

    {{-- RESUMEN ECONÓMICO --}}
    <section style="padding:0 2rem 2rem">
        <table class="totals">
            <tbody>
            <tr>
                <td>Subtotal</td>
                <td class="number">{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Impuestos ({{ $invoice->tax_rate }} %)</td>
                <td class="number">{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @if($invoice->discount > 0)
                <tr>
                    <td>Descuento</td>
                    <td class="number">-{{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Total</td>
                <td class="number">{{ number_format($invoice->total, 2) }}</td>
            </tr>
            </tbody>
        </table>
    </section>

    {{-- PIE --}}
    <footer class="footer">
        Gracias por su confianza. Para cualquier duda, contacte con nosotros en
        <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a>
    </footer>

</div>

</body>
</html>
