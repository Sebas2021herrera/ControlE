<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elemento;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;



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
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descripcion' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'serie' => 'nullable|string|max:255',
            'especificaciones_tecnicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB por imagen
        ]);

        // Crear un nuevo elemento
        $elemento = new Elemento();
        $elemento->categoria_id = $validatedData['categoria_id'];
        $elemento->descripcion = $validatedData['descripcion'];
        $elemento->marca = $validatedData['marca'];
        $elemento->modelo = $validatedData['modelo'];
        $elemento->serie = $validatedData['serie'] ?? null;
        $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'] ?? null;
        $elemento->usuario_id = Auth::id(); // Asignar el ID del usuario autenticado

        // Guardar la foto si está presente
        if ($request->hasFile('foto')) {
            $elemento->foto = $request->file('foto')->store('fotos', 'public');
        }

        $elemento->save();

        // Redireccionar con un mensaje de éxito
        return redirect()->route('user.panel')->with('success', '¡Elemento registrado exitosamente!');
    }

    public function showUserElements()
    {
        $user = Auth::user();
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
            Storage::delete('public/' . $elemento->foto);
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

    // Método para actualizar un elemento
    public function update(Request $request, $id)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descripcion' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'serie' => 'nullable|string|max:255',
            'especificaciones_tecnicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB por imagen
        ]);

        // Buscar el elemento por su ID
        $elemento = Elemento::findOrFail($id);

        // Verifica si el elemento pertenece al usuario autenticado
        if ($elemento->usuario_id != Auth::id()) {
            return redirect()->route('user.panel')->with('error', 'No tienes permiso para editar este elemento.');
        }

        // Actualizar los campos del elemento
        $elemento->categoria_id = $validatedData['categoria_id'];
        $elemento->descripcion = $validatedData['descripcion'];
        $elemento->marca = $validatedData['marca'];
        $elemento->modelo = $validatedData['modelo'];
        $elemento->serie = $validatedData['serie'] ?? null;
        $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'] ?? null;

        // Manejar la foto, si se sube una nueva
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($elemento->foto) {
                Storage::delete('public/' . $elemento->foto);
            }
            // Guardar la nueva foto
            $elemento->foto = $request->file('foto')->store('fotos', 'public');
        }

        // Guardar los cambios en la base de datos
        $elemento->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('user.panel')->with('success', '¡Elemento actualizado exitosamente!');
    }

    public function detalles($id)
    {
        $elemento = Elemento::with('categoria')->find($id); // Cargar la categoría

        if (!$elemento) {
            return response()->json(['success' => false, 'message' => 'Elemento no encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'elemento' => [
                'descripcion' => $elemento->descripcion,
                'marca' => $elemento->marca,
                'modelo' => $elemento->modelo,
                'serie' => $elemento->serie,
                'especificaciones' => $elemento->especificaciones_tecnicas,
                'foto' => $elemento->foto,
                'categoria' => $elemento->categoria->nombre // Solo el nombre de la categoría
            ]
        ]);
    }
}
