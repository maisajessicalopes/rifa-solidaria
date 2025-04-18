@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">Sorteio da Rifa</h1>

    <div class="alert alert-info text-center">
        Clique no botão abaixo para realizar o sorteio de um número.
    </div>

    <div class="d-flex justify-content-center my-4" style="margin-left: 110px;">
        <button id="btnSortear" class="btn btn-primary btn-lg">
            Sortear Número
        </button>
    </div>

    <div id="resultadoSorteio" class="text-center mt-4" style="display: none;">
        <h2 class="text-success">Número Sorteado:</h2>
        <div id="numeroSorteado" class="display-4 font-weight-bold"></div>
        <div id="detalhesSorteado" class="mt-3"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        console.log('Script carregado e funcionando!');
        $('#btnSortear').click(function() {
            $.ajax({
                url: '{{ route("rifa.sorteio") }}',
                type: 'GET',
                success: function(response) {
                    if (response.sorteados.length === 0) {
                        alert('Nenhum número disponível para sorteio.');
                        return;
                    }

                    const ganhador = response.sorteados[0];
                    const numeroFormatado = String(ganhador.numero).padStart(3, '0');
                    const vendedor = ganhador.vendedor ? ganhador.vendedor.name : 'Não informado';

                    $('#numeroSorteado').text(numeroFormatado);
                    $('#detalhesSorteado').html(`
                        <p><strong>Nome:</strong> ${ganhador.nome}</p>
                        <p><strong>Telefone:</strong> ${ganhador.telefone}</p>
                        <p><strong>Vendedor:</strong> ${vendedor}</p>
                    `);

                    $('#resultadoSorteio').fadeIn();
                },
                error: function(xhr) {
                    alert('Erro ao realizar o sorteio: ' + (xhr.responseJSON?.error || 'Erro desconhecido.'));
                }
            });
        });
    });
</script>
@endsection