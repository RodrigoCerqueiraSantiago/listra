<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veiculos;

class VeiculosController extends Controller
{
    public function index()
    {
        $veiculos = Veiculos::all();
        
        return view('home', [
            'veiculos' => $veiculos
        ]);
    }
    public function getVeiculoById($id_veiculo){

        $veiculo = Veiculos::where('id', $id_veiculo)->first();

        $veiculos = Veiculos::all();
        
       /* return view('home', [
            'veiculos' => $veiculos,
            'veiculo'  => $veiculo
        ]);*/

        return response()->json($veiculo);

    }


}
