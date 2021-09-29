@extends('layouts.app')

<style>
    #score-edit-table tr th,
    #score-edit-table tr td {
        vertical-align: inherit;
    }
</style>
@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col py-3 bg-gray-50">
            <h5 class="h5">スコア詳細</h5>
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['url' => route('scores.update', $game->id), 'method' => 'PATCH']) }}
                    <div class="row">
                        <div class="col-12">
                            <h5 class="h5">{{ $game->corse->name }}</h5>
                            <h5 class="h5">
                                {{ date('Y年m月d日', strtotime($game->date)) . '　' . $game->weather}}
                            </h5>
                            @foreach ($round as $halfType => $halfData)
                                @if (isset($halfData['owner']))
                                    <h5 class="h5 mt-3" id="first-half-course">
                                        {{ $halfData['course'] }}
                                    </h5>
                                    <table class="table text-center table-bordered" id="score-edit-table" style="max-width: 825px;">
                                        <tr class="bg-base-dark text-white">
                                            <th>Hole</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>{{ $halfType === 'first' ? '前半' : '後半' }}</th><th>合計</th>
                                        </tr>
                                        <tr>
                                            <td>Par</td>
                                            @foreach ($halfData['par'] as $key => $par)
                                                <td>{{ Form::text($halfData['owner']['score_id'] . '[par_' . $key . ']', $par, ['class' => 'form-control form-control-sm text-center']) }}</td>
                                            @endforeach
                                            <td>{{ $halfData['total']['par'] }}</td>
                                            <td>{{ $total['setting']['par'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Yard</td>
                                            @foreach ($halfData['yard'] as $key => $yard)
                                                <td>{{ Form::text($halfData['owner']['score_id'] . '[yard_' . $key . ']', $yard, ['class' => 'form-control form-control-sm text-center']) }}</td>
                                            @endforeach
                                            <td>{{ $halfData['total']['yard'] }}</td>
                                            <td>{{ $total['setting']['yard'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ $halfData['owner']['player_name'] . '(Score)' }}
                                            </td>
                                            @foreach ($halfData['owner']['score'] as $key => $score)
                                                <td style="font-weight: 700;">{{ Form::text($halfData['owner']['score_id'] . '[score_' . $key . ']', $score, ['class' => 'form-control form-control-sm text-center', 'style' => 'font-weight: 700;']) }}</td>
                                            @endforeach
                                            <td style="font-weight: 700;">{{ $halfData['owner']['total']['score'] }}</td>
                                            <td style="font-weight: 700;">{{ $total['owner']['score'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ $halfData['owner']['player_name'] . '(Putt)' }}
                                            </td>
                                            @foreach ($halfData['owner']['putter'] as $key => $putter)
                                                <td style="font-weight: 700;">{{ Form::text($halfData['owner']['score_id'] . '[putter_' . $key . ']', $putter, ['class' => 'form-control form-control-sm text-center', 'style' => 'font-weight: 700;']) }}</td>
                                            @endforeach
                                            <td style="font-weight: 700;">{{ $halfData['owner']['total']['putter'] }}</td>
                                            <td style="font-weight: 700;">{{ $total['owner']['putter'] }}</td>
                                        </tr>
                                        @foreach ($halfData['companion'] ?? [] as $id => $companion)
                                            <tr>
                                                @foreach ($companion as $key => $data)
                                                    @if ($key == 0 || $key == 10)
                                                        <td>{{ $data }}</td>
                                                    @elseif ($key < 10)
                                                        <td>{{ Form::text($id . '[score_' . $key . ']', $data, ['class' => 'form-control form-control-sm text-center', 'style' => 'font-weight: 700;']) }}</td>
                                                    @else
                                                        <td>{{ $total[$data] }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </table>
                                @else
                                    <h5 class="h5 mt-5" id="first-half-course">
                                        {{ $halfData['course'] }}
                                    </h5>
                                    <p>
                                        スコアデータがありません。
                                        <a href={{ route('scores.create') . '/' . $game->id . '/' . $halfType }}><i class="fas fa-plus-circle text-success fa-lg"></i></a>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-12">
                            <h5 class="h5 mt-3">memo</h5>
                            <div class="game-note-container" style="max-width: 825px;">
                                {{ Form::textarea('memo', $game->memo, ['class' => 'form-control form-control-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        {{ Form::submit('更新する', ['class' => 'btn btn-lg btn-success']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
