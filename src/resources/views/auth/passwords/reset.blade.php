@extends('layouts.app')

@section('content')
<div class="container p-0">
    <div class="w-full h-full bg-gray-50">
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
                            {{ Form::label('password_confirmation', 'パスワード確認用', ['class' => 'mb-0']) }}
                            {{ Form::password(
                                'password_confirmation',
                                $errors->has('password_confirmation')
                                    ? ['class' => 'form-control is-invalid', 'required']
                                    : ['class' => 'form-control', 'required'])
                            }}
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            {{ Form::submit('更新', ['class' => 'btn btn-primary']) }}
                        </div>
                        <!-- urlからパスワードリセットに必要な token のみ取得し、name="token" に値をセットする -->
                        {{ Form::hidden('token', str_replace('password/reset/', '', request()->path())) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
