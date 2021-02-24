@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.user.sidebar')
        <div class="col pt-3 bg-gray-50">
            <div class="pt-5 m-auto" style="width: 410px;">
                <div class="text-center">
                    <h3 class="h3">パスワード変更</h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['url' => route('password.change')]) }}
                            @if ($errors->any())
                                <div class="alert alert-danger p-2">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group">
                                {{ Form::label('current_password', '現在のパスワード', ['class' => 'mb-0']) }}
                                {{ Form::password('current_password', ['class' => 'form-control'])}}
                            </div>
                            <div class="form-group">
                                {{ Form::label('password', '新しいパスワード', ['class' => 'mb-0']) }}
                                {{ Form::password('password', ['class' => 'form-control'])}}
                            </div>
                            <div class="form-group">
                                {{ Form::label('password_confirmation', '新しいパスワード（確認用）', ['class' => 'mb-0']) }}
                                {{ Form::password('password_confirmation', ['class' => 'form-control', 'required'])}}
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-success">
                                    更新
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection