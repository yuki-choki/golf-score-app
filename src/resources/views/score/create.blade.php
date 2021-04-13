@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col pt-3 bg-gray-50">
            <h3 class="h3">スコアカード読み込み</h3>
            <div class="card">
                {{ Form::open(['action' => 'ScoreController@store' , 'method' => 'post', 'id' => 'upload-form']) }}
                <div class="card-body pb-0">
                    <div>
                        {{ Form::hidden('corse_id', $corse['id']) }}
                    </div>
                    <dropzone-component
                        :corse="{{ json_encode($corse) }}"
                        :max-size="{{ json_encode((string)$maxSize) }}"
                    >
                    </dropzone-component>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
