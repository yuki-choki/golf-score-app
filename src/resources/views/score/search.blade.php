@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    ゴルフ場検索
                </div>
                <div class="card-body">
                    {{ Form::open() }}
                    <div class="row">
                        <div class="col-md-5">
                            {{ Form::label('name', 'ゴルフ場名', ['class' => 'mb-0']) }}
                            {{ Form::text('name', $params['name'] ?? '', ['class' => 'form-control']) }}
                        </div>
                        <div class="col-md-5">
                            {{ Form::label('pref_code', '都道府県', ['class' => 'mb-0']) }}
                            {{ Form::select('pref_code', config('pref'), $params['pref_code'] ?? '0', ['class' => 'form-control']) }}
                        </div>
                        <div class="col-md-2">
                            {{ Form::submit('検索', ['class' => 'btn btn-primary mt-4']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                    @if ($request->method() === 'POST')
                        @if (count($searchData) > 0)
                            <div class="corse-result mt-3">
                                {{ Form::open(['action' => 'ScoreController@create']) }}
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="40%">コース名</th>
                                            <th scope="col" width="45%">住所</th>
                                            <th scope="col" class="action" width="15%">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($searchData as $corse)
                                    <tr>
                                        <td>{{ $corse['name'] }}</td>
                                        <td>{{ $corse['address'] }}</td>
                                        <td>
                                            {{ Form::hidden('pref_id', $corse['id']) }}
                                            {{ Form::submit('選択', ['class' => 'btn btn-sm btn-success']) }}
                                        </td>
                                    </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ Form::close() }}
                            </div>
                        @else
                           <div class="mt-3">検索条件に該当するゴルフコースはありません</div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
