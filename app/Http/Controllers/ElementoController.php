<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elemento;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class ElementoController extends Controller
{
    public function create()
    {
        $categorias = Categoria::all();
        return view('elementos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'categoria' => 'required|array',
            'categoria.*' => 'required|integer|exists:categorias,id',
            'descripcion' => 'required|array',
            'descripcion.*' => 'required|string|max:255',
            'marca' => 'required|array',
            'marca.*' => 'required|string|max:255',
            'modelo' => 'required|array',
            'modelo.*' => 'required|string|max:255',
            'serie' => 'nullable|array',
            'serie.*' => 'nullable|string|max:255',
            'especificaciones_tecnicas' => 'nullable|array',
            'especificaciones_tecnicas.*' => 'nullable|string',
            'foto' => 'nullable|array',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB por imagen
        ]);

        // Iterar sobre los elementos y guardarlos en la base de datos
        foreach ($validatedData['categoria'] as $index => $categoriaId) {
            $elemento = new Elemento();
            $elemento->categoria_id = $categoriaId;
            $elemento->descripcion = $validatedData['descripcion'][$index];
            $elemento->marca = $validatedData['marca'][$index];
            $elemento->modelo = $validatedData['modelo'][$index];
            $elemento->serie = $validatedData['serie'][$index] ?? null;
            $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'][$index] ?? null;
            $elemento->usuario_id = Auth::id(); // Asignar el ID del usuario autenticado

            // Guardar la foto si está presente
            if (isset($validatedData['foto'][$index])) {
                $elemento->foto = $validatedData['foto'][$index]->store('fotos', 'public');
            }

            $elemento->save();
        }

        // Redireccionar con un mensaje de éxito
        return redirect()->route('user.panel')->with('success', '¡Elementos registrados exitosamente!');
    }

    public function showUserElements()
    {
        $user = auth()->User();
        $elementos = $user->elementos; // Asumiendo que tienes una relación definida en el modelo User

        return view('index.vistausuario', compact('elementos'));
    }

    // Método para eliminar un elemento
    public function destroy($id)
    {
        $elemento = Elemento::findOrFail($id);
        
        // Verifica si el elemento pertenece al usuario autenticado
        if ($elemento->usuario_id != Auth::id()) {
            return redirect()->route('user.panel')->with('error', 'No tienes permiso para eliminar este elemento.');
        }
        
        // Elimina el archivo de la foto si existe
        if ($elemento->foto) {
            \Storage::delete('public/' . $elemento->foto);
        }
        
        $elemento->delete();
        
        return redirect()->route('user.panel')->with('success', '¡Elemento eliminado exitosamente!');
    }

    // Método para mostrar el formulario de edición de un elemento
    public function edit($id)
    {
        $elemento = Elemento::findOrFail($id);

        // Verifica si el elemento pertenece al usuario autenticado
        if ($elemento->usuario_id != Auth::id()) {
            return redirect()->route('user.panel')->with('error', 'No tienes permiso para editar este elemento.');
        }

        $categorias = Categoria::all();
        return view('elementos.edit', compact('elemento', 'categorias'));
    }
}
