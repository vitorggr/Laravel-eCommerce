@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
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
                    <input type="text" name="descricao" id="name" placeholder="Nome" class="form-control" value="{{ $category->descricao }}">
                </div>

                <div class="form-group">
                    <label for="descricao">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" id="name" placeholder="Nome" class="form-control" value="{{ $category->slug }}">
                </div>
                <div class="form-group">
                    <label for="banner">Banner </label>
                    <input type="file" name="banner" id="cover" value="{{$category->banner}}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="status">Status </label>
                    <select name="ativo" value="{{$category->ativo}}" id="status" class="form-control">
                        <option value="0">Inativo</option>
                        <option value="1">Ativo</option>
                    </select>
                </div>
            </div>
            <div class="box-footer">
                <div class="btn-group">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Voltar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection