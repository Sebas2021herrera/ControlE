<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elemento;
use App\Models\Categoria;

class ElementoController extends Controller
{
    public function create()
    {
        $categorias = Categoria::all();
        return view('elementos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'categoria.*' => 'required|exists:categorias,id',
            'descripcion.*' => 'required|string|max:255',
            'marca.*' => 'required|string|max:255',
            'modelo.*' => 'required|string|max:255',
            'serie.*' => 'nullable|string|max:255',
            'especificaciones_tecnicas.*' => 'nullable|string',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        foreach ($validatedData['categoria'] as $index => $categoriaId) {
            $elemento = new Elemento();
            $elemento->categoria_id = $categoriaId;
            $elemento->descripcion = $validatedData['descripcion'][$index];
            $elemento->marca = $validatedData['marca'][$index];
            $elemento->modelo = $validatedData['modelo'][$index];
            $elemento->serie = $validatedData['serie'][$index] ?? null;
            $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'][$index] ?? null;

            if (isset($validatedData['foto'][$index])) {
                $elemento->foto = $validatedData['foto'][$index]->store('fotos', 'public');
            }

            $elemento->save();
        }

        return redirect()->back()->with('success', '¡Elementos registrados exitosamente!');
    }
}
