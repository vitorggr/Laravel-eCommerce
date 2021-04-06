@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.categories.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="parent">Empresa</label>
                        <select name="idempresa" id="parent" class="form-control select2">
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->fantasia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="descricao" id="name" placeholder="Nome" class="form-control" value="{{ old('name') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="name" placeholder="Nome" class="form-control" value="{{ old('name') }}">
                    </div>
                    <!-- <div class="form-group">
                        <label for="description">Descrição </label>
                        <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Descrição">{{ old('description') }}</textarea>
                    </div> -->
                    <div class="form-group">
                        <label for="banner">Banner </label>
                        <input type="file" name="banner" id="cover" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status">Status </label>
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
