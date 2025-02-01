<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('Invoice')}}</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        /* Estilos generales */
        .container {
            position: relative;
            display: flex;
            flex-direction: column;
            background-color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            pointer-events: auto;

        }

        .dark .container {
            background-color: #262626; /* neutral-800 */
        }

        /* Estilos para la sección superior */
        .top-section {
            position: relative;
            overflow: hidden;
            min-height: 8rem; /* min-h-32 */
            background-color: #111827; /* gray-900 */
            text-align: center;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .dark .top-section {
            background-color: #0a0a0a; /* neutral-950 */
        }

        .top-section figure {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            margin-bottom: -1px; /* -mb-px */
        }

        .top-section svg {
            fill: white;
        }

        .dark .top-section svg {
            fill: #262626; /* neutral-800 */
        }

        /* Estilos para el círculo con ícono */
        .icon-circle {
            position: relative;
            z-index: 10;
            margin-top: -3rem; /* -mt-12 */
            margin-left: auto;
            margin-right: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 62px; /* size-[62px] */
            height: 62px; /* size-[62px] */
            border-radius: 50%;
            border: 1px solid #e5e7eb; /* gray-200 */
            background-color: white;
            color: #374151; /* gray-700 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            text-align: center;
            align-content: center;
        }

        .dark .icon-circle {
            background-color: #262626; /* neutral-800 */
            border-color: #404040; /* neutral-700 */
            color: #a3a3a3; /* neutral-400 */
        }

        .icon-circle img {
            flex-shrink: 0;
            width: 1.5rem; /* size-6 */
            height: 1.5rem; /* size-6 */
            margin-top: 25%;
            margin-bottom: auto;
        }

        /* Estilos para el contenido */
        .content {
            padding: 1rem; /* p-4 */
        }

        @media (min-width: 640px) {
            .content {
                padding: 1.75rem; /* sm:p-7 */
            }
        }

        .content h3 {
            font-size: 1.125rem; /* text-lg */
            font-weight: 600; /* font-semibold */
            color: #1f2937; /* gray-800 */
            margin-bottom: 0.5rem;
        }

        .dark .content h3 {
            color: #e5e5e5; /* neutral-200 */
        }

        .content p {
            font-size: 0.875rem; /* text-sm */
            color: #6b7280; /* gray-500 */
        }

        .dark .content p {
            color: #a3a3a3; /* neutral-500 */
        }

        /* Estilos para la sección de detalles */
        .details-grid {
            display: flex;
            justify-content: space-between;
            gap: 1.25rem; /* gap-5 */
            margin-top: 1.25rem; /* mt-5 */
        }

        @media (min-width: 640px) {
            .details-grid {
                grid-template-columns: repeat(3, 1fr); /* sm:grid-cols-3 */
                margin-top: 2.5rem; /* sm:mt-10 */
            }
        }

        .details-grid span {
            display: block;
            font-size: 0.75rem; /* text-xs */
            text-transform: uppercase;
        }

        .details-grid .value {
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            color: #1f2937; /* gray-800 */
        }

        .dark .details-grid .value {
            color: #e5e5e5; /* neutral-200 */
        }

        /* Estilos para el estado de la factura */
        .status {
            display: inline-block;
            align-items: center;
            padding: 0.5rem 1rem; /* px-4 py-2 */
            border-radius: 9999px; /* rounded-full */
            max-width: fit-content;
            width: fit-content;
        }

        .status.paid {
            background-color: #f0fdf4; /* bg-green-50 */
            color: #22c55e; /* text-green-500 */

            span {
                color: #22c55e;
            }
        }

        .status.unpaid {
            background-color: #fefce8; /* bg-yellow-50 */
            color: #eab308; /* text-yellow-500 */

            span {
                color: #eab308;
            }
        }

        .status.canceled {
            background-color: #fef2f2; /* bg-red-50 */
            color: #ef4444; /* text-red-500 */

            span {
                color: #ef4444;
            }
        }

        /* Estilos para la lista de resumen */
        .summary-list {
            margin-top: 1.25rem; /* mt-5 */
        }

        @media (min-width: 640px) {
            .summary-list {
                margin-top: 2.5rem; /* sm:mt-10 */
            }
        }

        .summary-list h4 {
            font-size: 0.75rem; /* text-xs */
            font-weight: 600; /* font-semibold */
            text-transform: uppercase;
            color: #1f2937; /* gray-800 */
        }

        .dark .summary-list h4 {
            color: #e5e5e5; /* neutral-200 */
        }

        .summary-list ul {
            display: flex;
            flex-direction: column;
            margin-top: 0.75rem; /* mt-3 */
        }

        .summary-list li {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem; /* gap-x-2 */
            padding: 0.75rem 1rem; /* py-3 px-4 */
            font-size: 0.875rem; /* text-sm */
            border: 1px solid #e5e7eb; /* border */
            color: #1f2937; /* gray-800 */
            margin-top: -1px; /* -mt-px */
        }

        .summary-list li:first-child {
            border-top-left-radius: 0.5rem; /* first:rounded-t-lg */
            border-top-right-radius: 0.5rem; /* first:rounded-t-lg */
            margin-top: 0; /* first:mt-0 */
        }

        .summary-list li:last-child {
            border-bottom-left-radius: 0.5rem; /* last:rounded-b-lg */
            border-bottom-right-radius: 0.5rem; /* last:rounded-b-lg */
        }

        .dark .summary-list li {
            border-color: #404040; /* dark:border-neutral-700 */
            color: #e5e5e5; /* dark:text-neutral-200 */
        }

        .summary-list li.bg-gray-50 {
            background-color: #f9fafb; /* bg-gray-50 */
        }

        .dark .summary-list li.bg-gray-50 {
            background-color: #262626; /* dark:bg-neutral-800 */
        }

        .summary-list li .total {
            font-size: 1.125rem; /* text-[18px] */
            font-weight: 500; /* font-medium */
            color: #1f2937; /* gray-900 */
        }

        .dark .summary-list li .total {
            color: #e5e5e5; /* dark:text-neutral-200 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table.summary tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table.summary tbody th {
            text-align: left;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-collapse: collapse;
            border-right: none;
        }

        table.summary tbody td {
            text-align: right;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-collapse: collapse;
            border-left: none;
        }


        /* Estilos para el pie de página */
        .footer {
            margin-top: 1.25rem; /* mt-5 */
        }

        @media (min-width: 640px) {
            .footer {
                margin-top: 2.5rem; /* sm:mt-10 */
            }
        }

        .footer p {
            font-size: 0.875rem; /* text-sm */
            color: #6b7280; /* gray-500 */
        }

        .dark .footer p {
            color: #a3a3a3; /* neutral-500 */
        }

        .footer a {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem; /* gap-x-1.5 */
            color: #2563eb; /* blue-600 */
            text-decoration: underline;
            text-decoration-thickness: 2px; /* decoration-2 */
            font-weight: 500; /* font-medium */
        }

        .dark .footer a {
            color: #3b82f6; /* dark:text-blue-500 */
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .footer a:focus {
            outline: none;
            text-decoration: underline;
        }
    </style>

</head>
<body>
<div class="container">
    <div class="top-section">
        <figure>
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                 viewBox="0 0 1920 100.1">
                <path fill="currentColor" class="fill-white dark:fill-neutral-800"
                      d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
            </svg>
        </figure>
    </div>

    <div class="icon-circle">
        <img src="{{$img}}" alt="Logo">
    </div>

    <div class="content">
        <div class="text-center">
            <h3 id="hs-ai-modal-label" class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                {{__('Invoice')}}
            </h3>
            <p class="text-sm text-gray-500 dark:text-neutral-500">
                {{__('Reference')}} #{{$invoice->increment_id}}
            </p>
        </div>

        <table>
            <tbody>
            <tr>
                <td><span
                        class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Total')}}:</span>
                </td>
                <td><span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Status paid')}}:</span>
                </td>
                <td><span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Customer')}}:</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="value">{{$invoice->total}}</span>
                </td>
                <td>
                    <div class="value" style="text-transform: uppercase; max-width: fit-content;">
                        @if($invoice->status === 'paid')
                            <div class="status paid">
                                <span>{{__('Paid')}}</span>
                            </div>
                        @elseif($invoice->status === 'unpaid')
                            <div class="status unpaid">
                                <span>{{__('Unpaid')}}</span>
                            </div>
                        @elseif($invoice->status === 'canceled')
                            <div class="status canceled">
                                <span>{{__('Canceled')}}</span>
                            </div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="flex items-center gap-x-2">
                        <span class="value">{{$invoice->full_name}}</span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>


        <div class="details-grid" style="display: none">
            <div>
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Total</span>
                <span class="value">{{$invoice->total}}</span>
            </div>

            <div>
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Status paid')}}:</span>
                <div class="value">
                    @if($invoice->status === 'paid')
                        <div class="status paid">
                            <span>{{__('Paid')}}</span>
                        </div>
                    @elseif($invoice->status === 'unpaid')
                        <div class="status unpaid">
                            <span>{{__('Unpaid')}}</span>
                        </div>
                    @elseif($invoice->status === 'canceled')
                        <div class="status canceled">
                            <span>{{__('Canceled')}}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Customer')}}:</span>
                <div class="flex items-center gap-x-2">
                    <span class="value">{{$invoice->full_name}}</span>
                </div>
            </div>
        </div>

        <div class="summary-list">
            <h4>{{__('Summary')}}</h4>
            <table class="summary">
                <tbody>
                <tr>
                    <th><span>{{__('Tax')}}</span></th>
                    <td><span>{{$invoice->tax}}</span></td>
                </tr>
                <tr>
                    <th><span>{{__('Amount')}}</span></th>
                    <td><span>{{$invoice->amount}}</span></td>
                </tr>
                <tr>
                    <th><span>{{__('Discount')}}</span></th>
                    <td><span>{{$invoice->discount}}</span></td>
                </tr>
                <tr>
                    <th><span>SubTotal</span></th>
                    <td><span>{{$invoice->subtotal}}</span></td>
                </tr>
                <tr>
                    <th><span class="total">Total</span></th>
                    <td><span class="total">{{$invoice->total}}</span></td>
                </tr>

                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>
                @if($companyEmail)
                    {{__('If you have any questions, please contact at ')}}
                    <a href="mailto:{{$companyEmail}}">{{$companyEmail}}</a>
                @endif

                @if($companyPhone)
                    {{__(' or call at ')}}
                    <a href="tel:{{$companyPhone}}">{{$companyPhone}}</a>
                @endif
            </p>
        </div>
    </div>
</div>
</body>
</html>
