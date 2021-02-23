@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-10 pt-3 bg-gray-50">
            <h3 class="h3">スコア分析</h3>
            {{ Form::open(['url' => route('scores.analysis.post')]) }}
            {{ Form::label('corse_id' ,'ゴルフ場', ['class' => 'mb-0']) }}
            {{ Form::select('corse_id', $corse_lists, $params['corse_id'] ?? '', ['class' => 'form-control mb-3 w-50']) }}
            {{ Form::label('date_from' ,'ラウンド日', ['class' => 'mb-0']) }}
            <div class="row">
                <div class="col-3">{{ Form::date('date_from', $params['date_from'] ?? '', ['class'=>'form-control']) }}</div>
                <div class="h-full my-auto">〜</div>
                <div class="col-3">{{ Form::date('date_to', $params['date_to'] ?? '', ['class'=>'form-control']) }}</div>
                <div class="col-2 pl-0">
                    {{ Form::submit('絞り込み', ['class' => 'btn btn-primary']) }}
                </div>
            </div>
            {{ Form::close() }}
            @if ($games)
                @if ($games->count() > 0)
                    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                        <li class="nav-item w-50 text-center">
                            <a class="nav-link active border hover:text-current" id="analysis-tab" data-toggle="tab" href="#analysis" role="tab" aria-controls="analysis" aria-selected="false">グラフ</a>
                        </li>
                        <li class="nav-item w-50 text-center">
                            <a class="nav-link border hover:text-current" id="table-tab" data-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="true">基本データ</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="analysis" role="tabpanel" aria-labelledby="analysis-tab">
                            <div class="card mb-3 border-top-0 rounded-0">
                                <div class="card-body">
                                    <div class="anasysis row">
                                        <div class="col-md-6">
                                            <div id="score-donutchart" style="width=500px; height: 300px;"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="putter-donutchart" style="width=500px; height: 300px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="table" role="tabpanel" aria-labelledby="table-tab">
                            <div class="card mb-3 border-top-0 rounded-0">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ラウンド日</th>
                                                <th>コース名</th>
                                                <th>スコア</th>
                                                <th>パット</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($games as $game)
                                                <tr>
                                                    <td>{{ $game->date }}</td>
                                                    <td>{{ $game->corse->name }}</td>
                                                    <td>{{ $game->total_score }}</td>
                                                    <td>{{ $game->total_putter }}</td>
                                                    <td>
                                                        {{ Form::open(['url' => route('scores.show', ['score' => $game->id]), 'method' => 'GET']) }}
                                                        {{ Form::submit('詳細', ['class' => 'btn btn-sm btn-success']) }}
                                                        {{ Form::close() }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <h5 class="h5 mt-3">
                        絞り込み条件に該当する記録がありません。
                    </h5>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
@if ($games && $games->count() > 0)
    @section('script')
        <script>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChartScore);
            google.charts.setOnLoadCallback(drawChartPutter);
            function drawChartScore() {
                let scoreData = google.visualization.arrayToDataTable([
                    ['title', 'スコア'],
                    ['イーグル', {{ json_encode($scores['eagle']) }}],
                    ['バーディ', {{ json_encode($scores['birdie']) }}],
                    ['パー', {{ json_encode($scores['par']) }}],
                    ['ボギー', {{ json_encode($scores['bogey']) }}],
                    ['ダブルボギー', {{ json_encode($scores['d_bogey']) }}],
                    ['その他', {{ json_encode($scores['other']) }}]
                ]);

                let scoreOptions = {
                    title: 'My Daily Activities',
                    pieHole: 0.5,
                    chartArea:{left: 10, top: 10, bottom: 10 ,width: '100%',height: '100%'},
                    tooltip: {trigger: 'selection'},
                };

                let scoreChart = new google.visualization.PieChart(document.getElementById('score-donutchart'));
                scoreChart.draw(scoreData, scoreOptions);
            }

            function drawChartPutter() {
                let PutterData = google.visualization.arrayToDataTable([
                    ['title', 'パット数'],
                    ['0パット', {{ json_encode($putters['zero_put']) }}],
                    ['1パット', {{ json_encode($putters['one_put']) }}],
                    ['2パット', {{ json_encode($putters['two_put']) }}],
                    ['3パット以上', {{ json_encode($putters['other']) }}]
                ]);

                let putterOptions = {
                    title: 'パット数',
                    pieHole: 0.5,
                    chartArea: {left: 10, top: 10, bottom: 10, width: '100%', height:' 100%'},
                    tooltip: {trigger: 'selection'},
                };

                let putterChart = new google.visualization.PieChart(document.getElementById('putter-donutchart'));
                putterChart.draw(PutterData, putterOptions);
            }

            // 画面サイズに合わせてチャートを表示するため
            window.onresize = function () {
                drawChartScore();
                drawChartPutter();
            }
        </script>
    @endsection
@endif
