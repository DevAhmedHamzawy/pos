@extends('layouts.dashboard.app')

@section('title', __('dashboard.activity_logs'))

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>
                {{ __('site.activity_logs') }}
                <small>{{ $activityLogs->count() }} {{ __('site.activity_logs') }}</small>
            </h1>

            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard.index') }}">
                        <i class="fa fa-dashboard"></i>
                        @lang('site.dashboard')
                    </a>
                </li>
                <li class="active">{{ __('site.activity_logs') }}</li>
            </ol>

        </section>

        <section class="content">

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

        </section>

    </div>

@endsection
