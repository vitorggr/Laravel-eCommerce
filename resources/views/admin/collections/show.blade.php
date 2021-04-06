@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($collection)
            <div class="box">
                <div class="box-body">
                    <h2>Collection</h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="col-md-4">Name</td>
                            <td class="col-md-4">Description</td>
                            <td class="col-md-4">Cover</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $collection->nome }}</td>
                                <td>{{ $collection->descricao }}</td>
                                <td>
                                    @if(isset($collection->cover))
                                        <img src="{{asset("storage/$collection->cover")}}" alt="Collection image" class="img-thumbnail">
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if(!$collections->isEmpty())
                <hr>
                    <div class="box-body">
                        <h2>Produtos</h2>
                        <table class="table">
                            <thead>
                            <tr>
                                <td class="col-md-3">Nome</td>
                                <td class="col-md-3">Descricao</td>
                                <td class="col-md-3">Codigo</td>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $item)
                                    <tr>
                                        <td><a href="{{route('admin.products.show', $item->id)}}">{{ $item->descricao }}</a></td>
                                        <td>{{ $item->descricao }}</td>
                                        <td>{{ $item->codigo }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.collections.index') }}" class="btn btn-default btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
