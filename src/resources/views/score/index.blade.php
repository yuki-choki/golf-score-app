@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-10 mt-3">
            <div class="card">
                <div class="card-header">
                    スコア一覧
                </div>
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
                                        {{ Form::submit('選択', ['class' => 'btn btn-sm btn-primary']) }}
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
</div>
@endsection
