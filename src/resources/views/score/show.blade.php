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
                    <h3 class="pl-3">{{ $game->corse->name }}</h3>
                    <h5 class="pl-3">{{ date('Y年m月d日', strtotime($game->date)) }}</h5>
                        <div class="col-md-7">
                        <table class="table text-center table-bordered lead">
                            <tr>
                                <th class="p-2 bg-light" width="10%">ホール</th>
                                <td class="p-2" width="14%">Par / Yard</td>
                                @foreach ($names as $name)
                                    <td class="p-2" width="14%">{{ $name }}</td>
                                @endforeach
                            </tr>
                            @foreach ($rows as $row_num => $row)
                                <tr>
                                    @foreach ($row as $key => $val)
                                        @if ($key === 0)
                                            <th class="p-2 bg-light" width="10%">
                                                @switch($row_num)
                                                    @case(10)
                                                        前半
                                                        @break
                                                    @case(20)
                                                        後半
                                                        @break
                                                    @case(21)
                                                        Total
                                                        @break
                                                    @default
                                                        @if ($row_num > 10)
                                                            {{ $row_num - 1 }}
                                                        @else
                                                            {{ $row_num }}
                                                        @endif
                                                @endswitch
                                            </th>
                                        @endif
                                        @switch($row_num)
                                            @case(10)
                                            @case(20)
                                            @case(21)
                                                <td class="p-2 bg-light" width="14%">{{ $val }}</td>
                                                @break
                                            @default
                                                <td class="p-2" width="14%">{{ $val }}</td>
                                        @endswitch
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
