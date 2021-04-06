@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <input type="hidden" value="{{date("Y-m-d")}}" name="datainicio">
                <div class="form-group">
                    <label for="parent">Empresa</label>
                    <select name="idempresa" id="parent" class="form-control select2">
                        @foreach($empresas as $item)
                        <option value="{{$item->id}}">{{$item->razao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Descrição </label>
                    <input type="text" class="form-control ckeditor" name="descricao"
                    value="{{$promotion->descricao}}"
                     id="description" rows="5" placeholder="Descrição">
                </div>
                <div class="form-group">
                    <label for="cover">Banner Lateral </label>
                    <input type="file" name="bannerlateral" id="cover"
                    value="{{$promotion->bannerlateral}}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="cover">Banner Cartão </label>
                    <input type="file" name="bannercartao"
                    value="{{$promotion->bannercartao}}"
                     id="cover" class="form-control">
                </div>
                <div class="form-group">
                    <label for="description">Título </label>
                    <input type="text" name="titulo" 
                    value="{{$promotion->titulo}}"
                    id="description" rows="5" placeholder="Título">
                </div>
                <div class="form-group">
                    <label for="description">Subtitulo </label>
                    <input type="text" name="subtitulo" 
                    value="{{$promotion->subtitulo}}"
                    id="description" rows="5" placeholder="Subtitulo">
                </div>
                <div class="form-group">
                    <label for="description">Desconto Unitário</label>
                    <input type="number" 
                    value="{{$promotion->descontounitario}}"
                    name="descontounitario" id="description" rows="5" placeholder="Desconto Unitário">
                </div>
                <div class="form-group">
                    <label for="description">Desconto Percentual</label>
                    <input type="number" name="descontopercentual" 
                    value="{{$promotion->descontopercentual}}"id="description" rows="5" placeholder="Desconto Percentual">
                </div>
                <div class="form-group">
                    <label for="description">Data Final</label>
                    <input type="date" name="datafim" 
                    value="{{$promotion->datafim}}"id="description" rows="5" placeholder="Desconto Percentual">
                </div>
                <div class="form-group">
                    <label for="status">Ativo </label>
                    <select name="ativo" id="status" class="form-control">
                        <option value="0">Inativo</option>
                        <option value="1">Ativo</option>
                    </select>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="btn-group">
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-default">Voltar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection