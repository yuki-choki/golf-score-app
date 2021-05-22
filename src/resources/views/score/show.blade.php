@extends('layouts.app')

<style>
    #score-show-table tr th,
    #score-show-table tr td {
        vertical-align: inherit;
    }
</style>
@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col py-3 bg-gray-50">
            <h3 class="h3">スコア詳細</h3>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="h5">{{ $game->corse->name }}</h5>
                            <h5 class="h5">
                                {{ date('Y年m月d日', strtotime($game->date)) . '　' . $game->weather}}
                                <span class="ml-3"><a href={{ action('ScoreController@edit', $game->id) }}><i class="fas fa-edit text-success"></i></a></span>
                            </h5>
                            @foreach ($round as $halfType => $halfData)
                                <h5 class="h5 mt-3" id="first-half-course">
                                    {{ $halfData['course'] }}
                                </h5>
                                <table class="table text-center table-bordered" id="score-show-table" style="max-width: 800px;">
                                    <tr class="bg-base-dark text-white">
                                        <th>Hole</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>{{ $halfType === 'first' ? '前半' : '後半' }}</th><th>合計</th>
                                    </tr>
                                    <tr>
                                        <td>Par</td>
                                        @foreach ($halfData['par'] as $par)
                                            <td>{{ $par }}</td>
                                        @endforeach
                                        <td>{{ $halfData['total']['par'] }}</td>
                                        <td>{{ $total['setting']['par'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Yard</td>
                                        @foreach ($halfData['yard'] as $yard)
                                            <td>{{ $yard }}</td>
                                        @endforeach
                                        <td>{{ $halfData['total']['yard'] }}</td>
                                        <td>{{ $total['setting']['yard'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 700;">
                                            {{ $halfData['owner']['player_name'] . '(Score)' }}
                                        </td>
                                        @foreach ($halfData['owner']['score'] as $score)
                                            <td style="font-weight: 700;">{{ $score }}</td>
                                        @endforeach
                                        <td style="font-weight: 700;">{{ $halfData['owner']['total']['score'] }}</td>
                                        <td style="font-weight: 700;">{{ $total['owner']['score'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 700;">
                                            {{ $halfData['owner']['player_name'] . '(Putt)' }}
                                        </td>
                                        @foreach ($halfData['owner']['putter'] as $putter)
                                            <td style="font-weight: 700;">{{ $putter }}</td>
                                        @endforeach
                                        <td style="font-weight: 700;">{{ $halfData['owner']['total']['putter'] }}</td>
                                        <td style="font-weight: 700;">{{ $total['owner']['putter'] }}</td>
                                    </tr>
                                    @foreach ($halfData['companion'] as $id => $companion)
                                        <tr>
                                            @foreach ($companion as $key => $data)
                                                @if ($key < 11)
                                                    <td style="font-weight: 700;">{{ $data }}</td>
                                                @else
                                                    <td style="font-weight: 700;">{{ $total[$data] }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>
                            @endforeach
                        </div>
                        <div class="col-12">
                            <h5 class="h5 mt-3">memo</h5>
                            <div class="card game-note-container"  style="max-width: 800px;">
                                <div class="card-body">
                                    {!! nl2br(e($game->memo)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
