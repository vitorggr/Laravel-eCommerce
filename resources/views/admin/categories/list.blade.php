@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($categories)
            <div class="box">
                <div class="box-body">
                    <h2>Categorias</h2>
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
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.categories.show', $category->id) }}">{{ $category->descricao }}</a></td>
                                <td>
                                    @if(isset($category->banner))
                                        <img src="{{ asset("storage/$category->banner") }}" alt="" class="img-responsive">
                                    @endif
                                </td>
                                <td>@include('layouts.status', ['status' => $category->status])</td>
                                <td>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                            <button onclick="return confirm('Deseja deletar essa categoria?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Deletar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $categories->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
