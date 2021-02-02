@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('components.user.sidebar')
            <div class="col-md-10 mt-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">ユーザ情報</h5></div>
                    <div class="card-body text-center">
                        <img class="rounded-circle d-inline-block" style="width: 100px; height: 100px;" src="{{ $user->avatar }}" alt="ユーザimg">
                        <h5 class="mt-2">{{ $user->name }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection