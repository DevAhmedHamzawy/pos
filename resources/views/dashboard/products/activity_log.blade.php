@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.product_activity_log')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.products.index') }}"><i class="fa fa-cubes"></i>@lang('site.products')</a></li>
                <li>{{ $product->name }}</li>
                <li class="active">@lang('site.product_activity_log')</li>

            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.product_activity_log')
                        <small>{{ $activities->total() }}</small>
                    </h3>

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($activities->count() > 0)
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="70">#</th>
                                    <th>{{ __('site.qty_type') }}</th>
                                    <th>{{ __('site.qty_reason') }}</th>
                                    <th>{{ __('site.quantity') }}</th>
                                    <th>{{ __('site.description') }}</th>
                                    <th>{{ __('site.user') }}</th>
                                    <th>{{ __('site.client') }}</th>
                                    <th>{{ __('site.order') }}</th>
                                    <th>{{ __('site.date') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($activities as $activity)
                                    <tr>

                                        <td>{{ $activity->id }}</td>

                                        <td>
                                            @if ($activity->type == 'in')
                                                <span class="badge badge-success">
                                                    {{ __('site.in') }}
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    {{ __('site.out') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            @switch($activity->status)
                                                @case('stock_purchase')
                                                    <span class="badge badge-primary">
                                                        {{ __('site.stock purchase') }}
                                                    </span>
                                                @break

                                                @case('stock_transfer')
                                                    <span class="badge badge-primary">
                                                        {{ __('site.stock transfer') }}
                                                    </span>
                                                @break

                                                @case('return')
                                                    <span class="badge badge-warning">
                                                        {{ __('site.return') }}
                                                    </span>
                                                @break

                                                @case('damaged')
                                                    <span class="badge badge-danger">
                                                        {{ __('site.damaged') }}
                                                    </span>
                                                @break

                                                @case('inventory_correction')
                                                    <span class="badge badge-info">
                                                        {{ __('site.inventory correction') }}
                                                    </span>
                                                @break

                                                @default
                                                    <span class="badge badge-secondary">
                                                        {{ __('site.other') }}
                                                    </span>
                                            @endswitch
                                        </td>

                                        <td>
                                            @if ($activity->type == 'in')
                                                <span class="text-success">
                                                    +{{ $activity->quantity }}
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    -{{ $activity->quantity }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>{{ $activity->description ?? '-' }}</td>

                                        <td>{{ $activity->user_name }}</td>

                                        <td>
                                            {{ $activity->client_name ?? '-' }}
                                        </td>

                                        <td>
                                            @if ($activity->order_id)
                                                #{{ $activity->order_id }}
                                            @elseif ($activity->order_number)
                                                #{{ $activity->order_number }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d h:i A') }}
                                        </td>

                                    </tr>

                                    @empty

                                        <tr>
                                            <td colspan="6" class="text-center">
                                                {{ __('site.no_data_found') }}
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                    </div>

                    <div class="card-footer clearfix">
                        {{ $activities->links() }}
                    </div>

                </div>
            @else
                <h2>@lang('site.no_data_found')</h2>
                @endif

        </div><!-- end of box body -->


        </div><!-- end of box -->

        </section><!-- end of content -->

        </div><!-- end of content wrapper -->
    @endsection
