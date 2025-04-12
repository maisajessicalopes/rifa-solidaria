@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Números Vendidos</h1>
        <span class="badge bg-primary">{{ $numerosVendidos->total() }} / 450</span>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="number" class="form-control" id="numero" name="numero" 
                           min="1" max="450" value="{{ request('numero') }}">
                </div>
                <div class="col-md-3">
                    <label for="nome" class="form-label">Nome do Comprador</label>
                    <input type="text" class="form-control" id="nome" name="nome" 
                           value="{{ request('nome') }}">
                </div>
                <div class="col-md-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" 
                           value="{{ request('telefone') }}">
                </div>
                <div class="col-md-3">
                    <label for="vendedor" class="form-label">Vendedor</label>
                    <select class="form-select" id="vendedor" name="vendedor">
                        <option value="">Todos</option>
                        @foreach($vendedores as $vendedor)
                            <option value="{{ $vendedor->id }}" {{ request('vendedor') == $vendedor->id ? 'selected' : '' }}>
                                {{ $vendedor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                           value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" 
                           value="{{ request('data_fim') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('rifa.numeros-vendidos') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Resultados -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Número</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Vendedor</th>
                    <th>Comprovante</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($numerosVendidos as $numero)
                    <tr>
                        <td>{{ str_pad($numero->numero, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $numero->nome }}</td>
                        <td>{{ $numero->telefone }}</td>
                        <td>{{ $numero->vendedor->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ asset('storage/'.$numero->comprovante) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-alt"></i> Ver
                            </a>
                        </td>
                        <td>{{ $numero->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                            <p class="h5 text-muted">Nenhum número encontrado</p>
                            @if(request()->anyFilled(['numero', 'nome', 'telefone', 'vendedor', 'data_inicio', 'data_fim']))
                                <a href="{{ route('rifa.numeros-vendidos') }}" class="btn btn-sm btn-outline-primary mt-2">
                                    Limpar filtros
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $numerosVendidos->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .table th {
        white-space: nowrap;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-size: 1rem;
        padding: 0.5em 0.75em;
    }
    
    .card {
        border-radius: 8px;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.9rem;
        }
        
        .table th, .table td {
            padding: 0.5rem;
        }
        
        .form-label {
            font-size: 0.8rem;
        }
        
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
    }
</style>
@endsection

@section('scripts')
<!-- Adicione Font Awesome para os ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
// Máscara para telefone nos filtros
document.addEventListener('DOMContentLoaded', function() {
    Inputmask({
        mask: '(99) 99999-9999',
        showMaskOnHover: false
    }).mask(document.getElementById('telefone'));
});
</script>
@endsection