@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    ラウンド記録分析
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($game as $data)
                        <li>
                            {{ $data }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
