@extends('base.index')

@section('title')
Minhas atividades
@endsection

@section('css')
    <link href="{{ asset('css/activities.css') }}" rel="stylesheet">
@endsection

@section('content-page')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(isset($logs[0]))
                <div class="timeline-centered">
                    <?php 
                        $actions = [
                            'create' => 'bi-plus-circle-dotted',
                            'update' => 'bi-pencil-square', 
                            'destroy' => 'bi-trash3', 
                            'show' => 'bi-card-list', 
                            'recovery' => 'bi-exclamation-diamond', 
                            'login' => 'bi-door-open', 
                            'logout' => 'bi-door-closed', 
                            'block' => 'bi-ban', 
                            'reset' => 'bi-hash',
                            'retryAll' => 'bi-repeat',
                            'retry' => 'bi-repeat-1',
                            'resend' => 'bi-send',
                            'reports' => 'bi-file-earmark-text'
                        ];
                    ?>

                    @foreach($logs as $index => $log)
                        <article class="timeline-entry {{ $index % 2 == 0 ? 'left-aligned' : '' }}">
                            <div class="timeline-entry-inner">
                                <time class="timeline-time" datetime="{{ $log->created_at }}"><span>{{ $log->created_at->format('H:i') . ' ' . $log->created_at->format('A') }}</span> <span>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span></time>
                                <div class="timeline-icon bg-secondary-subtle">
                                    <i class="bi {{ isset($actions[$log->action]) ? $actions[$log->action] : '' }}"></i>
                                </div>
                                <div class="timeline-label">
                                    <h2 class="fw-bold">{{ $log->title }}</h2>
                                    <p>{{ $log->description }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                    
                    @if($logs->onLastPage())
                        <article class="timeline-entry begin">
                            <div class="timeline-entry-inner">
                                <div class="timeline-icon bg-primary" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                                    <i class="entypo-flight"></i>
                                </div>
                            </div>
                        </article>
                    @endif
                </div> 
                {{ $logs->onEachSide(5)->links() }}
            @else
                <p>Nenhuma atividade registrada para seu usuário.<p>
            @endif
        </div>
    </div>
</div>
@endsection