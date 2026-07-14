@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')
                <small>{{ $orders->total() }} @lang('site.orders')</small>
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.orders')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-12">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.orders')</h3>

                            <form action="{{ route('admin.clients.orders.index', $client->id) }}" method="get">

                                <div class="row">

                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="@lang('site.search')" value="{{ request()->search }}">
                                    </div>

                                    <div class="col-md-4">
                                        <select name="installment_status" class="form-control">
                                            <option>@lang('site.all')</option>
                                            <option value="active">@lang('site.active')</option>
                                            <option value="late">@lang('site.late')</option>
                                            <option value="completed">@lang('site.completed')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                            @lang('site.search')</button>
                                    </div>

                                </div><!-- end of row -->

                            </form><!-- end of form -->

                        </div><!-- end of box header -->

                        @if ($orders->count() > 0)
                            <div class="box-body table-responsive">

                                <table class="table table-hover">
                                    <tr>
                                        <th>@lang('site.order_number')</th>

                                        <th>@lang('site.installment_status')</th>

                                        <th>@lang('site.action')</th>
                                    </tr>

                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>

                                            <td>@lang('site.' . $order->installment_status)</td>

                                            <td>

                                                @if (auth()->user()->hasPermission('installments_read'))
                                                    <a href="{{ route('admin.installments.index', ['client' => $order->client->id, 'order' => $order->id]) }}"
                                                        class="btn btn-success btn-sm"><i class="fa fa-money"></i>
                                                        @lang('site.view_installments')</a>
                                                @else
                                                    <a href="#" disabled class="btn btn-success btn-sm"><i
                                                            class="fa fa-money"></i> @lang('site.view_installments')</a>
                                                @endif



                                            </td>

                                        </tr>
                                    @endforeach

                                </table><!-- end of table -->

                                {{ $orders->appends(request()->query())->links() }}

                            </div>
                        @else
                            <div class="box-body">
                                <h3>@lang('site.no_records')</h3>
                            </div>
                        @endif

                    </div><!-- end of box -->

                </div><!-- end of col -->
            </div><!-- end of row -->

        </section><!-- end of content section -->

    </div><!-- end of content wrapper -->

@endsection
