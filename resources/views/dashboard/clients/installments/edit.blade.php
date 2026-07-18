@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.add_installment')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.clients.index') }}"> @lang('site.clients')</a></li>
                <li>{{ $client->name }}</li>
                <li>@lang('site.orders')</li>
                <li>{{ $order->id }}</li>
                <li><a
                        href="{{ route('admin.installments.index', ['client' => $order->client->id, 'order' => $order->id]) }}">@lang('site.installments')</a>
                </li>
                <li>{{ $installment->installment_no }}</li>
                <li class="active">@lang('site.add_installment')</li>

            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.add_installment')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('admin.installments.update', [$client, $order, $installment]) }}" method="post">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group">
                            <label>@lang('site.installment_no')</label>
                            <input type="number" name="installment_no" class="form-control"
                                value="{{ $installment->installment_no }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.paid_amount')</label>
                            <input type="number" name="paid_amount" class="form-control" value="{{ old('paid_amount') }}">
                        </div>


                        <div class="form-group">
                            <label>@lang('site.notes')</label>
                            <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                                @lang('site.add')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->
@endsection
