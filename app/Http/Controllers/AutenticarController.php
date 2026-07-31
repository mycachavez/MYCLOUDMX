<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AutenticarController extends Controller
{
   public function index(Request $request){
      $zapikey = config('services.zoho.zapikey');

      $url = "https://www.zohoapis.com/crm/v7/functions/myc_banco_login/actions/execute" . "?auth_type=apikey&zapikey={$zapikey}";

      $response = Http::post($url, $request->all());

      if ($response->failed()) {
         return response()->json(['error' => 'Error al autenticar'], 500);
      }

      $data = $response->json('data');

      if (empty($data['banco_id'])) {
         return response()->json(['error' => 'Credenciales inválidas'], 401);
      }

      // Guardamos el usuario/banco autenticado en la sesión de Laravel
      $request->session()->put('auth_banco', [
         'banco_id' => $data['banco_id'],
         'banco_nombre' => $data['banco_nombre'],
      ]);
      $request->session()->regenerate();

      return response()->json($data);

      //return response()->json($response->json());
   }

   public function usuarioActual(Request $request){
      if (!$request->session()->has('auth_banco')) {
         return response()->json(['error' => 'No autenticado'], 401);
      }

      return response()->json($request->session()->get('auth_banco'));
   }

   public function logout(Request $request){
      $request->session()->forget('auth_banco');
      $request->session()->regenerate();

      return response()->json(['message' => 'Sesión cerrada']);
   }
}
