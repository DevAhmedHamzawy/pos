<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>@lang('site.sales_receipt')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 80mm;
            margin: auto;
            padding: 5mm;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 13px;
            color: #000;
            background: #fff;
        }

        .store {
            text-align: center;
            margin-bottom: 10px;
        }

        .store h2 {
            font-size: 22px;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .store p {
            font-size: 12px;
            margin: 2px 0;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        thead th {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            font-size: 12px;
        }

        tbody td {
            padding: 6px 0;
            font-size: 12px;
            vertical-align: top;
        }

        th:first-child,
        td:first-child {
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        th:nth-child(2),
        td:nth-child(2),
        th:nth-child(3),
        td:nth-child(3) {
            text-align: center;
        }

        .total {
            margin-top: 10px;
        }

        .total .row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 13px;
        }

        .grand-total {
            font-size: 15px;
            font-weight: bold;
            border-top: 1px dashed #000;
            padding-top: 8px;
            margin-top: 8px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 12px;
        }

        .footer h3 {
            margin-top: 5px;
            letter-spacing: 2px;
        }

        @media print {

            @page {
                margin: 0;
                size: 80mm auto;
            }

            body {
                margin: 0;
            }
        }
    </style>

</head>

<body onload="window.print();setTimeout(window.close,500);">

    <div class="store">

        <h2>DEEPSTORE</h2>

        <p>@lang('site.sales_receipt')</p>

        <p>{{ now()->format('d/m/Y H:i') }}</p>

        <p>@lang('site.invoice') #{{ $order->id }}</p>

        @if ($order->client)
            <p>@lang('site.client') : {{ $order->client->name }}</p>
        @endif

    </div>

    <hr>

    <table>

        <thead>

            <tr>
                <th>@lang('site.name')</th>
                <th>@lang('site.quantity')</th>
                <th>@lang('site.total')</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($order->products as $product)
                <tr>

                    <td>{{ $product->name }}</td>

                    <td>{{ $product->pivot->quantity }}</td>

                    <td>
                        {{ number_format($product->pivot->quantity * $product->sale_price, 2) }}
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

    <hr>

    <div class="total">

        @if ($order->discount)
            <div class="row">
                <span>@lang('site.discount')</span>
                <span>{{ $order->discount }} %</span>
            </div>
        @endif

        @if ($order->installment_number > 0)
            <div class="row">
                <span>@lang('site.start')</span>
                <span>{{ number_format($order->start, 2) }}</span>
            </div>

            <div class="row">
                <span>@lang('site.installment_number')</span>
                <span>{{ $order->installment_number }}</span>
            </div>

            <div class="row">
                <span>@lang('site.installment_value')</span>
                <span>{{ number_format($order->installment_value, 2) }}</span>
            </div>

            <div class="row">
                <span>@lang('site.total_after_benefit')</span>
                <span>{{ number_format($order->total_after_benefit, 2) }}</span>
            </div>

            <div class="row">
                <span>@lang('site.installment_status')</span>
                <span>@lang('site.' . $order->installment_status)</span>
            </div>
        @endif

        <div class="row grand-total">

            <span>@lang('site.total')</span>

            <span>{{ number_format($order->total_price, 2) }}</span>

        </div>

    </div>

    <hr>

    <div class="footer">

        <p>@lang('site.thank_you_for_shopping')</p>

        <h3>DEEPSTORE</h3>

    </div>

</body>

</html>
```
