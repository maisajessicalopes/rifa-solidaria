@extends('layouts.app')

@section('content')
<div class="container">
    <style>
        .rifa-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 10px;
        }
        
        .numeros-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
        }
        
        .numeros-table td {
            padding: 0;
            text-align: center;
        }
        
        .numero-btn {
            width: 100%;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 4px;
            transition: all 0.2s;
            font-family: 'Courier New', monospace;
            border: 1px solid #dee2e6;
        }
        
        .numero-btn.disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }
        
        /* Responsividade */
        @media (max-width: 576px) {
            .numero-btn {
                height: 35px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 400px) {
            .numero-btn {
                height: 30px;
                font-size: 0.7rem;
            }
        }
    </style>

    <h1 class="text-center mb-3 mb-md-4">Rifa Solidária R$10,00</h1>
    
    <div class="alert alert-info mb-3 mb-md-4 py-2 py-md-3">
        Números disponíveis: <strong>{{ $numerosDisponiveis }} / 450</strong>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-3 py-2">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-3 py-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="rifa-container">
        <table class="numeros-table">
            @for($i = 0; $i < 90; $i++)
                <tr>
                    @for($j = 1; $j <= 5; $j++)
                        @php
                            $numero = $i * 5 + $j;
                            $numeroFormatado = str_pad($numero, 3, '0', STR_PAD_LEFT);
                            $vendido = isset($vendidos[$numero]);
                        @endphp
                        <td>
                            <button class="btn numero-btn {{ $vendido ? 'btn-success disabled' : 'btn-light' }}"
                                    data-numero="{{ $numero }}"
                                    {{ $vendido ? 'disabled' : '' }}
                                    data-toggle="modal" 
                                    data-target="#modalReserva">
                                {{ $numeroFormatado }}
                            </button>
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    </div>

    @if(Auth::user()->id == 2)
        <div class="d-flex justify-content-center my-3 my-md-4" style="margin-top: 20px;">
            <button id="btnSortear" class="btn btn-primary py-2 px-4" 
                    {{ $numerosDisponiveis > 0 ? 'disabled' : '' }}>
                Sortear 3 Ganhadores
            </button>
        </div>
    @endif
    <div class="d-flex justify-content-center my-3 my-md-4" style="margin-top: 20px;">
        <a href="{{ route('rifa.numeros-vendidos') }}" class="btn btn-success py-2 px-4">
            Números vendidos
        </a>
    </div>    
</div>

<!-- Modal de Reserva -->
<div class="modal fade" id="modalReserva" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Reservar Número</h5>
                <button type="button" class="close py-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formReserva" method="POST" action="{{ route('rifa.reservar') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body py-3">
                    <div class="form-group mb-1">
                        <label for="numeroInput">Número</label>
                        <input type="number" class="form-control form-control-sm" id="numeroInput" 
                               name="numero" min="1" max="450">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control form-control-sm" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control form-control-sm" id="telefone" name="telefone" required>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label for="comprovante">Comprovante de Pagamento</label>
                        <input type="file" class="form-control-file form-control-sm" id="comprovante" name="comprovante" required>
                        <small class="form-text text-muted">Formatos: JPG, PNG ou PDF</small>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Sorteio -->
<div class="modal fade" id="modalSorteio" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Resultado do Sorteio</h5>
                <button type="button" class="close py-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-3" id="resultadoSorteio">
                <!-- Resultados via JavaScript -->
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configura máscara para telefone
            $('#telefone').inputmask('(99) 99999-9999');

            // Quando um número é clicado
            $('.numero-btn:not(.disabled)').click(function() {
                const numero = $(this).data('numero');
                $('#numeroInput').val(numero);
            });

            // Sorteio
            $('#btnSortear').click(function() {
                $.ajax({
                    url: '{{ route("rifa.sorteio") }}',
                    type: 'GET',
                    success: function(response) {
                        let html = '<div class="ganhadores-list">';
                        response.sorteados.forEach(function(ganhador) {
                            const numeroFormatado = String(ganhador.numero).padStart(3, '0');
                            html += `
                                <div class="ganhador-item mb-2 p-2 border rounded">
                                    <div class="font-weight-bold">Número ${numeroFormatado}</div>
                                    <div>${ganhador.nome}</div>
                                    <div>${ganhador.telefone}</div>
                                </div>`;
                        });
                        html += '</div>';
                        
                        $('#resultadoSorteio').html(html);
                        $('#modalSorteio').modal('show');
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>
@endsection