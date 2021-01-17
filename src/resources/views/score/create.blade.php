@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    スコアカード読み込み
                </div>
                {{ Form::open(['action' => 'ScoreController@store' , 'method' => 'post', 'files' => true]) }}
                <div class="card-body">
                    <div>
                        {{ Form::hidden('corse_id', $corse['id']) }}
                        <h5 class="pb-1 border-rgba(0, 0, 0, 0.125) border-bottom">コース名：{{ $corse['name'] }}</h5>
                    </div>
                    {{ Form::label('読み込みファイル') }}
                    <div class="form-group">
                        {{ Form::file('score_card', ['id' => 'score_card_data']) }}
                    </div>
                    <div>
                        {{ Form::submit('読込開始', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
