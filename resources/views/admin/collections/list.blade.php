@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($collections)
            <div class="box">
                <div class="box-body">
                    <h2>Coleções</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-3">Nome</td>
                                <td class="col-md-3">Foto Banner</td>
                                <td class="col-md-3">Status</td>
                                <td class="col-md-3">Ação</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($collections as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.collections.show', $item->id) }}">{{ $item->descricao }}</a></td>
                                <td>
                                    @if(isset($item->banner))
                                        <img src="{{ asset("storage/$item->banner") }}" alt="" class="img-responsive">
                                    @endif
                                </td>
                                <td>@include('layouts.status', ['status' => $item->status])</td>
                                <td>
                                    <form action="{{ route('admin.collections.destroy', $item->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.collections.edit', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                            <button onclick="return confirm('Deseja deletar essa categoria?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Deletar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $collections->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
