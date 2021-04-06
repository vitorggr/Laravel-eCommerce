@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($couriers)
            <div class="box">
                <div class="box-body">
                    <h2> <i class="fa fa-truck"></i> Transportadoras</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-2">Nome</td>
                                <td class="col-md-2">Descrição</td>
                                <td class="col-md-2">URL</td>
                                <td class="col-md-1">Frete grátis ?</td>
                                <td class="col-md-1">Valor</td>
                                <td class="col-md-1">Status</td>
                                <td class="col-md-3">Ação</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($couriers as $courier)
                            <tr>
                                <td>{{ $courier->name }}</td>
                                <td>{{ str_limit($courier->description, 100, ' ...') }}</td>
                                <td>{{ $courier->url }}</td>
                                <td>
                                    @include('layouts.status', ['status' => $courier->is_free])
                                </td>
                                <td>
                                    R$ {{ $courier->cost }}
                                </td>
                                <td>@include('layouts.status', ['status' => $courier->status])</td>
                                <td>
                                    <form action="{{ route('admin.couriers.destroy', $courier->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.couriers.edit', $courier->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                            <button onclick="return confirm('Continuar ?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Deletar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection