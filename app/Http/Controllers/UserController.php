<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserIllness;
use App\Models\UserSymptom;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(){

        return view('user.index');
    }

    public static function store($request)
    {
        $user = UserService::triagemUser($request);

        return $user;
    }

    public function update(Request $request)
    {
        $user = UserService::updateUser($request->id);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'data'    => $user,
        ]);
    }

    /**
     * Show a specific user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }

    public static function getIllness(){
        $user =  Auth::user();

        if(!isset($user)){
            return response()->json([
                'status' => false,
            ]);
        }

        $itens = UserIllness::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'itens' => $itens,
        ]);
    }

    public static function getSymptoms(){
        $user =  Auth::user();

        if(!isset($user)){
            return response()->json([
                'status' => false,
            ]);
        }

        $itens = UserSymptom::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'itens' => $itens,
        ]);
    }
    

    public function renderizarImagem(Request $request)
{
    // Validação: Verifica se a imagem foi enviada e se o formato é válido
    $validated = $request->validate([
        'image' => 'required|string',
    ]);

    // Recupera a imagem em Base64
    $imageData = $validated['image'];

    // Verifica se a string começa com o prefixo de Base64 (isso ajuda a validar que é realmente Base64)
    if (strpos($imageData, 'data:image/') !== false) {
        // Remove o prefixo "data:image/png;base64," ou similar, caso exista
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
    }

    // Verifica se o Base64 está correto
    if (base64_decode($imageData, true) === false) {
        return response()->json(['error' => 'Imagem inválida.'], 400);
    }

    // URL da API da OpenAI para análise de imagem
    $openaiUrl = 'https://api.openai.com/v1/images/analyze'; // Substitua pelo endpoint correto

    try {
        // Envia a solicitação POST para a API OpenAI com a imagem em Base64
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'), // Substitua com sua chave da OpenAI
            'Content-Type' => 'application/json',
        ])->post($openaiUrl, [
            'image' => $imageData,
            'purpose' => 'analyze', // Ajuste o propósito conforme necessário
        ]);

        // Se a resposta for bem-sucedida, retorna os dados
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'Falha na análise da imagem.'], 500);
        }
    } catch (\Exception $e) {
        // Retorna erro caso ocorra algum problema na requisição
        return response()->json(['error' => 'Erro ao enviar imagem para análise.'], 500);
    }
}
}
