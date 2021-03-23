@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('components.user.sidebar')
            <div class="col pt-3 bg-gray-50">
                <h3 class="h3">プロフィール編集</h3>
                <div class="card">
                    {{ Form::open(['url' => 'users/update', 'files' => true]) }}
                    <div class="card-body">
                        <div class="form-group row">
                            {{ Form::label('name', '名前', ['class' => 'col-md-4 col-form-label text-md-right']) }}
                            <div class="col-md-6">
                                {{ Form::text('name', $user->name, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            {{ Form::label('avatar', 'プロフィール写真', ['class' => 'col-md-4 col-form-label text-md-right']) }}
                            <div class="col-md-6">
                                <label for="avatar">
                                    @if ($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="" style="width: 150px; height: 150px;" class="rounded-circle cursor-pointer" id="user_img">
                                    @else
                                        <img src="{{ asset('images/no-user-img.png') }}" alt="" style="width: 150px; height: 150px;" class="cursor-pointer" id="user_img">
                                    @endif
                                </label>
                                {{ Form::file('avatar', ['class' => 'd-none', 'onchange' => 'changeImage(this)']) }}
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 text-right">
                                {{ Form::submit('更新', ['class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function changeImage(obj) {
        var fileReader = new FileReader();
        fileReader.onload = (function() {
            document.getElementById('user_img').src = fileReader.result;
        });
        fileReader.readAsDataURL(obj.files[0]);
    }
</script>