<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{!! csrf_token() !!}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{!! mix('css/app.css') !!}">

        <style>
            html {
                height: 100%;
            }
            body {
                background-image: linear-gradient(to right top, #5e0836, #6d004c, #74006b, #6e0090, #4a07bc);
                height: 100%;
                margin: 0;
            }
            .jumbotron {
                background-color: transparent;
            }
            .text-jumbotron {
                color: #ffcdcd;
            }
            .text-jumbotron-muted {
                color: #def0ff !important;
            }
            label {
                color: #FFF;
                float: left;
            }
        </style>
    </head>
    <body>

    <div id="app">

        <header>
            <div class="collapse bg-dark" id="navbarHeader">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-md-7 py-4">
                            <h4 class="text-white">Sobre</h4>
                            <p class="text-muted">
                                O projeto tem como objetivo ficar analisando a mudança dos valores em lojas específicas. <br />
                                <span class="text-info">
                                    Andrew Rodrigues Brunoro < andrewrbrunoro@gmail.com >
                                </span>
                            </p>
                        </div>
                        <div class="col-sm-4 offset-md-1 py-4">
                            <h4 class="text-white">
                                Contatos / Dúvidas
                            </h4>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="#" class="text-white">
                                        Facebook
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar navbar-dark bg-dark shadow-sm">
                <div class="container d-flex justify-content-between">
                    <a href="#" class="navbar-brand d-flex align-items-center">
                        <strong>PriceObserver</strong>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </header>

        <main role="main">

            <section class="jumbotron text-center">
                <div class="container">
                    <h1 class="jumbotron-heading text-jumbotron">
                        Cadastre o produto abaixo para ficar analisando
                    </h1>

                    <p class="lead text-jumbotron-muted">
                        Na primeira leitura do produto será definido seu valor base. <br>
                        Os valores a serem diferenciados serão baseados no valor base.
                    </p>

                    <hr>

                    <form action="{!! route('product.store') !!}" method="post">
                        {!! csrf_field() !!}
                        <div class="col-lg-12 col-md-12">

                            @if (session()->has('error'))
                                <div class="alert alert-danger" role="alert">
                                    {!! session('error') !!}
                                </div>
                            @elseif(session()->has('success'))
                                <div class="alert alert-success" role="alert">
                                    {!! session('success') !!}
                                </div>
                            @endif

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-group{!! $errors->has('url') ? ' has-error' : '' !!}">
                                        <label for="url" class="pull-left">
                                            Link do produto
                                        </label>
                                        <input type="text" name="url" class="form-control{!! $errors->has('url') ? ' has-error' : '' !!}" placeholder="http://endereço" required value="{!! old('url') !!}" />
                                        {!! $errors->first('url', '<span class="text-info float-left">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group{!! $errors->has('total_off') ? ' has-error' : '' !!}">
                                        <label for="total_off">
                                            Desconto em valor
                                        </label>
                                        <input type="text" name="total_off" class="form-control money" placeholder="0,00" value="{!! old('total_off') !!}" />
                                        {!! $errors->first('total_off', '<span class="text-info float-left">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group{!! $errors->has('percent_off') ? ' has-error' : '' !!}">
                                        <label for="percent_off">
                                            Desconto em porcentagem (%)
                                        </label>
                                        <input type="text" name="percent_off" class="form-control percent" maxlength="5" placeholder="0" value="{!! old('percent_off') !!}" />
                                        {!! $errors->first('percent_off', '<span class="text-info float-left">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group{!! $errors->has('job') ? ' has-error' : '' !!}">
                                        <label for="job">
                                            Loja do produto
                                        </label>
                                        <select name="job" id="job" class="form-control">
                                            @foreach($shops as $job => $name)
                                                <option value="{!! $job !!}" {!! old('shop') === $job ? 'selected' : '' !!}>
                                                    {!! $name !!}
                                                </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('shop', '<span class="text-info float-left">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <button class="btn btn-submit btn-outline-warning float-left">
                                        Salvar
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
            </section>

            <div class="album py-5 bg-light">
                <div class="container">

                    <div class="row">
                        
                        @foreach($products as $product)
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm">
                                    <img class="card-img-top" data-src="holder.js/100px225?theme=thumb&amp;bg=55595c&amp;fg=eceeef&amp;text=Thumbnail" alt="Thumbnail [100%x225]" style="height: 225px; width: 100%; display: block;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22349%22%20height%3D%22225%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20349%20225%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_167348688f8%20text%20%7B%20fill%3A%23eceeef%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A17pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_167348688f8%22%3E%3Crect%20width%3D%22349%22%20height%3D%22225%22%20fill%3D%22%2355595c%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22117.21875%22%20y%3D%22120.253125%22%3EThumbnail%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
                                    <div class="card-body">
                                        <p class="card-text">
                                            {!! !$product->name ? "<a href='{$product->url}' target='_blank'>{$product->url}</a>" : $product->name !!}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                            </div>
                                            <small class="text-muted">
                                                {!! $product->times_read !!} {!! $product->times_read > 1 ? 'verificações' : ($product->times_read === 0 ? 'verificações' : 'verificação') !!}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="{!! mix('js/app.js') !!}"></script>
    </body>
</html>
