@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.maintenances')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.maintenances.index') }}"> @lang('site.maintenances')</a></li>
                <li class="active">@lang('site.add')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.add')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('admin.maintenances.store') }}" method="post">

                        {{ csrf_field() }}
                        {{ method_field('post') }}

                        <div class="form-group">
                            <label>@lang('site.clients')</label>
                            <select name="client_id" class="form-control">
                                <option value="">@lang('site.all_clients')</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.brands')</label>
                            <select name="brand_id" class="form-control">
                                <option value="">@lang('site.all_brands')</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.model')</label>
                            <input type="text" name="model" class="form-control" value="{{ old('model') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.imei')</label>
                            <input type="number" name="imei" class="form-control" value="{{ old('imei') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.total_price')</label>
                            <input type="number" name="price" id="price" step="0.01" class="form-control"
                                value="{{ old('price') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.space_part_price')</label>
                            <input type="number" name="space_part_price" id="space_part_price" step="0.01"
                                class="form-control" value="{{ old('space_part_price') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.subtotal')</label>
                            <input type="number" name="subtotal" id="subtotal" class="form-control"
                                value="{{ old('subtotal') }}" readonly>
                        </div>


                        <div class="form-group">
                            <label>@lang('site.description')</label>
                            <textarea name="description" class="form-control ckeditor">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.notes')</label>
                            <textarea name="notes" class="form-control ckeditor">{{ old('notes') }}</textarea>
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

@push('scripts')
    <script>
        $(document).on('keyup', '#price, #space_part_price', function() {
            $('#subtotal').val($('#price').val() - $('#space_part_price').val());
        });
    </script>
@endpush
