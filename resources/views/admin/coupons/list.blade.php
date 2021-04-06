@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($cupons)
            <div class="box">
                <div class="box-body">
                    <h2>Cupons</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-3">Código</td>
                                <td class="col-md-3">Descrição</td>
                                <td class="col-md-3">Desconto Unitário</td>
                                <td class="col-md-3">Desconto Percentual</td>
                                <td class="col-md-3"></td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($cupons as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.coupons.show', $item->id) }}">{{ $item->codigo }}</a></td>
                                <td>
                                  {{$item->descricao}}
                                </td>
                                <td>{{$item->desconto}}</td>
                                <td>{{$item->descontopercentual}}%</td>
                                <td>
                                    <form action="{{ route('admin.coupons.destroy', $item->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.coupons.edit', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                            <button onclick="return confirm('Deseja deletar essa categoria?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Deletar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $cupons->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
