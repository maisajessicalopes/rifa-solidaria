@extends('layouts.app')

@section('content')
<div class="container">
    <h2>🎟️ Meus Números Reservados</h2>

    @if($numeros->isEmpty())
        <p>Você ainda não reservou nenhum número.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Comprovante</th>
                    <th>Vendedor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($numeros as $numero)
                    <tr>
                        <td>{{ $numero->numero }}</td>
                        <td>{{ $numero->nome }}</td>
                        <td>{{ $numero->telefone }}</td>
                        <td>
                            <a href="{{ $numero->comprovante }}" target="_blank">Ver</a>
                        </td>
                        <td>{{ optional($numero->vendedor)->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
