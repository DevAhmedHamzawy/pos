@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.maintenances')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.maintenances.index') }}"> @lang('site.maintenances')</a></li>
                <li class="active">@lang('site.show')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.show')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="#" method="post">

                        <div class="form-group">
                            <label>@lang('site.status')</label>
                            <select name="status" class="form-control" style="height: 38px" readonly>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}">
                                        @lang('site.' . str_replace('_', ' ', $status->value))
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.clients')</label>
                            <select name="client_id" class="form-control" readonly>
                                <option value="">@lang('site.all_clients')</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ $maintenance->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.brands')</label>
                            <select name="brand_id" class="form-control" readonly>
                                <option value="">@lang('site.all_brands')</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ $maintenance->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.model')</label>
                            <input type="text" name="model" class="form-control" value="{{ $maintenance->model }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.imei')</label>
                            <input type="number" name="imei" class="form-control" value="{{ $maintenance->imei }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.price')</label>
                            <input type="number" name="price" id="maintenance_cost" step="0.01" class="form-control"
                                value="{{ $maintenance->price }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.space_part_price')</label>
                            <input type="number" name="space_part_price" id="space_part_price" step="0.01"
                                class="form-control" value="{{ $maintenance->space_part_price }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.total_price')</label>
                            <input type="number" name="total_price" id="total_price" class="form-control"
                                value="{{ $maintenance->total_price }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.description')</label>
                            <textarea name="description" class="form-control ckeditor" readonly>{{ $maintenance->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.notes')</label>
                            <textarea name="notes" class="form-control ckeditor" readonly>{{ $maintenance->notes }}</textarea>
                        </div>

                        {{-- <div class="card">
                            <div class="card-body">

                                <table class="table table-bordered" id="parts_table">

                                    <thead>
                                        <tr>
                                            <th>قطعة الغيار</th>
                                            <th width="120">الكمية</th>
                                            <th width="150">سعر الوحدة</th>
                                            <th width="150">الإجمالي</th>
                                            <th width="50">
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($maintenance->spaceParts as $i => $part)
                                            <tr>

                                                <td>
                                                    <select name="parts[{{ $i }}][id]" class="form-control part"
                                                        readonly>

                                                        @foreach ($space_parts as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-price="{{ $item->price }}"
                                                                {{ $item->id == $part->id ? 'selected' : '' }}>

                                                                {{ $item->name }}

                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="number" name="parts[{{ $i }}][quantity]"
                                                        class="form-control qty" value="{{ $part->pivot->quantity }}"
                                                        readonly>
                                                </td>

                                                <td>
                                                    <input type="number" name="parts[{{ $i }}][price]"
                                                        class="form-control price" value="{{ $part->pivot->price }}"
                                                        readonly>
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control total" readonly
                                                        value="{{ $part->pivot->quantity * $part->pivot->price }}"
                                                        readonly>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                                <div class="form-group">
                                    <label>@lang('site.final_total')</label>
                                    <input type="number" name="final_total" id="final_total" class="form-control"
                                        value="{{ old('total') }}" readonly>
                                </div>

                            </div>
                        </div> --}}

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                                @lang('site.show')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            calculateTotal();
        })

        let index = {{ $maintenance->spaceParts->count() }};

        // حساب صف واحد
        function calculateRow(row) {

            let qty = parseFloat(row.find('.qty').val()) || 0;

            let price = parseFloat(row.find('.price').val()) || 0;

            let total = qty * price;

            row.find('.total').val(total.toFixed(2));

            calculateTotal();

        }


        // حساب الإجمالي النهائي
        function calculateTotal() {

            let maintenanceCost = parseFloat($('#maintenance_cost').val()) || 0;

            let spacePartsTotal = 0;

            $('#parts_table tbody tr').each(function() {

                spacePartsTotal += parseFloat($(this).find('.total').val()) || 0;

            });

            $('#final_total').val((maintenanceCost + spacePartsTotal).toFixed(2));

        }


        // عند فتح صفحة الـ Edit
        $(document).ready(function() {

            $('#parts_table tbody tr').each(function() {

                calculateRow($(this));

            });

        });
    </script>
@endpush
