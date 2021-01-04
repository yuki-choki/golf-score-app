@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <div class="text-center">
                <h3>マイページ</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group">
                    <a href="{{ route('users.edit') }}"><li class="list-group-item">ユーザ情報編集</li></a>
                    <a href="#"><li class="list-group-item">ラウンド記録</li></a>
                    <a href="#"><li class="list-group-item">友達</li></a>
                    <a href="#"><li class="list-group-item">設定</li></a>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body text-center">
                        <img class="rounded-circle d-inline-block" style="width: 100px; height: 100px;" src="{{ $user->avatar }}" alt="ユーザimg">
                        <h5 class="mt-2">{{ $user->name }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection