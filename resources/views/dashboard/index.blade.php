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
                <div class="col-lg-3 col-xs-6">
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
                <div class="col-lg-3 col-xs-6">
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
                <div class="col-lg-3 col-xs-6">
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

            <div class="box box-solid">

                <div class="box-header">
                    <h3 class="box-title">Sales Graph</h3>
                </div>
                <div class="box-body border-radius-none">
                    <div class="chart" id="line-chart" style="height: 250px;"></div>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="row">

                <div class="col-lg-3">

                    <div class="small-box bg-danger">

                        <div class="inner">

                            <h3>

                                {{ $lateInstallmentsCount }}

                            </h3>

                            <p>

                                الأقساط المتأخرة

                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="small-box bg-warning">

                        <div class="inner">

                            <h3>

                                {{ number_format($lateInstallmentsTotal, 2) }}

                            </h3>

                            <p>

                                إجمالى المتأخرات

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <div class="row">

                <!-- مستحق اليوم -->
                <div class="col-lg-3 col-md-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $todayInstallments }}</h3>
                            <p>الأقساط المستحقة اليوم</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            عرض التفاصيل
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

                <!-- متأخر -->
                <div class="col-lg-3 col-md-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $lateInstallmentsCount }}</h3>
                            <p>الأقساط المتأخرة</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            عرض التفاصيل
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

                <!-- المحصل -->
                <div class="col-lg-3 col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $paidThisMonth }}</h3>
                            <p>المحصلة هذا الشهر</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            عرض التفاصيل
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

                <!-- خلال 3 أيام -->
                <div class="col-lg-3 col-md-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $next3Days }}</h3>
                            <p>تستحق خلال 3 أيام</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            عرض التفاصيل
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

            </div>

            <div class="row">

                <!-- الأقساط المتأخرة -->
                <div class="col-md-6">

                    <div class="card card-danger card-outline">

                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle"></i>
                                الأقساط المتأخرة
                            </h3>
                        </div>

                        <div class="card-body table-responsive p-0">

                            <table class="table table-hover text-nowrap">

                                <thead>
                                    <tr>
                                        <th>العميل</th>
                                        <th>القسط</th>
                                        <th>الاستحقاق</th>
                                        <th>المتبقى</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($lateInstallments as $item)
                                        <tr>

                                            <td>{{ $item->order->client->name }}</td>

                                            <td>#{{ $item->installment_no }}</td>

                                            <td>
                                                {{ $item->due_date->format('Y-m-d') }}
                                            </td>

                                            <td>

                                                <span class="badge badge-danger">

                                                    {{ number_format($item->amount - $item->paid_amount, 2) }}

                                                </span>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4" class="text-center">

                                                لا توجد أقساط متأخرة

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

                    <div class="card card-warning card-outline">

                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="fas fa-clock"></i>

                                الأقساط المستحقة خلال 3 أيام

                            </h3>

                        </div>

                        <div class="card-body table-responsive p-0">

                            <table class="table table-hover">

                                <thead>

                                    <tr>

                                        <th>العميل</th>

                                        <th>القسط</th>

                                        <th>التاريخ</th>

                                        <th>القيمة</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($nextInstallments as $item)
                                        <tr>

                                            <td>{{ $item->order->client->name }}</td>

                                            <td>#{{ $item->installment_no }}</td>

                                            <td>{{ $item->due_date->format('Y-m-d') }}</td>

                                            <td>

                                                <span class="badge badge-warning">

                                                    {{ number_format($item->amount, 2) }}

                                                </span>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4" class="text-center">

                                                لا توجد أقساط قريبة

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

                    <div class="card card-success card-outline">

                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="fas fa-money-check-alt"></i>

                                آخر الأقساط المحصلة

                            </h3>

                        </div>

                        <div class="card-body table-responsive p-0">

                            <table class="table table-striped">

                                <thead>

                                    <tr>

                                        <th>العميل</th>

                                        <th>القسط</th>

                                        <th>المدفوع</th>

                                        <th>تاريخ الدفع</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($paidInstallments as $item)
                                        <tr>

                                            <td>{{ $item->order->client->name }}</td>

                                            <td>#{{ $item->installment_no }}</td>

                                            <td>{{ number_format($item->paid_amount, 2) }}</td>

                                            <td>{{ optional($item->paid_at)->format('Y-m-d') }}</td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4" class="text-center">

                                                لا توجد بيانات

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

                    متأخر عن سداد القسط رقم

                    <strong>#{{ $installment->installment_no }}</strong>

                    الخاص بالطلب

                    <strong>#{{ $installment->order_id }}</strong>

                    <br>

                    تم سداد

                    <strong>{{ number_format($installment->paid_amount, 2) }} ج.م</strong>

                    من إجمالى

                    <strong>{{ number_format($installment->amount, 2) }} ج.م</strong>

                    ويتبقى

                    <strong class="text-warning">
                        {{ number_format($installment->amount - $installment->paid_amount, 2) }} ج.م
                    </strong>

                    <br>

                    <small>
                        كان موعد الاستحقاق
                        {{ $installment->due_date->format('Y-m-d') }}
                        ({{ $installment->due_date->diffForHumans() }})
                    </small>

                </div>
            @endforeach

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
    </script>
@endpush
