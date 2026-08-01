@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.maintenances')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.maintenances')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.maintenances')
                        <small>{{ $maintenances->total() }}</small>
                    </h3>

                    <form action="{{ route('admin.maintenances.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search_client_maintenance_imei')"
                                    value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="status" class="form-control" style="height: 38px">
                                        <option value="">@lang('site.all')</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->value }}">
                                                @lang('site.' . str_replace('_', ' ', $status->value))
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>
                                @if (auth()->user()->hasPermission('maintenances_create'))
                                    <a href="{{ route('admin.maintenances.create') }}" class="btn btn-primary"><i
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

                    @if ($maintenances->count() > 0)
                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.client')</th>
                                    <th>@lang('site.phone')</th>
                                    <th>@lang('site.brand')</th>
                                    <th>@lang('site.model')</th>
                                    <th>@lang('site.imei')</th>
                                    <th>@lang('site.price')</th>
                                    <th>@lang('site.space_part_price')</th>
                                    <th>@lang('site.total_price')</th>
                                    <th>@lang('site.description')</th>
                                    <th>@lang('site.status')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($maintenances as $index => $maintenance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $maintenance->client->name }}</td>
                                        <td>{{ implode('- ', $maintenance->client->phone) }}</td>
                                        <td>{{ $maintenance->brand->name }}</td>
                                        <td>{{ $maintenance->model }}</td>
                                        <td>{{ $maintenance->imei }}</td>
                                        <td>{{ $maintenance->price }}</td>
                                        <td>{{ $maintenance->space_part_price }}</td>
                                        <td>{{ $maintenance->subtotal }}</td>
                                        <td>{!! $maintenance->description !!}</td>
                                        <th>@lang('site.' . str_replace('_', ' ', $maintenance->status))</th>
                                        <td>
                                            @if (auth()->user()->hasPermission('maintenances_read'))
                                                <a href="{{ route('admin.maintenances.show', $maintenance->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                                    @lang('site.show')</a>
                                            @else
                                                <a href="#" class="btn btn-info btn-sm disabled"><i
                                                        class="fa fa-edit"></i> @lang('site.edit')</a>
                                            @endif
                                            @if (auth()->user()->hasPermission('maintenances_update'))
                                                <a href="{{ route('admin.maintenances.edit', $maintenance->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                                    @lang('site.edit')</a>
                                            @else
                                                <a href="#" class="btn btn-info btn-sm disabled"><i
                                                        class="fa fa-edit"></i> @lang('site.edit')</a>
                                            @endif
                                            @if (auth()->user()->hasPermission('maintenances_delete'))
                                                <form action="{{ route('admin.maintenances.destroy', $maintenance->id) }}"
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

                        {{ $maintenances->appends(request()->query())->links() }}
                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
