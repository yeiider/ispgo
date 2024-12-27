<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contract</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .contract-content {
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
        }
        .signature-section {
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .signature-pair {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .signature-block {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .signature-block p {
            margin: 5px 0;
        }
        .signature-line {
            margin-top: 20px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 80%;
        }
        .signature-image {
            margin-top: 10px;
            max-width: 100px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div class="contract-content">
    <!-- Contenido principal del contrato -->
    {!! $content !!}
</div>

<div class="signature-section">
    <div class="signature-pair">
        <!-- Bloque de firma del cliente -->
        <div class="signature-block">
            @if($signatureUrl)
                <img src="{{ $signatureUrl }}" alt="Firma del cliente" class="signature-image">
            @endif
            <div class="signature-line"></div>
            <p><strong>EL MANDANTE</strong></p>
            <p><strong>Nombre:</strong> {{ $customer['name'] ?? 'N/A' }}</p>
            <p><strong>C.C.:</strong> {{ $customer['document'] ?? 'N/A' }}</p>
        </div>

        <!-- Bloque de firma del prestador de servicio -->
        <div class="signature-block">
            @if(isset($providerSignatureUrl))
                <img src="{{ $providerSignatureUrl }}" alt="Firma del prestador" class="signature-image">
            @endif
            <div class="signature-line"></div>
            <p><strong>EL MANDATARIO</strong></p>
            <p><strong>Nombre:</strong> Jader Albarracín Acaisedo</p>
            <p><strong>Cargo:</strong> Representante Legal</p>
            <p><strong>C.C.:</strong> 11661777181</p>
        </div>
    </div>
</div>
</body>
</html>
