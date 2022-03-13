@extends('layouts.app')
<script src="{{ asset('js/user/friend.js') }}" defer></script>

@section('content')
    <div class="container">
        <div class="row">
            @include('components.main.sidebar')
            <div class="col pt-3 bg-gray-50">
                <h3 class="h3">友達一覧</h3>
                <div class="card lead" style="width: 300px;">
                    <div class="card-body text-center pb-0" id="friends-container">
                        @foreach ($friends as $friend)
                            <div class="friend-container" id="{{ $friend->id }}">
                                <p class="friend-box mb-3 border-b-2">
                                    <span class="friend-name">{{ $friend->name }}</span>
                                    <span class="float-right text-danger ml-1 friend-trash" style="height: 29px;">
                                        <i class="fas fa-trash cursor-pointer fa-sm" style="line-height: 29px;"></i>
                                    </span>
                                    <span class="float-right text-success friend-edit" style="height: 29px;">
                                        <i class="fas fa-edit cursor-pointer fa-sm" style="line-height: 29px;"></i>
                                    </span>
                                </p>
                                <p class="d-none friend-input mb-3">
                                    {{ Form::text('name', '', ['class' => 'form-control form-control-sm']) }}
                                    <i class="fas fa-check-circle my-auto ml-1 cursor-pointer text-success update-icon"></i>
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-body text-center pt-0">
                        <i class="fas fa-plus-circle fa-lg text-success cursor-pointer mt-2" id="add-friend-container"></i>
                    </div>
                </div>
            </div> 
        </div>
    </div>
@endsection
