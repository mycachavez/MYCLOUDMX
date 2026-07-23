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

      return response()->json($response->json());
   }
}
