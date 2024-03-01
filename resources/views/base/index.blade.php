@auth
    @include('base.header')

    <div class="full-page">
        @include('base.sidebar')

        <div class="content-wrapper">
            @include('base.nav')
            @include('base.main')
        </div>
    </div>

    @include('base.footer')
@endauth

@guest
    @include('base.system.login')
@endguest