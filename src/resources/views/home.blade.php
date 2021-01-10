@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    最近のラウンド
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px">
                        グラフ・チャート
                    </div>
                    <div class="score-container" style="height: 100px">
                        平均スコア・平均パット
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
