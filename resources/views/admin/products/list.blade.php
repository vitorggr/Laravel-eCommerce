@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Produtos</h2>
                    @include('layouts.search', ['route' => route('admin.products.index')])
                    @include('admin.shared.products')
                    @if(!$products->isEmpty())
                    {{ $products->links() }}
                    @else
                    <p>Nenhum produto cadastrado</p> 
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
