<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elemento;
use App\Models\Categoria;
use App\Models\Usuario;
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

        // Buscar el usuario por el número de documento
        $usuario = Usuario::where('numero_documento', $validatedData['numeroDocumentoUsuario'])->firstOrFail();


        // Crear un nuevo elemento
        $elemento = new Elemento();
        $elemento->usuario_id = $usuario->id; // Asignar el ID del usuario encontrado
        $elemento->categoria_id = $validatedData['categoria_id'];
        $elemento->descripcion = $validatedData['descripcion'];
        $elemento->marca = $validatedData['marca'];
        $elemento->modelo = $validatedData['modelo'];
        $elemento->serie = $validatedData['serie'] ?? null;
        $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'] ?? null;
        $elemento->usuario_id = Auth::id(); // Asignar el ID del usuario autenticado

        // Relacionar el elemento con el usuario
        $usuario->elementos()->save($elemento);

        // Redireccionar o mostrar vista de éxito
        return redirect()->route('admin.panel')->with('success', 'Elemento registrado correctamente.');

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
        $user = auth()->user();
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

    public function mostrarVistaAdmin()
    {
        $categorias = Categoria::all(); // O la consulta adecuada para obtener las categorías
        return view('index.vistaadmin', compact('categorias'));
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
}
