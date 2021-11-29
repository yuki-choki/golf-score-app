@extends('layouts.app')
<script src="{{ asset('js/cropper.js') }}" defer></script>
<script src="{{ asset('js/dragtable.js') }}" defer></script>
<script src="{{ asset('js/create.js') }}" defer></script>

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col pt-3 bg-gray-50">
            <h3 class="h3" id="test_submit">スコアカード読み込み</h3>
            <div class="card">
                {{ Form::open(['action' => 'ScoreController@store' , 'method' => 'post', 'id' => 'score-save-form']) }}
                <div class="card-body pb-0">
                    <div>
                        {{ Form::hidden('game_id', $game['id']) }}
                        {{ Form::hidden('start_flag', $start_flag) }}
                    </div>

                    <div class="flex-container">
                        <div>
                            <dropzone-component
                                :corse="{{ json_encode($course) }}"
                                :max-size="{{ json_encode((string)$maxSize) }}"
                            >
                            </dropzone-component>
                        </div>
                        <div id="result_table" class='table-responsive'></div>
                    </div>

                </div>
                <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalTitle" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header py-2">
                                <h4 class="modal-title h4" id="previewModalTitle">読み込み範囲選択</h4>
                                <button type="button" class="py-2 hover:opacity-75" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fas fa-lg fa-times"></i></span>
                                </button>
                            </div>
                            <div class="modal-body px-5 py-0">
                                <div class="img-container">
                                    <div>
                                        <img id="image" src="">
                                    </div>
                                    <div class="mt-3" hidden>
                                        <canvas id="canvas" height="300" width="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer py-2">
                                <button type="button" class="btn btn-secondary" id="rotate">回転</button>
                                <button type="button" class="btn btn-primary" id="send-s3">読込開始</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
