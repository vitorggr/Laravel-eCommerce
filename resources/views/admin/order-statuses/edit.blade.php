@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        @include('layouts.errors-and-messages')
        <!-- Default box -->
        <div class="box">
            <form action="{{ route('admin.order-statuses.update', $orderStatus->id) }}" method="post">
            <div class="box-body">
                <h2>Status de Pedidos</h2>
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ $orderStatus->name ?: old('name') }}" placeholder="Nome">
                </div>
                <div class="form-group">
                    <label for="color">Cor</label>
                    <input class="form-control jscolor {hash:true}" type="text" name="color" id="color" value="{{ $orderStatus->color ?: old('color') }}">
                </div>
            </div>
            <!-- /.box-body -->
                <div class="box-footer btn-group">
                    <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-default">Voltar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection
@section('js')
    <script src="{{ asset('js/jscolor.min.js') }}" type="text/javascript"></script>
@endsection
