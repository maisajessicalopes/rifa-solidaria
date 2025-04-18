<?php

namespace App\Http\Controllers;

use App\RifaNumero;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RifaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }   

    public function index()
    {
        $vendidos = RifaNumero::all()->keyBy('numero');
        $numerosDisponiveis = 450 - $vendidos->count();

        return view('rifa.index', compact('vendidos', 'numerosDisponiveis'));
    }

    // public function reservarNumero(Request $request)
    // {
    //     Log::info('Iniciando reserva de número', ['ip' => $request->ip(), 'user' => auth()->id()]);

    //     $validated = $request->validate([
    //         'numero' => 'required|integer|min:1|max:450',
    //         'nome' => 'required|string|max:255',
    //         'telefone' => 'required|string|max:20',
    //         'comprovante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
    //     ]);

    //     Log::debug('Dados validados', $validated);

    //     try {
    //         DB::beginTransaction();
    //         Log::info('Transação iniciada');

    //         // Verifica se número já existe
    //         if (RifaNumero::where('numero', $validated['numero'])->exists()) {
    //             Log::warning('Número já reservado', ['numero' => $validated['numero']]);
    //             return back()->with('error', 'Esse número já foi reservado.');
    //         }

    //         // Processa comprovante
    //         $file = $request->file('comprovante');
    //         $filename = 'comprovante_'.time().'_'.$file->getClientOriginalName();
    //         Log::debug('Processando upload do arquivo', ['original' => $file->getClientOriginalName()]);

    //         $path = $file->storeAs('comprovantes', $filename, 'public');
    //         Log::info('Arquivo armazenado', ['path' => $path]);

    //         // Cria registro
    //         $rifaNumero = RifaNumero::create([
    //             'numero' => $validated['numero'],
    //             'nome' => $validated['nome'],
    //             'telefone' => $validated['telefone'],
    //             'comprovante' => $path,
    //             'user_id' => auth()->id(),
    //             'vendedor_id' => auth()->id(),
    //         ]);

    //         Log::info('Registro criado', ['id' => $rifaNumero->id]);

    //         DB::commit();
    //         Log::info('Transação commitada');

    //         return back()->with('success', 'Número reservado com sucesso!');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Erro ao reservar número', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return back()->with('error', 'Erro ao reservar número: '.$e->getMessage());
    //     }
    // }


    public function reservarNumero(Request $request)
    {
        Log::info('Iniciando reserva de número', ['ip' => $request->ip(), 'user' => auth()->id()]);

        $validated = $request->validate([
            'numero' => 'required|integer|min:1|max:450',
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'comprovante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        Log::debug('Dados validados', $validated);

        try {
            DB::beginTransaction();
            Log::info('Transação iniciada');

            if (RifaNumero::where('numero', $validated['numero'])->exists()) {
                Log::warning('Número já reservado', ['numero' => $validated['numero']]);
                return back()->with('error', 'Esse número já foi reservado.');
            }

            $file = $request->file('comprovante');
            $filename = 'comprovante_' . time() . '_' . $file->getClientOriginalName();
            Log::debug('Processando upload para Supabase', ['nome_arquivo' => $filename]);

            // Upload para Supabase
            $supabaseUrl = env('SUPABASE_URL'); // ex: https://xxxx.supabase.co
            $supabaseKey = env('SUPABASE_SERVICE_ROLE_KEY');
            $bucket = 'comprovantes';

            $fileContent = file_get_contents($file->getRealPath());

            $client = new \GuzzleHttp\Client();

            //dd($supabaseUrl, $supabaseKey);


            $response = $client->request('POST', "$supabaseUrl/storage/v1/object/$bucket/$filename", [
                'headers' => [
                    'Authorization' => "Bearer $supabaseKey",
                    'Content-Type'  => 'application/octet-stream',
                    'x-upsert'      => 'true'
                ],
                'body' => $fileContent,
            ]);

            if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
                throw new \Exception('Erro ao subir comprovante para Supabase');
            }

            $publicUrl = "$supabaseUrl/storage/v1/object/public/$bucket/$filename";
            Log::info('Comprovante enviado para Supabase', ['url' => $publicUrl]);

            $rifaNumero = RifaNumero::create([
                'numero' => $validated['numero'],
                'nome' => $validated['nome'],
                'telefone' => $validated['telefone'],
                'comprovante' => $publicUrl,
                'user_id' => auth()->id(),
                'vendedor_id' => auth()->id(),
            ]);

            Log::info('Registro criado', ['id' => $rifaNumero->id]);

            DB::commit();
            Log::info('Transação commitada');

            return back()->with('success', 'Número reservado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao reservar número', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erro ao reservar número: ' . $e->getMessage());
        }
    }
    
    public function numerosVendidos()
    {
        $query = RifaNumero::with('vendedor')->orderBy('numero');

        if(request('numero')) {
            $query->where('numero', request('numero'));
        }
        
        if(request('nome')) {
            $query->where('nome', 'like', '%'.request('nome').'%');
        }
        
        if(request('telefone')) {
            $query->where('telefone', 'like', '%'.request('telefone').'%');
        }
        
        if(request('vendedor')) {
            $query->where('vendedor_id', request('vendedor'));
        }
        
        if(request('data_inicio') && request('data_fim')) {
            $query->whereBetween('created_at', [
                request('data_inicio').' 00:00:00',
                request('data_fim').' 23:59:59'
            ]);
        }
        //commit de composer
        $numerosVendidos = $query->paginate(450);
        $vendedores = User::whereHas('numerosVendidos')->orderBy('name')->get();
        
        return view('rifa.numeros-vendidos', compact('numerosVendidos', 'vendedores'));
    }

    public function sorteio()
    {
        if (RifaNumero::count() < 303) {
            return response()->json(['error' => 'Nem todos os números foram vendidos!'], 400);
        }

        $sorteados = RifaNumero::with('vendedor')->inRandomOrder()->limit(1)->get();
        return response()->json(['sorteados' => $sorteados]);
    }
}