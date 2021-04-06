<!-- =============================================== -->

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Administrador</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">HOME</li>
            <li><a href="{{ route('admin.dashboard') }}"> <i class="fa fa-home"></i> Home</a></li>
            <li class="header">SELL</li>
            <li class="treeview @if(request()->segment(2) == 'products' || request()->segment(2) == 'attributes' || request()->segment(2) == 'brands') active @endif">
                <a href="#">
                    <i class="fa fa-gift"></i> <span>Produtos</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.products.index') }}"><i class="fa fa-circle-o"></i> Lista de Produtos</a></li>
                    <li><a href="{{ route('admin.products.create') }}"><i class="fa fa-plus"></i> Criar Produto</a></li>
                    <li class="@if(request()->segment(2) == 'attributes') active @endif">
                        <a href="#">
                            <i class="fa fa-gear"></i> <span>Características</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.attributes.index') }}"><i class="fa fa-circle-o"></i> Lista de Características</a></li>
                            <li><a href="{{ route('admin.attributes.create') }}"><i class="fa fa-plus"></i> Criar Característica</a></li>
                        </ul>
                    </li>
                    <li class="@if(request()->segment(2) == 'brands') active @endif">
                        <a href="#">
                            <i class="fa fa-tag"></i> <span>Marcas</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.brands.index') }}"><i class="fa fa-circle-o"></i> Listar Marcas</a></li>
                            <li><a href="{{ route('admin.brands.create') }}"><i class="fa fa-plus"></i> Criar Marca</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'categories') active @endif">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Categorias</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.categories.index') }}"><i class="fa fa-circle-o"></i> Lista de Categorias</a></li>
                    <li><a href="{{ route('admin.categories.create') }}"><i class="fa fa-plus"></i> Criar Categoria</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'collections') active @endif">
                <a href="#">
                    <i class="fa fa-layer-group"></i> <span>Coleções</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.collections.index') }}"><i class="fa fa-circle-o"></i> Lista de Coleções</a></li>
                    <li><a href="{{ route('admin.collections.create') }}"><i class="fa fa-plus"></i> Criar Coleção</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'coupons') active @endif">
                <a href="#">
                    <i class="fa fa-ticket-alt"></i> <span>Cupons</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.coupons.index') }}"><i class="fa fa-circle-o"></i> Lista de Cupons</a></li>
                    <li><a href="{{ route('admin.coupons.create') }}"><i class="fa fa-plus"></i> Criar Cupom</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'promotions') active @endif">
                <a href="#">
                    <i class="fa fa-tags"></i> <span>Promoções</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.promotions.index') }}"><i class="fa fa-circle-o"></i> Lista de Promoções</a></li>
                    <li><a href="{{ route('admin.promotions.create') }}"><i class="fa fa-plus"></i> Criar Promoção</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'customers' || request()->segment(2) == 'addresses') active @endif">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Clientes</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.customers.index') }}"><i class="fa fa-circle-o"></i> Lista de Clientes</a></li>
                    <li><a href="{{ route('admin.customers.create') }}"><i class="fa fa-plus"></i> Criar Cliente</a></li>
                    <li class="@if(request()->segment(2) == 'addresses') active @endif">
                        <a href="#"><i class="fa fa-map-marker"></i> Endereços
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.addresses.index') }}"><i class="fa fa-circle-o"></i> Lista de Endereços</a></li>
                            <li><a href="{{ route('admin.addresses.create') }}"><i class="fa fa-plus"></i> Criar Endereço</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="header">PEDIDOS</li>
            <li class="treeview @if(request()->segment(2) == 'orders') active @endif">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Pedidos</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.orders.index') }}"><i class="fa fa-circle-o"></i> Lista de Pedidos</a></li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'order-statuses') active @endif">
                <a href="#">
                    <i class="fa fa-anchor"></i> <span>Status Pedidos</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.order-statuses.index') }}"><i class="fa fa-circle-o"></i> Listar Status Pedido</a></li>
                    <li><a href="{{ route('admin.order-statuses.create') }}"><i class="fa fa-plus"></i> Criar Status Pedido</a></li>
                </ul>
            </li>
            <li class="header">ENTREGA</li>
            <li class="treeview @if(request()->segment(2) == 'couriers') active @endif">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Transportadoras</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            <li class="header">CONFIG</li>

            <li class="treeview @if(request()->segment(2) == 'employees' || request()->segment(2) == 'roles' || request()->segment(2) == 'permissions') active @endif">
                <a href="#">
                    <i class="fa fa-star"></i> <span>Funcionários</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.employees.index') }}"><i class="fa fa-circle-o"></i> Listar Funcionários</a></li>
                    <li><a href="{{ route('admin.employees.create') }}"><i class="fa fa-plus"></i> Criar Funcionário</a></li>
                    <li class="@if(request()->segment(2) == 'roles') active @endif">
                        <a href="#">
                            <i class="fa fa-star-o"></i> <span>Regras</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.roles.index') }}"><i class="fa fa-circle-o"></i> Listar Regras</a></li>
                        </ul>
                    </li>
                    <li class="@if(request()->segment(2) == 'permissions') active @endif">
                        <a href="#">
                            <i class="fa fa-star-o"></i> <span>Permissões</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.permissions.index') }}"><i class="fa fa-circle-o"></i> Listar Permissões</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="treeview @if(request()->segment(2) == 'countries' || request()->segment(2) == 'provinces') active @endif">
                <a href="#">
                    <i class="fa fa-flag"></i> <span>Países</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.countries.index') }}"><i class="fa fa-circle-o"></i> Listar</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- =============================================== -->