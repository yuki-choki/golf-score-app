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
                {{ Form::open(['action' => 'ScoreController@store' , 'method' => 'post', 'id' => 'upload-form']) }}
                <div class="card-body pb-0">
                    <div>
                        {{ Form::hidden('corse_id', $corse['id']) }}
                    </div>
                    <upload-component
                        :corse="{{ json_encode($corse) }}"
                        :url="{{ json_encode(route('scores.store')) }}"
                    >
                    </upload-component>
                    <div class="col-12 my-3">
                        {{ Form::submit('読込開始', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
