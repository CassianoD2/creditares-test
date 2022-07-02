<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>

    </head>
    <body>
        <nav class="navbar bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Creditares Teste</a>
            </div>
        </nav>

        <div class="container">
            <div class="card mt-2">
                <div class="card-header">
                    Cultura: {{$cultura->nome}}
                    <a href="{{route('culturas')}}" class="btn btn-warning">Voltar</a>
                </div>
                <div class="card-body">
                    <form method="GET">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <select class="form-select" aria-label="Selecione o ano de pesquisa" name="ano">
                                    @for($i= 2010; $i <= \Carbon\Carbon::now()->year; $i++)
                                        <option value="{{$i}}" @if($anoSelecionado == $i) selected @endif> {{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-3">
                                <select class="form-select" aria-label="Selecione o mes de pesquisa" name="mes">
                                    @foreach($meses as $valor => $mes)
                                        <option value="{{$valor}}" @if($mesSelecionado == $valor) selected @endif>{{$mes}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <select class="form-select" aria-label="Selecione a Unidade" name="unidade">
                                    @foreach($unidades as $unidade)
                                        <option value="{{$unidade->id}}" @if($unidadeSelecionada == $unidade->id) selected @endif>{{$unidade->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
                            </div>
                        </div>

                        <div class="row mt-2 mb-2">
                            <div class="col-12">
                                Maior Preço: {{ $max }} </br>
                                Menor Preço: {{ $min }} </br>
                                Preço Médio: {{ $avg }} </br>
                            </div>
                        </div>
                    </form>
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($precos as $preco)
                                <tr>
                                    <td>{{$preco->data_preco}}</td>
                                    <td>{{$preco->preco}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Sem Dados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </body>
</html>
