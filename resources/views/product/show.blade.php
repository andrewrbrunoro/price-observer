@extends('template')

@section('content')
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">
            PriceObserver
        </a>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="{!! route('home') !!}">
                    Voltar
                </a>
            </li>
        </ul>
    </nav>

    <main role="main" class="col-lg-12" style="padding-top: 20px">

        <h4 class="text-white">
            {!! !$product->name ? "<a href='{$product->url}' class='text-white' target='_blank'>{$product->url}</a>" : $product->name !!}
        </h4>

        <div class="row" style="padding-left: 20px">
            <div class="col-lg-8 col-md-8 bg-white">
                <canvas id="myChart" width="400"></canvas>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered bg-white table-sm">
                        <thead>
                        <tr>
                            <th>Preço</th>
                            <th>Promoção</th>
                            <td>Alertou?</td>
                            <th>Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($product->PriceHistories->count())
                            @foreach($product->PriceHistories as $history)
                                <tr>
                                    <td>R$ {!! bco_to_coin($history->price) !!}</td>
                                    <td>R$ {!! bco_to_coin($history->sale) !!}</td>
                                    <td>{!! $history->alert === 1 ? "Sim" : "Não" !!}</td>
                                    <td>{!! $history->created_at->format('d/m/Y H:i:s') !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">Nenhum histórico salvo no momento</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('script')
    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    "Janeiro","Fevereiro","Março","Abril","Maio","Junho",
                    "Julho","Agosto","Setembro","Outubro","Novembro", "Dezembro"
                ],
                datasets: [
                    {
                        label: "Preço",
                        backgroundColor: "rgba(30,144,255,0.5)",
                        strokeColor: "rgba(30,144,255,0.5)",
                        pointColor: "rgba(30,144,255,0.5)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: {!! $max !!}
                    },
                    {
                        label: "Promoção",
                        backgroundColor: "rgba(255,255,0,0.5)",
                        strokeColor: "rgba(255,255,0,1)",
                        pointColor: "rgba(255,255,0,0.5)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(255,255,0,1)",
                        data: {!! $min !!}
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                bezierCurve: true,
                bezierCurveTension: 0.4,
                pointDot: true,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                responsive: true,
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ' : R$ ' + (tooltipItem.yLabel.toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                        }
                    }
                }
            }
        });
    </script>
@endsection