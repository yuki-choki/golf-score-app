@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('components.sidebar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">プロフィール編集</h5></div>
                    {{ Form::open(['url' => 'users/update', 'files' => true]) }}
                    <div class="card-body">
                        <div class="form-group row">
                            {{ Form::label('name', '名前', ['class' => 'col-md-4 col-form-label text-md-right']) }}
                            <div class="col-md-6">
                                {{ Form::text('name', $user->name, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            {{ Form::label('email', 'メールアドレス', ['class' => 'col-md-4 col-form-label text-md-right']) }}
                            <div class="col-md-6">
                                {{ Form::text('email', $user->email, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            {{ Form::label('avatar', 'プロフィール写真', ['class' => 'col-md-4 col-form-label text-md-right']) }}
                            <div class="col-md-6">
                                {{ Form::file('avatar') }}
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 text-right">
                                {{ Form::submit('更新', ['class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection