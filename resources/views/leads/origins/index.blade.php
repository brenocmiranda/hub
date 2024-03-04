@extends('base.index')

@section('title')
Origens
@endsection

@section('buttons')
    <a href="{{ route('create.leads.origins') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Novo</span>
    </a>
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table id="table">
                    <thead>
                        <tr>
                            <th data-field="name" data-align="center">Nome</th>
                            <th data-field="slug" data-align="center">Slug</th>
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($origins as $origin)
                                { 
                                    'name': '{{ ucfirst($origin->name) }}', 
                                    'slug': '{{ $origin->slug }}', 
                                    'status': ({{ $origin->active }} ? '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>' : '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>'), 
                                    'operations': '<a href="{{ route('edit.leads.origins', $origin->id ) }}" class="btn btn-outline-secondary me-1 px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a>' + ({{ $origin->active }} ? '<a href="{{ route('destroy.leads.origins', $origin->id ) }}" class="btn btn-outline-secondary ms-1 px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Arquivar"><i class="bi bi-archive"></i></a>' : "")
                                },
                            @endforeach
                        ];

                        $table.bootstrapTable('refreshOptions', {
                            data: data
                        });

                        // Enable toltips
                        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

                        // Buttons in destroy
                        $('a.destroy').on('click', function(e){
                            e.preventDefault();
                            $('#modalDestroy').find('.confirm').attr('href', $(this).attr('href'));
                            $('#modalDestroy').modal('show');
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection

@section('modals')
<div class="modal fade p-4 py-md-5" tabindex="-1" role="dialog" id="modalDestroy" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-body p-4 text-center">
        <h5>Tem certeza que deseja desabilitar?</h5>
        <p class="mb-0">O registro será desabilitado e caso necessário, basta reativá-lo.</p>
      </div>
      <div class="modal-footer flex-nowrap p-0">
        <a href="#" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end confirm"><strong>Sim, arquivar!</strong></a>
        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal">Não</button>
      </div>
    </div>
  </div>
</div>
@endsection

