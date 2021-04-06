@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.collections.update', $collection->id) }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group">
                    <label for="parent">Empresa</label>
                    <select name="parent" id="parent" class="form-control select2">
                        <option value="0"></option>
                        @foreach($empresas as $item)
                        <option value="{{$item->id}}">{{$item->razao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Nome <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" placeholder="Nome" class="form-control" value="{{$collection->descricao}}">
                </div>
                <div class="form-group">
                    <label for="description">Descrição </label>
                    <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Descrição">{!! $collection->descricao!!}</textarea>
                </div>
                @if(isset($collection->cover))
                <div class="form-group">
                    <img src="{{ asset("storage/$collection->cover") }}" alt="" class="img-responsive"> <br />
                    <a onclick="return confirm('Continuar ?')" href="{{ route('admin.collection.remove.image', ['collection' => $collection->id]) }}" class="btn btn-danger">Remover imagem?</a>
                </div>
                @endif
                <div class="form-group">
                    <label for="cover">Cover </label>
                    <input type="file" name="cover" id="cover" class="form-control">
                </div>
                <div class="form-group">
                    <label for="status">Ativa </label>
                    <select name="status" id="status" class="form-control">
                        <option value="0" @if($collection->ativa == 0) selected="selected" @endif>Inativo</option>
                        <option value="1" @if($collection->ativa == 1) selected="selected" @endif>Ativo</option>
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