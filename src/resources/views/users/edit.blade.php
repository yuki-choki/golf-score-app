@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
        <div class="col-md-12">
            <div class="text-center">
                <h3>ユーザ情報編集</h3>
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
                    {{ Form::open(['url' => 'users/update', 'files' => true]) }}
                    <div class="card-body">
                        <div class="col-sm-12 col-md-6">
                            <p class="mb-0">{{ Form::label('name', '名前', ['class' => 'mb-0']) }}</p>
                            <p>{{ Form::text('name', $user->name, ['class' => 'form-control']) }}</p>
                            <p class="mb-0">{{ Form::label('email', 'メールアドレス', ['class' => 'mb-0']) }}</p>
                            <p>{{ Form::text('email', $user->email, ['class' => 'form-control']) }}</p>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            {{ Form::label('avatar', 'プロフィール写真') }}
                            {{ Form::file('avatar') }}
                        </div>
                    </div>
                    <div class="card-body pt-0 text-right">
                        {{ Form::submit('更新', ['class' => 'btn btn-primary']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection