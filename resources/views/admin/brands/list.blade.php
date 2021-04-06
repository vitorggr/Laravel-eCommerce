@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(!$brands->isEmpty())
            <div class="box">
                <div class="box-body">
                    <h2>Marcas</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Nome</td>
                                <td>Ação</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.brands.show', $brand->id) }}">{{ $brand->name }}</a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                            <button onclick="return confirm('Continuar ?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Deletar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $brands->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">Nenhuma marca criada. <a href="{{ route('admin.brands.create') }}">Criar uma!</a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
