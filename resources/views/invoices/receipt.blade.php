<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body>
<div class="receipt border-2 border-gray-400 rounded-md max-w-xs mx-auto p-4 text-center">
    <!-- Shop Info -->
    <h1 class="text-lg font-bold mb-2">{{ $config['name'] }}</h1>
    <p class="text-sm mb-2">{{ $config['address']  }}</p>
    <p class="text-sm mb-2">{{ $config['site']  }}</p>

    <!-- Divider -->
    <hr class="my-2 border-gray-300"/>

    <!-- Product Info -->
    <div class="text-left mb-4">
        <p class="font-bold">{{ ucwords($invoice->full_name)  }}</p>
        <p>{{ $invoice->service->plan->name }}</p> <!-- Se puede ajustar dependiendo de cómo se maneje -->
        <p>${{ number_format((int)$invoice->amount,0,',','.') }}</p>
    </div>

    <!-- Divider -->
    <hr class="my-2 border-gray-300"/>

    <!-- Subtotal, TAX, Discount -->
    <div class="text-left mb-4">
        <p>Subtotal: <span class="float-right">${{ number_format((int)$invoice->subtotal,0,',','.') }}</span></p>
        <p>TAX: <span class="float-right">${{ number_format((int)$invoice->tax,0,',','.') }}</span></p>
        <p>Discount: <span class="float-right">-${{ number_format((int)$invoice->discount,0,',','.') }}</span></p>
    </div>

    <!-- Total -->
    <div class="font-bold text-lg mb-4">
        <p>Total: <span class="float-right">{{ number_format((int)$invoice->total,'0',',','.') }}</span></p>
    </div>

    <!-- Divider -->
    <hr class="my-2 border-gray-300"/>

    <!-- Date and Time -->
    <div class="text-left text-sm mb-4">
        <p>{{ $invoice->created_at }}</p>
        <p>{{ $invoice->updated_at->format('H:i:s') }}</p>
    </div>

    <!-- Barcode Placeholder -->
    <div class="mb-4">
        <img src="{{ $qrCode }}" alt="QR Code" class="mx-auto"/>
    </div>

    <!-- Footer Message -->
    <p class="text-sm mt-4 font-semibold">Thank you for your purchase!</p>
</div>
</body>
</html>

