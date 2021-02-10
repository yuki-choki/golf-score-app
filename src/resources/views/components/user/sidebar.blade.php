<div class="col-md-2 side-bar p-0">
    <a class="hover:no-underline" href="{{ route('home') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-home fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">ホーム</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="{{ route('users.edit') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-user-edit fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">プロフィール編集</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="{{ route('password.form') }}">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-lock fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">パスワード変更</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="#">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-user-friends fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">友達</span></div>
        </div>
    </a>
    <a class="hover:no-underline" href="#">
        <div class="side-bar-item d-flex p-3 hover:bg-base-deep hover:text-white">
            <div class="col-2 text-center p-0"><i class="fas fa-cog fa-lg leading-6"></i></div>
            <div class="col-10 ml-3 p-0"><span class="text-base">設定</span></div>
        </div>
    </a>
</div>