@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
    @if($promotions)
    <div class="box">
        <div class="box-body">
            <h2>Promoções</h2>
            <table class="table">
                <thead>
                    <tr>
                        <td class="col-md-2">Descricao</td>
                        <td class="col-md-2">Data Final</td>
                        <td class="col-md-2">Desconto Unitario</td>
                        <td class="col-md-2">Desconto Percentual</td>
                        <td class="col-md-2">Ativo</td>
                        <td class="col-md-2"></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $item)
                    <tr>
                        <td>
                            <a href="{{ route('admin.promotions.show', $item->id) }}">{{ $item->descricao }}</a>
                        </td>
                        <td>{{date("Y-m-d",$item->datafinaldate)}}</td>
                        <td>{{$item->descontounitario}}</td>
                        <td>{{$item->descontopercentual}}%</td>
                        <td>{{$item->ativo}}</td>
                        <td>
                            <form action="{{ route('admin.promotions.destroy', $item->id) }}" method="post" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete">
                                <div class="btn-group">
                                    <a href="{{ route('admin.promotions.edit', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                    <button onclick="return confirm('Deseja deletar essa Promoção?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Deletar</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $promotions->links() }}
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    @endif

</section>
<!-- /.content -->
@endsection