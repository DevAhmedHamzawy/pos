@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.add_order')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang('site.clients')</a></li>
                <li class="active">@lang('site.add_order')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-6">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.categories')</h3>

                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="search_value"
                                        placeholder="@lang('site.search_product_name_brand_imei')">

                                </div>
                                <div class="col-md-7">
                                    <button type="button" class="btn btn-primary" id="search">@lang('site.search')</button>
                                    <button type="button" class="btn btn-danger" id="cancel">@lang('site.cancel')</button>

                                </div>
                            </div>


                        </div><!-- end of box header -->

                        <div class="box-body">
                            <div id="search_result"></div>
                        </div>

                        <div class="box-body category_panel">

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
                                                                <td>{{ number_format($product->sale_price, 2) }}</td>
                                                                <td>
                                                                    <a href="" id="product-{{ $product->id }}"
                                                                        data-name="{{ $product->name }}"
                                                                        data-id="{{ $product->id }}"
                                                                        data-price="{{ $product->sale_price }}"
                                                                        class="btn btn-success btn-sm add-product-btn">
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

                            <form action="{{ route('admin.clients.orders.store', $client->id) }}" method="post">

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                @include('partials._errors')

                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>@lang('site.product')</th>
                                            <th>@lang('site.quantity')</th>
                                            <th>@lang('site.price')</th>
                                        </tr>
                                    </thead>

                                    <tbody class="order-list">


                                    </tbody>

                                </table><!-- end of table -->

                                <h4>@lang('site.total') : <span class="total-price">0</span></h4>

                                <div class="card">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="custom-tabs-one-tab">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="pill" href="#tab1">
                                                    @lang('site.cash')
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="pill" href="#tab2">
                                                    @lang('site.installment')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="card-body">
                                        <div class="tab-content">

                                            <div class="tab-pane fade show active" id="tab1">

                                            </div>


                                            <div class="tab-pane fade" id="tab2">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="start">@lang('site.start')</label>
                                                        <input type="number" name="start" id="start"
                                                            class="form-control" placeholder="@lang('site.start')">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="benefit">@lang('site.benefit')</label>
                                                        <input type="number" name="benefit" id="benefit"
                                                            class="form-control" placeholder="@lang('site.benefit')">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="installment_number">@lang('site.installment_number')</label>
                                                        <input type="number" name="installment_number"
                                                            id="installment_number" class="form-control"
                                                            placeholder="@lang('site.installment_number')">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="total_after_benefit">@lang('site.total_after_benefits')</label>
                                                        <input type="number" name="total_after_benefit"
                                                            id="total_after_benefit" class="form-control"
                                                            placeholder="@lang('site.total_after_benefits')" readonly>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="installment_value">@lang('site.installment_value')</label>
                                                        <input type="number" name="installment_value"
                                                            id="installment_value" class="form-control"
                                                            placeholder="@lang('site.installment_value')" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary btn-block disabled" id="add-order-form-btn"><i
                                        class="fa fa-plus"></i> @lang('site.add_order')</button>

                            </form>

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


@push('scripts')
    <script>
        $(document).ready(function() {

            function searchProducts() {
                $.ajax({
                    url: '{{ route('admin.product_search') }}',
                    type: 'GET',
                    data: {
                        search: $('#search_value').val()
                    },
                    success: function(data) {
                        $('.category_panel').addClass('hidden');
                        $('#search_result').html(data.html);

                        $(".order-list tr").each(function() {
                            let id = $(this).data("id");

                            $("#product-" + id)
                                .removeClass("btn-success")
                                .addClass("btn-default disabled");
                        });
                    }
                });
            }

            // الضغط على زر البحث
            $('#search').on('click', function() {
                searchProducts();
            });

            // الضغط على Enter داخل حقل البحث
            $('#search_value').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    searchProducts();
                }
            });

            $('#cancel').on('click', function() {
                $('.category_panel').removeClass('hidden');
                $('#search_result').html('');
            });

        });
    </script>
@endpush
