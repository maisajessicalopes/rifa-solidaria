@extends('layouts.app')

@section('content')

<style>
    .numeros-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.col-numero {
    flex: 0 0 calc(100% / 10 - 8px); /* 10 botões por linha com gap */
    box-sizing: border-box;
}

.numero-btn {
    width: 100%;
    height: 50px;
    font-weight: bold;
    padding: 0;
}

</style>

<div class="container">
    <h2 class="mb-4">🎟️ Rifa Solidária - R$10,00 por número</h2>

    <div class="numeros-container mb-3">
        @for ($i = 1; $i <= 450; $i++)
            @php
                $numero = null;
                if ($vendidos) {
                    $numero = $vendidos->get($i);
                }
            @endphp
            {{-- <div class="col-numero"> --}}
                <button
                    class="btn numero-btn {{ $numero ? 'btn-success' : 'btn-light' }}"
                    data-toggle="modal"
                    data-target="#modalNumero"
                    data-numero="{{ $i }}"
                    data-nome="{{ $numero->nome ?? '' }}"
                    data-telefone="{{ $numero->telefone ?? '' }}"
                    data-vendedor="{{ $numero ? optional($numero->vendedor)->name : '' }}"
                    data-comprovante="{{ $numero->comprovante ?? '' }}"
                    {{ $numero && auth()->id() !== $numero->user_id ? 'disabled' : '' }}
                >
                    {{ $i }}
                </button>
            {{-- </div> --}}
        @endfor
    </div> 
            
        
        
        @if($numerosDisponiveis === 0)
            <form method="POST" action="{{ route('rifa.sorteio') }}">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary mt-4">Sortear 🏆</button>
            </form>
        @else
            <p class="mt-4 text-danger">Todos os números devem estar preenchidos para liberar o sorteio.</p>
        @endif
        
</div>

<!-- Modal -->
<div class="modal fade" id="modalNumero" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
  <div class="modal-dialog" role="document">
    <form method="POST" action="/rifa/reservar" enctype="multipart/form-data" class="modal-content">
      {{ csrf_field() }}
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Reservar número <span id="numero-modal"></span></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="numero" id="input-numero">
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Comprovante (jpg, png, pdf)</label>
            <input type="file" name="comprovante" class="form-control-file" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Reservar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script>
    $('#modalNumero').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var numero = button.data('numero');
        var nome = button.data('nome');
        var telefone = button.data('telefone');
        var vendedor = button.data('vendedor');
        var comprovante = button.data('comprovante');
        var modal = $(this);

        modal.find('#numero-modal').text(numero);
        modal.find('#input-numero').val(numero);

        if (nome) {
            modal.find('input[name="nome"]').val(nome).prop('readonly', true);
            modal.find('input[name="telefone"]').val(telefone).prop('readonly', true);
            modal.find('input[name="comprovante"]').replaceWith('<p><a href="/storage/' + comprovante + '" target="_blank">Ver comprovante</a></p>');
            modal.find('.modal-footer .btn-success').hide();
        } else {
            modal.find('input[name="nome"]').val('').prop('readonly', false);
            modal.find('input[name="telefone"]').val('').prop('readonly', false);
            modal.find('input[name="comprovante"]').replaceWith('<input type="file" name="comprovante" class="form-control-file" required>');
            modal.find('.modal-footer .btn-success').show();
        }
    });
</script>

@endsection
