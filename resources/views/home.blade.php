@extends('template')

@section('content')
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
                <show-case></show-case>
            </div>
        </div>

    </main>
@endsection