@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.clients')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.clients')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.clients')
                        <small>{{ $installments->total() }}</small>
                    </h3>

                    {{-- <form action="{{ route('admin.clients.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')"
                                    value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>
                                @if (auth()->user()->hasPermission('clients_create'))
                                    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary"><i
                                            class="fa fa-plus"></i> @lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i>
                                        @lang('site.add')</a>
                                @endif
                            </div>

                        </div>
                    </form><!-- end of form --> --}}

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($installments->count() > 0)
                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.order')</th>
                                    <th>@lang('site.installment_number')</th>
                                    <th>@lang('site.amount')</th>
                                    <th>@lang('site.due_date')</th>
                                    <th>@lang('site.paid_at')</th>
                                    <th>@lang('site.paid_amount')</th>
                                    <th>@lang('site.notes')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($installments as $index => $installment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $installment->order->id }}</td>
                                        <td>{{ $installment->installment_no }}</td>
                                        <td>{{ $installment->amount }}</td>
                                        <td>{{ $installment->due_date }}</td>
                                        <td>{{ $installment->paid_at }}</td>
                                        <td>{{ $installment->paid_amount }}</td>
                                        <td>{{ $installment->notes }}</td>

                                        <td>
                                            @if (auth()->user()->hasPermission('installments_update'))
                                                <a href="{{ route('admin.installments.edit', ['client' => $installment->client_id, 'order' => $installment->order_id, 'installment' => $installment->id]) }}"
                                                    class="btn btn-info btn-sm"><i class="fa fa-money"></i>
                                                    @lang('site.add_installment')</a>
                                            @else
                                                <a href="#" class="btn btn-info btn-sm disabled"><i
                                                        class="fa fa-money"></i> @lang('site.add_installment')</a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table><!-- end of table -->

                        {{ $installments->appends(request()->query())->links() }}
                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
