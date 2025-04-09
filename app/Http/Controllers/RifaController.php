<?php

namespace App\Http\Controllers;

use App\RifaNumero;
use Illuminate\Http\Request;

class RifaController extends Controller
{
    public function index()
    {
        $vendidos = \App\RifaNumero::all()->keyBy('numero');
        $numerosDisponiveis = 450 - $vendidos->count();

        return view('rifa.index', compact('vendidos', 'numerosDisponiveis'));
    }


    public function reservarNumero(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|min:1|max:450',
            'nome' => 'required|string',
            'telefone' => 'required|string',
            'comprovante' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        // Verifica se o número já foi reservado
        if (RifaNumero::where('numero', $request->numero)->exists()) {
            return back()->with('error', 'Esse número já foi reservado.');
        }

        // Salva comprovante
        $comprovante = $request->file('comprovante')->store('comprovantes', 'public');

        // Cria o registro no banco
        RifaNumero::create([
            'numero' => $request->numero,
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'comprovante' => $comprovante,
            'user_id' => auth()->id(),
            'vendedor_id' => auth()->id(),
        ]);

        return back()->with('success', 'Número reservado com sucesso!');
    }

    public function meusNumeros()
    {
        $numeros = RifaNumero::where('user_id', auth()->id())->get();
        return view('rifa.meus_numeros', compact('numeros'));
    }

    public function sorteio()
    {
        if (RifaNumero::count() < 450) {
            return response()->json(['error' => 'Nem todos os números foram vendidos!'], 400);
        }

        $sorteados = RifaNumero::inRandomOrder()->limit(3)->get();
        return response()->json(['sorteados' => $sorteados]);
    }
}
