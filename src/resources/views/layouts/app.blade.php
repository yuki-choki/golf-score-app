<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'golf-score-app') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- フラッシュメッセージ用 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- google chart 読み込み -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">

    <!-- フラッシュメッセージ用 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

</head>
<body>
    <div id="app" class="vh-100">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container">
                <a class="navbar-brand text-base-dark hover:text-base-dark" href="{{ url('/') }}">
                    <h3>{{ config('app.name') }}</h3>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">新規登録</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown d-flex">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle hover:text-gray-500" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                @if (Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="ユーザimg" class="rounded-circle my-auto" style="height: 30px; width: 30px;">
                                @endif
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2"></i>ログアウト
                                    </a>
                                    <a href="{{ route('users') }}" class="dropdown-item"><i class="far fa-id-card mr-2"></i>マイページ</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main style="height: calc(100% - 53px)">
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
<script>
    @if (session('msg_success'))
        $(function () {
            toastr.success('{{ session('msg_success') }}');
        });
    @endif
    @if (session('msg_danger'))
        $(function () {
            toastr.error('{{ session('msg_danger') }}');
        });
    @endif
</script>
</html>
