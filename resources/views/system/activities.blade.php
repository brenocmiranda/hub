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
                    @if(isset($logs[0]))
                        <?php 
                            $actions = [
                                'create' => 'bg-success',
                                'update' => 'bg-primary', 
                                'destroy' => 'bg-danger', 
                                'show' => 'bg-info', 
                                'recovery' => 'bg-warning', 
                                'login' => 'bg-dark', 
                                'logout' => 'bg-secondary', 
                                'block' => 'bg-warning', 
                                'reset' => 'bg-info'
                            ]; 
                        ?>

                        @foreach($logs as $index => $log)
                        <article class="timeline-entry {{ $index % 2 == 0 ? 'left-aligned' : '' }}">
                            <div class="timeline-entry-inner">
                                <time class="timeline-time" datetime="{{ $log->created_at }}"><span>{{ $log->created_at->format('H:i') . ' ' . $log->created_at->format('A') }}</span> <span>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span></time>
                                <div class="timeline-icon {{ isset($actions[$log->action]) ? $actions[$log->action] : '' }}">
                                    <i class="entypo-feather"></i>
                                </div>
                                <div class="timeline-label">
                                    <h2 class="fw-bold">{{ $log->title }}</h2>
                                    <p>{{ $log->description }}</p>
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
                    @else
                        <p>Nenhuma atividade registrada para seu usuário.<p>
                    @endif
                </div> 
            </div>
        </div>
    </div>
</section>
@endsection