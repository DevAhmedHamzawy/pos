@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.edit_order')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang('site.clients')</a></li>
                <li class="active">@lang('site.edit_order')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-6">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.categories')</h3>

                        </div><!-- end of box header -->

                        <div class="box-body">

                            @foreach ($categories as $category)
                                <div class="panel-group">

                                    <div class="panel panel-info">

                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse"
                                                    href="#{{ str_replace(' ', '-', $category->name) }}">{{ $category->name }}</a>
                                            </h4>
                                        </div>

                                        <div id="{{ str_replace(' ', '-', $category->name) }}"
                                            class="panel-collapse collapse">

                                            <div class="panel-body">

                                                @if ($category->products->count() > 0)
                                                    <table class="table table-hover">
                                                        <tr>
                                                            <th>@lang('site.name')</th>
                                                            <th>@lang('site.stock')</th>
                                                            <th>@lang('site.price')</th>
                                                            <th>@lang('site.add')</th>
                                                        </tr>

                                                        @foreach ($category->products as $product)
                                                            <tr>
                                                                <td>{{ $product->name }}</td>
                                                                <td>{{ $product->stock }}</td>
                                                                <td>{{ $product->sale_price }}</td>
                                                                <td>
                                                                    <a href="" id="product-{{ $product->id }}"
                                                                        data-name="{{ $product->name }}"
                                                                        data-id="{{ $product->id }}"
                                                                        data-price="{{ $product->sale_price }}"
                                                                        class="btn {{ in_array($product->id, $order->products->pluck('id')->toArray()) ? 'btn-default disabled' : 'btn-success add-product-btn' }} btn-sm">
                                                                        <i class="fa fa-plus"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </table><!-- end of table -->
                                                @else
                                                    <h5>@lang('site.no_records')</h5>
                                                @endif

                                            </div><!-- end of panel body -->

                                        </div><!-- end of panel collapse -->

                                    </div><!-- end of panel primary -->

                                </div><!-- end of panel group -->
                            @endforeach

                        </div><!-- end of box body -->

                    </div><!-- end of box -->

                </div><!-- end of col -->

                <div class="col-md-6">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title">@lang('site.orders')</h3>

                        </div><!-- end of box header -->

                        <div class="box-body">

                            @include('partials._errors')

                            <form
                                action="{{ route('admin.clients.orders.update', ['order' => $order->id, 'client' => $client->id]) }}"
                                method="post">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>@lang('site.product')</th>
                                            <th>@lang('site.quantity')</th>
                                            <th>@lang('site.price')</th>
                                        </tr>
                                    </thead>

                                    <tbody class="order-list">

                                        @foreach ($order->products as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td><input type="number" name="products[{{ $product->id }}][quantity]"
                                                        data-price="{{ number_format($product->sale_price, 2) }}"
                                                        class="form-control input-sm product-quantity" min="1"
                                                        value="{{ $product->pivot->quantity }}"></td>
                                                <td class="product-price">
                                                    {{ number_format($product->sale_price * $product->pivot->quantity, 2) }}
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm remove-product-btn"
                                                        data-id="{{ $product->id }}"><span
                                                            class="fa fa-trash"></span></button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table><!-- end of table -->

                                <h4>@lang('site.total') : <span
                                        class="total-price">{{ number_format($order->total_price, 2) }}</span></h4>

                                <div class="card">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="custom-tabs-one-tab">
                                            <li class="nav-item {{ !$order->installment_value ? 'active' : '' }}">
                                                <a class="nav-link {{ !$order->installment_value ? 'active' : '' }}"
                                                    data-toggle="pill" href="#tab1">
                                                    @lang('site.cash')
                                                </a>
                                            </li>

                                            <li class="nav-item {{ $order->installment_value ? 'active' : '' }}">
                                                <a class="nav-link {{ $order->installment_value ? 'active' : '' }}"
                                                    data-toggle="pill" href="#tab2">
                                                    @lang('site.installment')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="discount">@lang('site.discount')</label>
                                        <input type="number" name="discount" id="discount" class="form-control"
                                            value="{{ $order->discount }}" min="0" placeholder="@lang('site.discount')">
                                    </div>

                                    <div class="card-body">
                                        <div class="tab-content">

                                            <div class="tab-pane  {{ !$order->installment_value ? 'show active' : '' }}"
                                                id="tab1">

                                            </div>


                                            <div class="tab-pane  {{ $order->installment_value ? 'show active' : '' }}"
                                                id="tab2">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="start">@lang('site.start')</label>
                                                        <input type="number" name="start" id="start"
                                                            class="form-control" placeholder="@lang('site.start')"
                                                            value="{{ $order->start }}">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="benefit">@lang('site.benefit')</label>
                                                        <input type="number" name="benefit" id="benefit"
                                                            class="form-control" placeholder="@lang('site.benefit')"
                                                            value="{{ $order->benefit }}">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="installment_number">@lang('site.installment_number')</label>
                                                        <input type="number" name="installment_number"
                                                            id="installment_number" class="form-control"
                                                            placeholder="@lang('site.installment_number')"
                                                            value="{{ $order->installment_number }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="total_after_benefit">@lang('site.total_after_benefits')</label>
                                                        <input type="number" name="total_after_benefit"
                                                            id="total_after_benefit" class="form-control"
                                                            placeholder="@lang('site.total_after_benefits')" readonly
                                                            value="{{ $order->total_after_benefit }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="installment_value">@lang('site.installment_value')</label>
                                                        <input type="number" name="installment_value"
                                                            id="installment_value" class="form-control"
                                                            placeholder="@lang('site.installment_value')" readonly
                                                            value="{{ $order->installment_value }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4>@lang('site.total') : <span
                                        class="total-price">{{ number_format($order->total_price, 2) }}</span></h4>
                                <input type="hidden" name="total_price" value="{{ $order->total_price }}"
                                    class="total-price-value">


                                <button class="btn btn-primary btn-block" id="form-btn"><i class="fa fa-edit"></i>
                                    @lang('site.edit_order')</button>

                            </form><!-- end of form -->

                        </div><!-- end of box body -->

                    </div><!-- end of box -->

                    @if ($client->orders->count() > 0)
                        <div class="box box-primary">

                            <div class="box-header">

                                <h3 class="box-title" style="margin-bottom: 10px">@lang('site.previous_orders')
                                    <small>{{ $orders->total() }}</small>
                                </h3>

                            </div><!-- end of box header -->

                            <div class="box-body">

                                @foreach ($orders as $order)
                                    <div class="panel-group">

                                        <div class="panel panel-success">

                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse"
                                                        href="#{{ $order->created_at->format('d-m-Y-s') }}">{{ $order->created_at->toFormattedDateString() }}</a>
                                                </h4>
                                            </div>

                                            <div id="{{ $order->created_at->format('d-m-Y-s') }}"
                                                class="panel-collapse collapse">

                                                <div class="panel-body">

                                                    <ul class="list-group">
                                                        @foreach ($order->products as $product)
                                                            <li class="list-group-item">{{ $product->name }}</li>
                                                        @endforeach
                                                    </ul>

                                                </div><!-- end of panel body -->

                                            </div><!-- end of panel collapse -->

                                        </div><!-- end of panel primary -->

                                    </div><!-- end of panel group -->
                                @endforeach

                                {{ $orders->links() }}

                            </div><!-- end of box body -->

                        </div><!-- end of box -->
                    @endif

                </div><!-- end of col -->

            </div><!-- end of row -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
