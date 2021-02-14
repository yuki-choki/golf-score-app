@extends('layouts.app')

@section('content')
<div class="container p-0">
    <div class="w-full h-full bg-gray-50">
        <div class="pt-5 m-auto" style="width: 410px;">
            <div class="text-center">
                <h3 class="h3">パスワード再設定</h3>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ Form::open(['url' => route('password.email')]) }}
                        <div class="form-group ">
                            {{ Form::label('email', 'メールアドレス', ['class' => 'mb-0']) }}
                            {{ Form::text(
                                'email',
                                old('email'),
                                $errors->has('email')
                                    ? ['class' => 'form-control is-invalid', 'required']
                                    : ['class' => 'form-control', 'required'])
                            }}
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::submit('確認用メール送信', ['class' => 'btn btn-primary mt-3']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
