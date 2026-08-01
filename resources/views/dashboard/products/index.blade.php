@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.products')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.products')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.products')
                        <small>{{ $products->total() }}</small>
                    </h3>

                    <form action="{{ route('admin.products.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')"
                                    value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <select name="category_id" class="form-control">
                                    <option value="">@lang('site.all_categories')</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request()->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>
                                @if (auth()->user()->hasPermission('products_create'))
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i
                                            class="fa fa-plus"></i> @lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i>
                                        @lang('site.add')</a>
                                @endif
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($products->count() > 0)
                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.name')</th>
                                    <th>@lang('site.description')</th>
                                    <th>@lang('site.category')</th>
                                    <th>@lang('site.image')</th>
                                    <th>@lang('site.purchase_price')</th>
                                    <th>@lang('site.sale_price')</th>
                                    <th>@lang('site.profit_percent')</th>
                                    <th>@lang('site.stock')</th>
                                    <th>@lang('site.stock_limit')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm show-description" data-toggle="modal"
                                                data-target="#descriptionModal">
                                                <i class="fa fa-eye"></i> @lang('site.view')
                                            </button>

                                            <div class="description-data hidden">
                                                {!! $product->description !!}
                                            </div>
                                        </td>
                                        <td>{{ $product->category->name }}</td>
                                        <td><img src="{{ $product->image_path }}" style="width: 100px"
                                                class="img-thumbnail" /></td>
                                        <td>{{ $product->purchase_price }}</td>
                                        <td>{{ $product->sale_price }}</td>
                                        <td>{{ $product->profit_percent }} %</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $product->stock_limit }}</td>
                                        <td>
                                            @if (auth()->user()->hasPermission('products_read'))
                                                <a href="{{ route('admin.products.activityLog', $product->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fa fa-line-chart"
                                                        aria-hidden="true"></i>

                                                    @lang('site.activity_product_log')</a>
                                            @else
                                                <a href="#" class="btn btn-info btn-sm disabled"><i
                                                        class="fa fa-line-chart" aria-hidden="true"></i>
                                                    @lang('site.activity_product_log')</a>
                                            @endif
                                            @if (auth()->user()->hasPermission('products_create') || auth()->user()->hasPermission('products_update'))
                                                <a href="{{ route('admin.products.changeQty', $product->id) }}"
                                                    class="btn btn-primary btn-sm"><i class="fa fa-certificate"></i>
                                                    @lang('site.change_qty')</a>
                                            @else
                                                <a href="#" class="btn btn-primary btn-sm disabled"><i
                                                        class="fa fa-quantity"></i> @lang('site.change_qty')</a>
                                            @endif
                                            @if (auth()->user()->hasPermission('products_update'))
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                                    @lang('site.edit')</a>
                                            @else
                                                <a href="#" class="btn btn-info btn-sm disabled"><i
                                                        class="fa fa-edit"></i> @lang('site.edit')</a>
                                            @endif
                                            @if (auth()->user()->hasPermission('products_delete'))
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                    method="post" style="display: inline-block">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <button type="submit" class="btn btn-danger delete btn-sm"><i
                                                            class="fa fa-trash"></i> @lang('site.delete')</button>
                                                </form><!-- end of form -->
                                            @else
                                                <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i>
                                                    @lang('site.delete')</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table><!-- end of table -->

                        {{ $products->appends(request()->query())->links() }}
                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>

                    <h4 class="modal-title">
                        @lang('site.description')
                    </h4>
                </div>

                <div class="modal-body">
                    <div id="description-content"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        @lang('site.close')
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.show-description', function() {
            let description = $(this).siblings('.description-data').html();
            $('#description-content').html(description);
        });
    </script>
@endpush
