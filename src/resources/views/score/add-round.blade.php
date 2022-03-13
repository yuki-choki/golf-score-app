@extends('layouts.app')
<script src="{{ asset('js/cropper.js') }}" defer></script>
<script src="{{ asset('js/dragtable.js') }}" defer></script>
<script src="{{ asset('js/create.js') }}" defer></script>

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col pt-3 bg-gray-50">
            <h3 class="h3">ラウンド登録</h3>
            <div class="card py-3">
                {{ Form::open(['action' => 'ScoreController@storeRound' , 'method' => 'post', 'id' => 'score-save-form', 'class' => 'mb-0']) }}
                    <div class="d-flex mb-3">
                        <div class="col-sm-2 text-center" style="min-width: 125px;">コース名</div>
                        <div class="col-sm-10 p-0">{{ $course['name'] }}</div>
                    </div>
                    {{ Form::hidden('corse_id', $course['id']) }}
                    <div class="form-group form-inline">
                        <div class="col-sm-2" style="min-width: 125px;">{{ Form::label('date' ,'ラウンド日', ['class' => 'mr-sm-2']) }}</div>
                        {{ Form::date('date', '', ['class' => 'form-control text-center', 'required' => true]) }}
                    </div>
                    <div class="form-group form-inline">
                        <div class="col-sm-2" style="min-width: 125px;">{{ Form::label('weather' ,'天気', ['class' => 'mr-sm-2']) }}</div>
                        {{ Form::select('weather', $weathers, '', ['class' => 'form-control']) }}
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-sm-2 text-center" style="min-width: 125px;">{{ Form::label('memo' ,'メモ', ['class' => 'mr-sm-2']) }}</div>
                        <div class="col-sm-5 pl-0">{{ Form::textarea('memo', '', ['class' => 'form-control', 'rows' => '5']) }}</div>
                    </div>
                    <div class="form-group form-inline mb-0">
                        <div class="col-sm-2" style="min-width: 125px;"></div>
                        {{ Form::submit('登録', ['class' => 'btn btn-success']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
