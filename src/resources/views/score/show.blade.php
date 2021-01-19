@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    スコア詳細
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <h3>{{ $game->corse->name }}</h3>
                            <h5>
                                {{ date('Y年m月d日', strtotime($game->date)) . '　' . $game->weather}}
                                <span class="float-right"><a href={{ action('ScoreController@edit', $game->id) }}><i class="fas fa-edit text-success"></i></a></span>
                            </h5>
                            <table class="table text-center table-bordered lead">
                                <tr>
                                    <th class="bg-light" width="10%">ホール</th>
                                    <td width="14%">Par / Yard</td>
                                    @foreach ($names as $name)
                                        <td width="14%">{{ $name }}</td>
                                    @endforeach
                                </tr>
                                @foreach ($rows as $row_num => $row)
                                    <tr>
                                        @foreach ($row as $key => $val)
                                            @if ($key === 4)
                                                <th class="bg-light" width="10%">
                                                    @switch($row_num)
                                                        @case('b_half')
                                                            前半
                                                            @break
                                                        @case('a_half')
                                                            後半
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
                                                            <span class="mr-1">{{ $val[array_key_first($val)]['score'] }}</span><span>({{ $val[array_key_first($val)]['putter'] }})</span>
                                                        </td>
                                                    @else
                                                        <td class="bg-light" width="14%">{{ $val }}</td>
                                                    @endif
                                                    @break
                                                @default
                                                    @if (is_array($val))
                                                        <td width="14%">
                                                            <span class="mr-1">{{ $val[array_key_first($val)]['score'] }}</span><span>({{ $val[array_key_first($val)]['putter'] }})</span>
                                                        </td>
                                                    @else
                                                        <td width="14%">{{ $val }}</td>
                                                    @endif
                                            @endswitch
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="col-md-5 pl-0">
                            <h3>&nbsp;</h3>
                            <h5>memo</h5>
                            <div class="card game-note-container">
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
