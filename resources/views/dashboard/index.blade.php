@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.dashboard')</h1>

            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $todaySales }}</h3>

                            <p>@lang('site.today_sales')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-first-order"></i>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="small-box-footer">@lang('site.read') <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $todayOrders }}</h3>

                            <p>@lang('site.today_orders')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-archive"></i>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="small-box-footer">@lang('site.read') <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $maintenances }}</h3>

                            <p>@lang('site.maintenance_devices')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tablet"></i>
                        </div>
                        <a href="{{ route('admin.maintenances.index') }}" class="small-box-footer">@lang('site.read') <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>



                {{-- categories --}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $categories_count }}</h3>

                            <p>@lang('site.categories')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('admin.categories.index') }}" class="small-box-footer">@lang('site.read') <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- products --}}
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ $products_count }}</h3>

                            <p>@lang('site.products')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="small-box-footer">@lang('site.read') <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- clients --}}
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $clients_count }}</h3>

                            <p>@lang('site.clients')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <a href="{{ route('admin.clients.index') }}" class="small-box-footer">@lang('site.read') <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- users --}}
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $users_count }}</h3>

                            <p>@lang('site.users')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="small-box-footer">@lang('site.read') <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div><!-- end of row -->


            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('site.last_30_days')</h3>
                </div>

                <div class="box-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('site.profits')</h3>
                        </div>

                        <div class="box-body">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-success">

                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @lang('site.most_sold_products')
                            </h3>
                        </div>

                        <div class="box-body table-responsive">

                            <table class="table table-bordered">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.product')</th>
                                        <th>@lang('site.quantity')</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($topProducts as $product)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $product->name }}</td>

                                            <td>{{ $product->total_quantity }}</td>


                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @lang('site.quiet_expired_products') ({{ $almostProducts }})
                            </h3>
                        </div>

                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover">

                                <thead>
                                    <tr>
                                        <th>@lang('site.product')</th>
                                        <th>@lang('site.stock')</th>
                                        <th>@lang('site.status')</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($almostProductsTable as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>
                                                @if ($product->stock == 0)
                                                    <span class="label label-danger">
                                                        @lang('site.out_of_stock')
                                                    </span>
                                                @else
                                                    <span class="label label-success">
                                                        @lang('site.low_stock')
                                                    </span>
                                                @endif

                                            </td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="2" class="text-center">
                                                @lang('site.no_quiet_expired_products')
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-primary">

                        <div class="box-header with-border">

                            <h3 class="box-title">

                                @lang('site.last_orders')

                            </h3>

                        </div>

                        <div class="box-body table-responsive">

                            <table class="table table-hover">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>@lang('site.client')</th>

                                        <th>@lang('site.total')</th>

                                        <th>@lang('site.date')</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($latestOrders as $order)
                                        <tr>

                                            <td>#{{ $order->id }}</td>

                                            <td>{{ $order->client->name }}</td>

                                            <td>{{ number_format($order->total_price, 2) }}</td>

                                            <td>{{ $order->created_at->diffForHumans() }}</td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('site.maintenance_status')</h3>
                        </div>

                        <div class="box-body">

                            <strong>@lang('site.pending')</strong>
                            <span class="pull-right">{{ $pending }} @lang('site.device')</span>

                            <div class="progress">
                                <div class="progress-bar progress-bar-danger" style="width:{{ $pendingWidth }}%"></div>
                            </div>

                            <strong>@lang('site.in_progress')</strong>
                            <span class="pull-right">{{ $inProgress }} @lang('site.device')</span>

                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" style="width:{{ $inProgressWidth }}%">
                                </div>
                            </div>

                            <strong>@lang('site.completed')</strong>
                            <span class="pull-right">{{ $completed }} @lang('site.device')</span>

                            <div class="progress">
                                <div class="progress-bar progress-bar-info" style="width:{{ $completedWidth }}%"></div>
                            </div>

                            <strong>@lang('site.delivered')</strong>
                            <span class="pull-right">{{ $delivered }} @lang('site.device')</span>

                            <div class="progress">
                                <div class="progress-bar progress-bar-success" style="width:{{ $deliveredWidth }}%">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>



            <div class="row">

                {{-- العملاء الجدد --}}
                <div class="col-md-6">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon">
                            <i class="fa fa-users"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">@lang('site.new_clients_month')</span>
                            <span class="info-box-number">
                                {{ $newClientsMonth }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- أفضل عميل --}}
                <div class="col-md-6">
                    <div class="info-box bg-green">

                        <span class="info-box-icon">
                            <i class="fa fa-trophy"></i>
                        </span>

                        <div class="info-box-content">

                            <span class="info-box-text">
                                @lang('site.best_client')
                            </span>

                            @if ($topClient)
                                <span class="info-box-number">
                                    {{ $topClient->client->name }}
                                </span>

                                <span>
                                    @lang('site.total_sales')
                                    {{ number_format($topClient->total_sales, 2) }}
                                    @lang('site.egp')
                                </span>
                            @else
                                <span class="info-box-number">
                                    @lang('site.no_data')
                                </span>
                            @endif

                        </div>

                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-4">
                    <div class="info-box bg-aqua">

                        <span class="info-box-icon">
                            <i class="fa fa-cubes"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.total_stock')
                            </span>

                            <span class="info-box-number">
                                {{ number_format($stockValue) }}
                            </span>
                        </div>

                    </div>
                </div>



                <div class="col-md-4">
                    <div class="info-box bg-red">

                        <span class="info-box-icon">
                            <i class="fa fa-times-circle"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.expired_products')
                            </span>

                            <span class="info-box-number">
                                {{ $stockExpired }}
                            </span>
                        </div>

                    </div>
                </div>


                <div class="col-md-4">
                    <div class="info-box bg-red">

                        <span class="info-box-icon">
                            <i class="fa fa-times-circle"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.almost_expired_products')
                            </span>

                            <span class="info-box-number">
                                {{ $almostProducts }}
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-3">
                    <div class="info-box bg-green">
                        <span class="info-box-icon">
                            <i class="fa fa-money"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.today_profit')
                            </span>

                            <span class="info-box-number">
                                {{ number_format($todayProfit, 2) }} @lang('site.egp')
                            </span>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon">
                            <i class="fa fa-calendar"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.weekly_profit')
                            </span>

                            <span class="info-box-number">
                                {{ number_format($weekProfit, 2) }} @lang('site.egp')
                            </span>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon">
                            <i class="fa fa-line-chart"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.monthly_profit')
                            </span>

                            <span class="info-box-number">
                                {{ number_format($monthProfit, 2) }} @lang('site.egp')
                            </span>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="info-box bg-red">
                        <span class="info-box-icon">
                            <i class="fa fa-bar-chart"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.yearly_profit')
                            </span>

                            <span class="info-box-number">
                                {{ number_format($yearProfit, 2) }} @lang('site.egp')
                            </span>
                        </div>
                    </div>
                </div>

            </div>


            <div class="box box-solid">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.sales_statistics')</h3>
                </div>
                <div class="box-body border-radius-none">
                    <div class="chart" id="line-chart" style="height: 250px;"></div>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="row">

                <!-- الأقساط المتأخرة -->
                <div class="col-lg-6 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $lateInstallmentsCount }}</h3>
                            <p>@lang('site.late_installments')</p>
                        </div>

                        <div class="icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>


                    </div>
                </div>

                <!-- إجمالي المتأخرات -->
                <div class="col-lg-6 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ number_format($lateInstallmentsTotal, 2) }}</h3>
                            <p>@lang('site.late_installments_total')</p>
                        </div>

                        <div class="icon">
                            <i class="fa fa-money"></i>
                        </div>


                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon">
                            <i class="fa fa-list"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.total_installments')
                            </span>

                            <span class="info-box-number">
                                {{ $totalInstallmentsCount }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-green">
                        <span class="info-box-icon">
                            <i class="fa fa-check"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.paid_installments')
                            </span>

                            <span class="info-box-number">
                                {{ $paidInstallmentsCount }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon">
                            <i class="fa fa-clock-o"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                @lang('site.remaining_installments')
                            </span>

                            <span class="info-box-number">
                                {{ $remainingInstallmentsCount }}
                            </span>
                        </div>
                    </div>
                </div>



            </div>

            <div class="box box-warning">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        @lang('site.today_installments')
                    </h3>
                </div>

                <div class="box-body table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>@lang('site.order_number')</th>
                                <th>@lang('site.client')</th>
                                <th>@lang('site.amount')</th>
                                <th>@lang('site.due_date')</th>
                                <th>@lang('site.status')</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($todayInstallmentsTable as $installment)
                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>#{{ $installment->order->id }}</td>

                                    <td>{{ $installment->order->client->name }}</td>

                                    <td>{{ number_format($installment->amount, 2) }} @lang('site.egp')</td>

                                    <td>{{ $installment->due_date }}</td>

                                    <td>

                                        @if ($installment->status == 'paid')
                                            <span class="label label-success">
                                                @lang('site.paid')
                                            </span>
                                        @elseif($installment->status == 'unpaid')
                                            <span class="label label-danger">
                                                @lang('site.unpaid')
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center">
                                        @lang('site.no_today_installments')
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="row">

                <!-- مستحق اليوم -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $todayInstallments }}</h3>
                            <p>@lang('site.today_installments_deserved')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>



                <!-- المحصل -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ $paidThisMonth }}</h3>
                            <p>@lang('site.this_month_installments')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <!-- خلال 3 أيام -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $next3DaysCount }}</h3>
                            <p>@lang('site.three_days_installments_deserved')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <!-- الأقساط المتأخرة -->
                <div class="col-md-6">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-exclamation-triangle"></i>
                                @lang('site.late_installments')
                            </h3>
                        </div>

                        <div class="box-body table-responsive no-padding">

                            <table class="table table-hover">

                                <thead>
                                    <tr>
                                        <th>@lang('site.client')</th>
                                        <th>@lang('site.installment')</th>
                                        <th>@lang('site.due_date')</th>
                                        <th>@lang('site.remaining')</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($lateInstallments as $item)
                                        <tr>
                                            <td>{{ $item->order->client->name }}</td>
                                            <td>#{{ $item->installment_no }}</td>
                                            <td>{{ $item->due_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="label label-danger">
                                                    {{ number_format($item->amount - $item->paid_amount, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                @lang('site.no_late_installments')
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <!-- خلال 3 أيام -->
                <div class="col-md-6">

                    <div class="box box-warning">

                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-clock-o"></i>
                                @lang('site.three_days_installments')
                            </h3>
                        </div>

                        <div class="box-body table-responsive no-padding">

                            <table class="table table-hover">

                                <thead>
                                    <tr>
                                        <th>@lang('site.client')</th>
                                        <th>@lang('site.installment')</th>
                                        <th>@lang('site.due_date')</th>
                                        <th>@lang('site.amount')</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($next3Days as $item)
                                        <tr>
                                            <td>{{ $item->order->client->name }}</td>
                                            <td>#{{ $item->installment_no }}</td>
                                            <td>{{ $item->due_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="label label-warning">
                                                    {{ number_format($item->amount, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                @lang('site.no_close_installments')
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-12">

                    <div class="box box-success">

                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-money"></i>
                                @lang('site.last_paid_installments')
                            </h3>
                        </div>

                        <div class="box-body table-responsive no-padding">

                            <table class="table table-striped">

                                <thead>
                                    <tr>
                                        <th>@lang('site.client')</th>
                                        <th>@lang('site.installment')</th>
                                        <th>@lang('site.paid_amount')</th>
                                        <th>@lang('site.paid_at')</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($paidInstallments as $item)
                                        <tr>
                                            <td>{{ $item->order->client->name }}</td>
                                            <td>#{{ $item->installment_no }}</td>
                                            <td>
                                                <span class="label label-success">
                                                    {{ number_format($item->paid_amount, 2) }}
                                                </span>
                                            </td>
                                            <td>{{ optional($item->paid_at)->format('Y-m-d') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                @lang('site.no_data')
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            @foreach ($lateInstallments as $installment)
                <div class="alert alert-danger mb-2">

                    <strong>{{ $installment->order->client->name }}</strong>

                    @lang('site.late_installment_no')

                    <strong>#{{ $installment->installment_no }}</strong>

                    @lang('site.order_specific')

                    <strong>#{{ $installment->order_id }}</strong>

                    <br>

                    @lang('site.paid_done')

                    <strong>{{ number_format($installment->paid_amount, 2) }} @lang('site.egp')</strong>

                    @lang('site.from_total')

                    <strong>{{ number_format($installment->amount, 2) }} @lang('site.egp')</strong>

                    @lang('site.remain')

                    <strong>
                        {{ number_format($installment->amount - $installment->paid_amount, 2) }} @lang('site.egp')
                    </strong>

                    <br>

                    <small>
                        @lang('site.was_deserved')
                        {{ $installment->due_date->format('Y-m-d') }}
                        ({{ $installment->due_date->diffForHumans() }})
                    </small>

                </div>
            @endforeach



            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-history"></i>
                        {{ __('site.activity_logs') }}
                    </h3>
                </div>

                <div class="box-body p-0">

                    @if ($activityLogs->count())
                        <ul class="products-list product-list-in-box">

                            @foreach ($activityLogs as $log)
                                <li class="item">



                                    <div class="product-info">

                                        <span class="product-title">

                                            {{ ucfirst($log->description) }}

                                            <small class="pull-right text-muted">
                                                <i class="fa fa-clock-o"></i>
                                                {{ $log->created_at->diffForHumans() }}
                                            </small>

                                        </span>



                                    </div>

                                </li>
                            @endforeach

                        </ul>
                    @else
                        <div class="text-center" style="padding:50px">

                            <i class="fa fa-history fa-4x text-muted"></i>

                            <h4 class="text-muted">
                                No Activity Logs Found
                            </h4>

                        </div>
                    @endif

                </div>

            </div>


        </section><!-- end of content -->

    </div><!-- end of content wrapper -->
@endsection

@push('scripts')
    <script>
        //line chart
        var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: [
                @foreach ($sales_data as $data)
                    {
                        ym: "{{ $data->year }}-{{ $data->month }}",
                        sum: "{{ $data->sum }}"
                    },
                @endforeach
            ],
            xkey: 'ym',
            ykeys: ['sum'],
            labels: ['@lang('site.total')'],
            lineWidth: 2,
            hideHover: 'auto',
            gridStrokeWidth: 0.4,
            pointSize: 4,
            gridTextFamily: 'Open Sans',
            gridTextSize: 10
        });


        var ctx = document.getElementById('salesChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($salesLabels),
                datasets: [{
                    label: 'المبيعات',
                    data: @json($salesData),
                    borderColor: '#3c8dbc',
                    backgroundColor: 'rgba(60,141,188,.15)',
                    borderWidth: 3,
                    fill: true,
                    tension: .3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('revenueChart'), {

            type: 'doughnut',

            data: {

                labels: [
                    'المبيعات',
                    'الصيانة',
                    'قطع الغيار'
                ],

                datasets: [{

                    data: [
                        {{ $revenues['sales'] }},
                        {{ $revenues['maintenance'] }},
                        {{ $revenues['parts'] }}
                    ],

                    backgroundColor: [
                        '#00a65a',
                        '#3c8dbc',
                        '#f39c12'
                    ]

                }]
            },

        });
    </script>
@endpush
