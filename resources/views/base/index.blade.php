@if( Auth::check() && Auth::user()->active )
    @include('base.header')

    <div class="full-page">
        @include('base.sidebar')

        <div class="content-wrapper">
            @include('base.nav')
            @include('base.main')
        </div>
    </div>

    @include('base.footer')
@else
    @php 
        header("Location: " . URL::route('login'), true, 302);
    @endphp 
@endif
