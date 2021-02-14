@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.user.sidebar')
        <div class="col-md-10 pt-3 bg-gray-50">
            <div class="pt-5 m-auto" style="width: 410px;">
                <div class="text-center">
                    <h3 class="h3">パスワードリセット</h3>
                </div>
                <div class="card">
                    <div class="card-body"> 
                        {{ Form::open(['url' => route('password.update')]) }}
                            <div class="form-group">
                                {{ Form::label('email', 'メールアドレス', ['mb-0']) }}
                                {{ Form::text(
                                    'email',
                                    old('email'),
                                    $errors->has('email')
                                        ? ['class' => 'form-control is-invalid', 'required']
                                        : ['class' => 'form-control', 'required']
                                    )
                                }}
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                {{ Form::label('password', 'パスワード', ['class' => 'mb-0']) }}
                                {{ Form::password(
                                    'password',
                                    $errors->has('password')
                                        ? ['class' => 'form-control is-invalid']
                                        : ['class' => 'form-control'])
                                }}
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                {{ Form::label('password-confirm', 'パスワード確認用', ['class' => 'mb-0']) }}
                                {{ Form::password(
                                    'password-confirm',
                                    $errors->has('password-confirm')
                                        ? ['class' => 'form-control is-invalid', 'required']
                                        : ['class' => 'form-control', 'required'])
                                }}
                                @error('password-confirm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                {{ Form::submit('更新', ['class' => 'btn btn-primary']) }}
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
