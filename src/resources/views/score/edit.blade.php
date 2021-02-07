@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-10 py-3 bg-gray-50">
            <h5 class="h5">スコア詳細</h5>
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['url' => route('scores.update', $game->id), 'method' => 'PATCH']) }}
                    <div class="row">
                        <div class="col-md-7">
                            <h5 class="h5">{{ $game->corse->name }}</h5>
                            <h5 class="h5">
                                {{ date('Y年m月d日', strtotime($game->date)) . '　' . $game->weather}}
                            </h5>
                            <table class="table text-center table-bordered lead">
                                <tr>
                                    <th class="bg-light" width="10%">Hole</th>
                                    <td width="14%">Par / Yard</td>
                                    @foreach ($names as $key => $name)
                                        <td width="14%">
                                            {{ Form::text('player_name['. $key . '][player_name]', $name, ['class' => 'form-control form-control-sm']) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @foreach ($rows as $row_num => $row)
                                    <tr>
                                        @foreach ($row as $key => $val)
                                            @if ($key == '4')
                                                <th class="bg-light" width="10%">
                                                    @switch($row_num)
                                                        @case('b_half')
                                                            Out
                                                            @break
                                                        @case('a_half')
                                                            In
                                                            @break
                                                        @case('total')
                                                            Total
                                                            @break
                                                        @default
                                                            {{ $row_num }}
                                                    @endswitch
                                                </th>
                                            @endif
                                            @switch($row_num)
                                                @case('b_half')
                                                @case('a_half')
                                                @case('total')
                                                    @if (is_array($val))
                                                        <td class="bg-light" width="14%">
                                                            <span>{{ $val[array_key_first($val)]['score'] }}</span><span>({{ $val[array_key_first($val)]['putter'] }})</span>
                                                        </td>
                                                    @else
                                                        <td class="bg-light" width="14%">{{ $val }}</td>
                                                    @endif
                                                    @break
                                                @default
                                                    @if (is_array($val))
                                                        <td width="14%">
                                                            <div class="d-flex">
                                                                <span>{{ Form::number('score['. $row_num . '][' . array_key_first($val) . '][score]', $val[array_key_first($val)]['score'], ['class' => 'form-control form-control-sm', 'min' => 0]) }}</span>
                                                                <span>{{ Form::number('score['. $row_num . '][' . array_key_last($val) . '][putter]', $val[array_key_first($val)]['putter'], ['class' => 'form-control form-control-sm', 'min' => 0]) }}</span>
                                                            </div>
                                                        </td>
                                                    @else
                                                        @if ($key == '4')
                                                            <td width="14%">{{ $val }}</td>
                                                        @else
                                                            <td width="14%">{{ Form::number('score['. $row_num . ']['. $key . ']', $val, ['class' => 'form-control form-control-sm', 'min' => 0]) }}</td>
                                                        @endif
                                                    @endif
                                            @endswitch
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="col-md-5 pl-0">
                            <h5 class="h5">&nbsp;</h5>
                            <h5 class="h5">memo</h5>
                            <div class="game-note-container">
                                {{ Form::textarea('memo', $game->memo, ['class' => 'form-control form-control-sm']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {{ Form::submit('更新', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
