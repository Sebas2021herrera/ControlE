<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Elemento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElementoController extends Controller
{
    public function create()
    {
        $categorias = Categoria::all(); // Obtener todas las categorías
        return view('elementos.create', compact('categorias')); // Pasar las categorías a la vista
    }

    public function store(Request $request)
    {
        // Validamos los datos del formulario
        $request->validate([
            'categoria' => 'required|exists:categorias,id', // La categoría es obligatoria
            'descripcion' => 'required', // La descripción es obligatoria
            'marca' => 'required', // La marca es obligatoria
            'modelo' => 'required', // El modelo es obligatorio
            'serie' => 'nullable|string', // La serie es opcional y debe ser una cadena de texto
            'especificaciones_tecnicas' => 'nullable|string', // Las especificaciones técnicas son opcionales y deben ser una cadena de texto
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // La foto es opcional y debe ser una imagen con un tamaño máximo de 2MB
        ]);

        $data = $request->all(); // Obtenemos todos los datos del formulario
        if ($request->hasFile('foto')) {
            // Si se subió una foto, la almacenamos en la carpeta 'fotos' en el almacenamiento público
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $data['user_id'] = Auth::id(); // Asignamos el ID del usuario autenticado

        // Aquí se cambia 'categoria' por 'categoria_id'
        $data['categoria_id'] = $data['categoria'];
        unset($data['categoria']);

        Elemento::create($data); // Creamos el nuevo elemento en la base de datos

        return redirect()->back()->with('success', 'Elemento registrado exitosamente.'); // Redirigimos al usuario con un mensaje de éxito
    }
}
