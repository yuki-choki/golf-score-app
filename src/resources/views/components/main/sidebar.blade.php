<div class="side-bar p-0">
    <a class="hover:no-underline" href="{{ route('home') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-home fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">ホーム</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="{{ route('scores.search') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-file-signature fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">ラウンド登録</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="{{ route('scores.index') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-pen fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">スコア登録</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="{{ route('scores.index') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-clipboard-list fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">スコア一覧</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="{{ route('scores.analysis') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-chart-pie fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">スコア分析</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="{{ route('users.friend') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-user-friends fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">友達</span></div>
        </div>
    </a>
</div>
