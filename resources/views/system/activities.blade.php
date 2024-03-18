@extends('base.index')

@section('title')
Minhas atividades
@endsection

@section('css')
    <link href="{{ asset('css/activities.css') }}" rel="stylesheet">
@endsection

@section('content-page')
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="timeline-centered">
                    @foreach($logs as $log)
                    <?php 
                        $timestamp = $log->created_at; 
                        $currentDate = gmdate('Y-m-d', $timestamp); 
                    ?>
                    <article class="timeline-entry">
                        <div class="timeline-entry-inner">
                            <time class="timeline-time" datetime="{{ $log->created_at }}"><span>{{ $log->created_at->format('H:i') . ' ' . $log->created_at->format('A') }}</span> <span>{{ $currentDate }}</span></time>
                            <div class="timeline-icon bg-white">
                                <i class="entypo-feather"></i>
                            </div>
                            <div class="timeline-label">
                                <h2><a href="#">{{ $log->title }}</a></h2>
                                <p>{{ $log->action }}</p>
                            </div>
                        </div>
                    </article>
                    @endforeach

                    <article class="timeline-entry begin">
                        <div class="timeline-entry-inner">
                            <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                                <i class="entypo-flight"></i>
                            </div>
                        </div>
                    </article>
                </div> 
            </div>
        </div>
    </div>
</section>
@endsection