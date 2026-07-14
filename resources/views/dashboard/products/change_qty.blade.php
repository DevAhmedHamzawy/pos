@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.products')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.products.index') }}"> @lang('site.products')</a></li>
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

                    <form action="{{ route('admin.products.changeQty', $product->id) }}" method="post"
                        enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('post') }}

                        <div class="form-group">
                            <label>@lang('site.qty')</label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                value="{{ old('quantity') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.qty_type')</label>
                            <select name="type" id="type" class="form-control" style="height: 38px">
                                @foreach ($types as $type)
                                    <option value="{{ $type->value }}">
                                        @lang('site.' . str_replace('_', ' ', $type->value))
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.qty_reason')</label>
                            <select name="status" class="form-control" style="height: 38px">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}">
                                        @lang('site.' . str_replace('_', ' ', $status->value))
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.current_qty')</label>
                            <input type="number" class="form-control" value="{{ $product->stock }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('site.new_qty')</label>
                            <input type="number" id="new_qty" class="form-control" value="{{ $product->stock }}"
                                readonly>
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
        $('#quantity').on('keyup', function() {
            if ($('#type').val() == 'in') {
                $('#new_qty').val(parseInt($(this).val()) + {!! $product->stock !!});
            } else {
                $('#new_qty').val(parseInt({!! $product->stock !!} - $(this).val()));
            }
        });

        $('#type').on('change', function() {
            if ($('#type').val() == 'in') {
                $('#new_qty').val(parseInt($('#quantity').val()) + {!! $product->stock !!});
            } else {
                $('#new_qty').val(parseInt({!! $product->stock !!} - $('#quantity').val()));
            }
        });
    </script>
@endpush
