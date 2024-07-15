<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <h2>Invoice</h2>
                        </td>
                        <td>
                            Invoice #: {{ $invoice->id }}<br>
                            Created: {{ $invoice->issue_date }}<br>
                            Due: {{ $invoice->due_date }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            {{ $invoice->customer->full_name }}<br>
                            {{ $invoice->customer->email_address }}<br>
                        </td>
                        <td>
                            Your Company<br>
                            yourcompany@example.com
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="heading">
            <td>Item</td>
            <td>Price</td>
        </tr>
        <!-- Add invoice items here -->
        <tr class="item">
            <td>{{ $invoice->service->plan->name }}</td>
            <td>${{ $invoice->subtotal }}</td>
        </tr>
        <tr class="item">
            <td>Tax</td>
            <td>${{ $invoice->tax }}</td>
        </tr>
        <tr class="total">
            <td></td>
            <td>Total: ${{ $invoice->total }}</td>
        </tr>
    </table>
</div>
</body>
</html>
