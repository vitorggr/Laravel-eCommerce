<link rel="stylesheet" type="text/css" href="css/cart.css">
<link rel="stylesheet" type="text/css" href="css/style.min.css">


<section class="container content" id="center">
    <div class="col-md-12">@include('layouts.errors-and-messages')</div>
    <div class="col-md-4 col-md-offset-4">
        <h1 class="section_title text-center">Registrar</h1>
        <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                    <label for="name" class="section_subtitle">Nome</label>
                    <input type="text" id="email" name="Nome"  class="form-control" placeholder="" autofocus>
                    @if ($errors->has('nome'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nome') }}</strong>
                    </span>
                    @endif
                </div>


                <div class="form-group{{ $errors->has('Documento') ? ' has-error' : '' }}">
                    <label for="email" class="section_subtitle">CPF/CNPJ</label>
                    <input id="email" type="text" class="form-control" name="Documento" value="{{ old('login') }}">

                    @if ($errors->has('Documento'))
                    <span class="help-block">
                        <strong>{{ $errors->first('Documento') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('senha') ? ' has-error' : '' }}">
                    <label for="password" class="section_subtitle">Senha</label>
                    <input id="password" type="password" class="form-control" name="senha">

                    @if ($errors->has('senha'))
                    <span class="help-block">
                        <strong>{{ $errors->first('senha') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="row">
                    <button class="cart_total_button btn-block section-subtitle" type="submit">Entrar</button>
                </div>

            </form>
        </div>
    </div>
</section>
<style>
    h2 {
        text-align: center;
    }

    a {
        text-align: center;
    }

    #center {
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