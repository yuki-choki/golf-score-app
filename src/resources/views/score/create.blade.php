@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('components.main.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    スコアカード読み込み
                </div>
                <div class="card-body">
                    スコアカード読み込みフォーム
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
