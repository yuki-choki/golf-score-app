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
                <div class="card my-3">
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
            @endif
        </div>
    </div>
</div>
@endsection
