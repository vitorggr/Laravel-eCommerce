@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
    @if($coupon)
    <div class="box">
        <div class="box-body">
            <h2>Cupom</h2>
            <table class="table">
                <thead>
                    <tr>
                        <td class="col-md-3">Código</td>
                        <td class="col-md-3">Descrição</td>
                        <td class="col-md-3">Desconto Unitário</td>
                        <td class="col-md-3">Desconto Percentual</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="{{ route('admin.coupons.show', $coupon->id) }}">{{ $coupon->codigo }}</a>
                        </td>
                        <td>
                            {{$coupon->descricao}}
                        </td>
                        <td>{{$coupon->desconto}}</td>
                        <td>{{$coupon->descontopercentual}}%</td>

                    </tr>
                </tbody>
            </table>
        </div>

        <!-- /.box-body -->
        <div class="box-footer">
            <div class="btn-group">
                <a href="{{ route('admin.collections.index') }}" class="btn btn-default btn-sm">Back</a>
            </div>
        </div>
    </div>
    <!-- /.box -->
    @endif

</section>
<!-- /.content -->
@endsection