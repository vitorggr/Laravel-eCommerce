@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.couriers.store') }}" method="post" class="form">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição </label>
                        <textarea name="description" id="description" rows="5" class="form-control" placeholder="">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="URL">URL</label>
                        <div class="input-group">
                            <span class="input-group-addon">http://</span>
                            <input type="text" name="url" id="url" placeholder="Link" class="form-control" value="{{ old('url') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="is_free">Frete grátis? </label>
                        <select name="is_free" id="is_free" class="form-control">
                            <option value="0">Não</option>
                            <option value="1" selected="selected">Sim</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: none" id="delivery_cost">
                        <label for="cost">Valor do frete <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">{{config('cart.currency')}}</span>
                            <input class="form-control" type="text" id="cost" name="cost" placeholder="{{config('cart.currency')}}" value="{{old('cost')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status </label>
                        <select name="status" id="status" class="form-control">
                            <option value="0">Inativo</option>
                            <option value="1">Ativo</option>
                        </select>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-default">Voltar</a>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
