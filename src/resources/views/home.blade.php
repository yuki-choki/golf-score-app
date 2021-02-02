@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('components.main.sidebar')
            <div class="col-md-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        ラウンド記録
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" style="margin-left: 100px">
                            <div class="col-md-2">
                                <div><h5>平均スコア</h5></div>
                                <div><h3 class="pl-3">{{ $average['score'] }}</h3></div>
                            </div>
                            <div class="col-md-2">
                                <div><h5>平均パット</h5></div>
                                <div><h3 class="pl-3">{{ $average['putter'] }}</h3></div>
                            </div>
                            <div class="col-md-3">
                                <div><h5>ベストスコア</h5></div>
                                <div><h3 class="pl-3">{{ $record['best'] }}</h3></div>
                            </div>
                            <div class="col-md-3    ">
                                <div><h5>ワーストスコア</h5></div>
                                <div><h3 class="pl-3">{{ $record['worst'] }}</h3></div>
                            </div>
                        </div>
                        <div class="col-12" style="height: 300px">
                            @if (isset($chart_data[1]))
                                <div id="target" class="col-12"></div>
                            @else
                                <div class="text-center pt-5">
                                    <h3 class="pt-5">ラウンド記録がありません</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
        var data = google.visualization.arrayToDataTable(@json($chart_data));

        var view = new google.visualization.DataView(data);
        view.setColumns([
            0,
            1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2,
            {
                calc: "stringify",
                sourceColumn: 2,
                type: "string",
                role: "annotation"
            },
        ]);

        var options = {
            height: 300,
            colors: ['green', 'lightblue'],
            vAxis:{ textPosition: 'none' },
            hAxis: {
                viewWindow: { max: 7 },
            },
            legend: { position: 'none' }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("target"));
        chart.draw(view, options);
    }
    // 画面サイズに合わせてチャートを表示するため
    window.onresize = function () {
        drawChart();
    }
    </script>
@endsection