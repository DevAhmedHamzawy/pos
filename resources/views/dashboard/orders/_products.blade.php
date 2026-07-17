<div id="print-area">
    <table class="table table-hover table-bordered">

        <thead>
            <tr>
                <th>@lang('site.name')</th>
                <th>@lang('site.quantity')</th>
                <th>@lang('site.price')</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ number_format($product->pivot->quantity * $product->sale_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($order->discount)
        <h3>@lang('site.discount') <span>{{ $order->discount }}%</span></h3>
    @endif

    @if ($order->installment_number > 0)
        <h3>@lang('site.start') <span>{{ number_format($order->start, 2) }}</span></h3>
        <h3>@lang('site.installment_number') <span>{{ $order->installment_number }}</span></h3>
        <h3>@lang('site.total_after_benefit') <span>{{ number_format($order->total_after_benefit, 2) }}</span></h3>
        <h3>@lang('site.installment_value') <span>{{ number_format($order->installment_value, 2) }}</span></h3>
        <h3>@lang('site.installment_status') <span> @lang('site.' . $order->installment_status)</span></h3>
    @endif



    <h3>@lang('site.total') <span>{{ number_format($order->total_price, 2) }}</span></h3>

</div>

<a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="btn btn-primary btn-block">

    <i class="fa fa-print"></i>
    @lang('site.print')

</a>
