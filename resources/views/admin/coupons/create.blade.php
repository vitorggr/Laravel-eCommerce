@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.coupons.store') }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="post">
                <div class="form-group">
                    <label for="name">Código <span class="text-danger">*</span></label>
                    <input type="text" name="codigo" id="name" placeholder="Nome" class="form-control">
                </div>
                <div class="form-group">
                    <label for="description">Descrição </label>
                    <textarea class="form-control ckeditor" name="descricao" id="description" rows="5" placeholder="Descrição"></textarea>
                </div>
                <div class="form-group">
                    <label for="parent">Empresa</label>
                    <select name="idempresa" id="parent" class="form-control select2">
                        @foreach($empresas as $item)
                        <option value="{{$item->id}}">{{$item->razao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Limite <span class="text-danger">*</span></label>
                    <input type="number" name="limite" id="name" placeholder="limite" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Desconto Unitario <span class="text-danger">*</span></label>
                    <input type="number" name="desconto" id="name" placeholder="desconto" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Desconto Percentual <span class="text-danger">*</span></label>
                    <input type="number" name="descontopercentual" id="name" placeholder="descontopercentual" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Validade <span class="text-danger">*</span></label>
                    <input type="date" name="validade" id="name" placeholder="validade" class="form-control">
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
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Voltar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection