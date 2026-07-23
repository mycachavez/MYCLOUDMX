<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReporteDomicilioController extends Controller
{
    public function index(Request $request){

      $zapikey = config('services.zoho.zapikey');

      $url = "https://www.zohoapis.com/crm/v7/functions/myc_banco_reportes_domicilio/actions/execute" . "?auth_type=apikey&zapikey={$zapikey}";

      $response = Http::post($url, array_merge($request->all(), [
         'banco' => 'Santander', 
      ]));

      if ($response->failed()) {
         return response()->json(['error' => 'Error al obtener los datos'], 500);
      }

      return response()->json($response->json());

      /* $registros = [
         ['folio' => 'F-001', 'nombre' => 'Ana',     'apellido' => 'García',    'estado' => 'Activo',     'banco' => 'BBVA',       'importe' => 12500.00],
         ['folio' => 'F-002', 'nombre' => 'Carlos',  'apellido' => 'Martínez',  'estado' => 'Pendiente',  'banco' => 'Banamex',    'importe' => 8750.50 ],
         ['folio' => 'F-003', 'nombre' => 'Laura',   'apellido' => 'López',     'estado' => 'Completado', 'banco' => 'Santander',  'importe' => 31200.00],
         ['folio' => 'F-004', 'nombre' => 'Miguel',  'apellido' => 'Hernández', 'estado' => 'Cancelado',  'banco' => 'HSBC',       'importe' => 5000.00 ],
         ['folio' => 'F-005', 'nombre' => 'Sofía',   'apellido' => 'Ramírez',   'estado' => 'Activo',     'banco' => 'Banorte',    'importe' => 19800.75],
         ['folio' => 'F-006', 'nombre' => 'Luis',    'apellido' => 'Torres',    'estado' => 'Pendiente',  'banco' => 'BBVA',       'importe' => 6400.00 ],
         ['folio' => 'F-007', 'nombre' => 'María',   'apellido' => 'Flores',    'estado' => 'Activo',     'banco' => 'Scotiabank', 'importe' => 22100.00],
         ['folio' => 'F-008', 'nombre' => 'Jorge',   'apellido' => 'Díaz',      'estado' => 'Completado', 'banco' => 'Banamex',    'importe' => 14300.25],
         ['folio' => 'F-009', 'nombre' => 'Valeria', 'apellido' => 'Morales',   'estado' => 'Pendiente',  'banco' => 'HSBC',       'importe' => 9900.00 ],
         ['folio' => 'F-010', 'nombre' => 'Roberto', 'apellido' => 'Jiménez',   'estado' => 'Activo',     'banco' => 'Santander',  'importe' => 41500.00],
      ];

      return response()->json([
         'data' => $registros
      ]); */
      
    }
}
