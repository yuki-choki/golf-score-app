@extends('layouts.app')

@section('content')
<div class="container p-0">
    <div class="w-full h-full bg-gray-50">
        <div class="pt-5 m-auto" style="width: 410px;">
            <div class="text-center">
                <h3 class="h3">ログイン</h3>
            </div>
            <div class="card">
                <div class="card-body pb-0">
                    <div>
                        {{ Form::open(['url' => route('login')]) }}
                            <div class="form-group">
                                {{ Form::label('email', 'メールアドレス', ['class' => 'mb-0']) }}
                                {{ Form::text(
                                    'email',
                                    old('email'),
                                    $errors->has('email')
                                        ? ['class' => 'form-control is-invalid']
                                        : ['class' => 'form-control'])
                                }}
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                {{ Form::label('password', 'パスワード', ['class' => 'mb-0']) }}
                                {{ Form::text(
                                    'password',
                                    old('password'),
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
                                {{ Form::checkbox('remember', 'remember', false, [old('remember') ? 'checked' : '']) }}
                                {{ Form::label('remember', 'ログインを記憶する', ['class' => 'mb-0']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::submit('ログイン', ['class' => 'btn btn-success btn-lg btn-block']) }}
                            </div>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link pl-0 pt-0" href="{{ route('password.request') }}">
                                    パスワードを忘れた場合
                                </a>
                            @endif
                        </form>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="border-top text-center py-2">
                        - SNSアカウントでログイン -
                    </div>
                    <div class="row m-0">
                        <div class="col-sm-6 p-1">
                            <a class="btn btn-google d-block hover:opacity-80" href="/login/google">
                                <i class="fab fa-google"></i>
                                google
                            </a>
                            {{-- <a class="d-block hover:opacity-80" href="/login/google">
                                <img src="/storage/btn_google_signin_light_normal_web.png" alt="" style="height: 37px; width: 100%;">
                            </a> --}}
                        </div>
                        <div class="col-sm-6 p-1">
                            <a class="btn btn-facebook d-block hover:text-white hover:opacity-80" href="/login/facebook">
                                <i class="fab fa-facebook-square"></i>
                                facebook
                            </a>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col-sm-6 p-1">
                            <a class="btn btn-twitter d-block hover:text-white hover:opacity-80" href="/login/twitter">
                                <i class="fab fa-twitter"></i>
                                twitter
                            </a>
                        </div>
                        <div class="col-sm-6 p-1">
                            <a class="btn btn-github d-block hover:text-white hover:opacity-80" href="/login/github">
                                <i class="fab fa-github"></i>
                                github
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
