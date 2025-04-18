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

    <h2 class="text-center mb-3 mb-md-4">Rifa Solidária R$10,00 cada número</h2>
    <p>PIX: <a href="#" id="pixEmail">maisajlf90@outlook.com</a></p>
<small id="copyMessage" style="display: none; color: green;">PIX copiado!</small>
        
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
        <table class="numeros-table" style="margin-left:60px;">
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

    @if(Auth::user()->id == 1)
        <div class="d-flex justify-content-center my-3 my-md-4" style="margin-top: 20px; margin-left:150px;">
            <a href="{{ route('rifa.sorteio-view') }}" class="btn btn-primary py-2 px-4">
                Sortear
            </a>
        </div>
    @endif
    <div class="d-flex justify-content-center my-3 my-md-4" style="margin-top: 20px; margin-left:110px;">
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
                               name="numero" min="1" max="450" required>
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
        function copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopyMessage();
                }).catch(function(error) {
                    console.error('Erro ao copiar:', error);
                    fallbackCopyText(text);
                });
            } else {
                fallbackCopyText(text);
            }
            return false;
        }
        
        function fallbackCopyText(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopyMessage();
                }
            } catch (err) {
                console.error('Erro ao copiar:', err);
            }
            
            document.body.removeChild(textarea);
        }
        
        function showCopyMessage() {
            const message = document.getElementById('copyMessage');
            message.style.display = 'inline';
            setTimeout(() => {
                message.style.display = 'none';
            }, 2000);
        }

        $(document).ready(function() {
            // Configura máscara para telefone
            $('#telefone').inputmask('(99) 99999-9999');

            // Quando um número é clicado
            $('.numero-btn:not(.disabled)').click(function() {
                const numero = $(this).data('numero');
                $('#numeroInput').val(numero);
            });

        });
    </script>
@endsection