
<link rel="stylesheet" type="text/css" href="css/cart.css">
<link rel="stylesheet" type="text/css" href="css/style.min.css">

    <!-- Main content -->
    <section class="container content" id="center">
        <div class="col-md-12">@include('layouts.errors-and-messages')</div>
            <div class="col-md-4 col-md-offset-4">
            <h1 class="section_title text-center">Login</h1>
            <form action="{{ route('login') }}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="login" class="section_subtitle">CPF/CNPJ</label>
                    <input type="text" id="login" name="Documento" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password" class="section_subtitle">Senha</label>
                    <input type="password" name="senha" id="senha" value="" class="form-control" placeholder="xxxxx">
                </div>
                <div class="row">
                    <button class="cart_total_button btn-block section-subtitle" type="submit">Entrar</button>
                </div>
            </form>
            <div class="row">
                <hr>
                <a href="#" class="col-md-12 text-center section-subtitle" >Esqueci minha senha</a>
                <a href="{{route('register')}}" class="col-md-12 text-center section-subtitle">Criar uma conta</a>
            </div>
        </div>
    </section>

<style>
    h2{
        text-align: center;
    }
    a{
        text-align: center;
    }
    #center{
        margin-top: 200px;
    }

</style>
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script type="text/javascript">
    var options = {
        onKeyPress: function(cpf, ev, el, op) {
            var masks = ['000.000.000-000', '00.000.000/0000-00'];
            $("input[name='Documento']").mask((cpf.length > 14) ? masks[1] : masks[0], op);
        }
    }
    $("input[name='Documento']").length > 11 ? $("input[name='Documento']").mask('00.000.000/0000-00', options) : $("input[name='Documento']").mask('000.000.000-00#', options);
</script>