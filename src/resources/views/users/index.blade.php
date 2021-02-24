@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('components.user.sidebar')
            <div class="col pt-3 bg-gray-50">
                <h3 class="h3">ユーザ情報</h3>
                <div class="card">
                    <div class="card-body text-center">
                        <img class="rounded-circle d-inline-block" style="width: 100px; height: 100px;" src="{{ $user->avatar }}" alt="ユーザimg">
                        <h5 class="h5 mt-2">{{ $user->name }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection