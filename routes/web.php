<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ElementoController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\VigilanteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportesElementosController;
use App\Models\Elemento;
use App\Models\Categoria;
use App\Http\Middleware\AdminAccess;
use App\Http\Controllers\ReportesIngresosController;
use App\Http\Controllers\AboutController;

// Rutas para autenticación y registro
Route::get('/controle', [WelcomeController::class, 'index'])->name('welcome');
// Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::get('create', [AuthController::class, 'create'])->name('create');
Route::post('registrado', [AuthController::class, 'createpost'])->name('createpost');

Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//ruta apra restablecer contraseña
Route::get('resetpass', [AuthController::class, 'resetpass'])->name('resetpass');
Route::post('/password/manual-reset', [AuthController::class, 'manualResetPassword'])->name('password.manual-reset');

// Agrupación de rutas protegidas con autenticación
Route::middleware('auth')->group(function () {

    // Ruta para actualizar el perfil
    Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('updateProfile');
    // Ruta para actualizar la contraseña
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('updatePassword');

    // Rutas para la gestión de elementos
    Route::get('elementos/create', [ElementoController::class, 'create'])->name('elementos.create');
    Route::post('/elementos', [ElementoController::class, 'store'])->name('elementos.store');
    Route::delete('/elementos/{id}', [ElementoController::class, 'destroy'])->name('elementos.destroy');
    Route::get('/elementos/{id}/edit', [ElementoController::class, 'edit'])->name('elementos.edit');
    Route::put('/elementos/{id}', [ElementoController::class, 'update'])->name('elementos.update');
    Route::get('/elementos/detalles/{id}', [ElementoController::class, 'detalles'])->name('elementos.detalles');

    // Ruta para obtener detalles del elemento
    Route::get('/elementos/detalles/{id}', [ReportesElementosController::class, 'obtenerDetalles'])
        ->name('elementos.obtenerDetalles');

    // Rutas para los paneles de administración y control
    Route::middleware(CheckRole::class . ':1')->group(function () {
        Route::get('admin/panel', function () {
            $categorias = Categoria::all();
            $elementos = Elemento::all();
            return view('index.vistaadmin', compact('categorias', 'elementos'));
        })->name('admin.panel');

        // Agregar estas rutas justo después de admin.panel
        Route::get('/admin/usuarios/consulta-masiva', [AdminController::class, 'consultaMasiva'])
            ->name('admin.usuarios.consultaMasiva');
        Route::get('/admin/usuarios/{id}/elementos', [AdminController::class, 'obtenerElementosUsuario'])
            ->name('admin.usuarios.elementos');

        // Ruta para consultar usuarios
        Route::get('/admin/usuarios/consultar', [AdminController::class, 'consultarUsuario'])
            ->name('admin.usuarios.consultar');

        // Ruta para actualizar el perfil del administrador
        Route::post('/admin/update-profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');

        // Rutas específicas para reportes, agrupadas con middleware de administrador
        Route::middleware([AdminAccess::class])->prefix('admin/reportes')->group(function () {

        // Ruta para la vista principal de reportes de ingresos
        Route::get('/ingresos', function () {
            return view('PDF.reportes_ingresos'); // Cambiar por la vista correspondiente
        })->name('admin.reportes.ingresos');

        // Ruta para la consulta de ingresos (AJAX)
        Route::get('/ingresos/consulta', [ReportesIngresosController::class, 'consultaIngresos'])
            ->name('reportes.ingresos.consulta');

        // Nueva ruta para la generación del PDF
        Route::get('/ingresos/pdf', [ReportesIngresosController::class, 'generarPDF'])
            ->name('admin.reportes.ingresos.pdf');

        // Ruta para la vista de reportes de elementos
        Route::get('/ingresos-elementos', function () {
            return view('PDF.reportes_elementos');
        })->name('admin.reportes.elementos');

        // Ruta para la consulta de elementos (AJAX)
        Route::get('/elementos/consulta', [ReportesElementosController::class, 'consultaElementos'])
            ->name('reportes.elementos.consulta');
        // Nueva ruta para la generación del PDF de elementos
        Route::get('/elementos/pdf', [ReportesElementosController::class, 'generarPDF'])
        ->name('admin.reportes.elementos.pdf');

        // Ruta para la vista de reportes de usuarios
        Route::get('/ingresos-usuarios', function () {
            return view('PDF.reportes_usuarios'); // Cambiar por la vista correspondiente
        })->name('admin.reportes.usuarios');

        // Route::get('/admin/usuarios/consulta-masiva', [AdminController::class, 'consultaMasiva'])
        // ->name('admin.usuarios.consultaMasiva');
    });

        Route::delete('/admin/usuarios/{id}', [AdminController::class, 'destroyUsuario'])
            ->name('admin.usuarios.destroy');

        // Ruta para almacenar un nuevo usuario
        Route::post('/admin/usuarios', [AdminController::class, 'storeUsuario'])->name('admin.usuarios.store');

        // Ruta para generar PDF de usuario
        Route::post('/admin/usuarios/pdf', [AdminController::class, 'generarReporteIngresosUsuario'])->name('admin.usuarios.pdf');

         // Rutas para gestionar elementos
         Route::post('/admin/elementos/store', [AdminController::class, 'storeElemento'])->name('admin.elementos.store');
         Route::put('/admin/elementos/{id}', [AdminController::class, 'updateElemento'])->name('admin.elementos.update');
         Route::get('admin/elementos/{id}/edit', [AdminController::class, 'edit'])->name('admin.elementos.edit');
         Route::delete('/admin/elementos/{id}', [AdminController::class, 'destroyElemento'])->name('admin.elementos.destroy');
    });

    // Luego, la ruta POST para manejar el envío del formulario y almacenar el elemento
    Route::post('/admin/elementos/store', [AdminController::class, 'storeElemento'])->name('admin.elementos.store');

    // Route::get('/admin/panel', [AdminController::class, 'panel'])->name('admin.panel');
    Route::put('/admin/elementos/{id}', [AdminController::class, 'updateElemento'])->name('admin.elementos.update');
    Route::get('admin/elementos/{id}/edit', [AdminController::class, 'edit'])->name('admin.elementos.edit');
    Route::delete('/admin/elementos/{id}', [AdminController::class, 'destroyElemento'])->name('admin.elementos.destroy');

    // Rutas del vigilante para funciones  específicos
    Route::middleware(CheckRole::class . ':2')->group(function () {
        Route::get('control/panel', [VigilanteController::class, 'mostrarVistaControl'])->name('control.panel');

        Route::get('/vigilante/buscar', [VigilanteController::class, 'buscarPorDocumento'])
            ->name('vigilante.buscar');
        // Rutas para eliminar elementos del contenedor en sub_control_ingresos
        Route::delete('/vigilante/sub_control_ingreso/{id}', [VigilanteController::class, 'destroy']);

        // Ruta para actualizar el estado de un registro a "Cerrado"
        Route::put('/vigilante/control_ingreso/{id}/cerrar', [VigilanteController::class, 'cerrarRegistro'])
            ->name('control_ingreso.cerrar');

        Route::post('/nuevoRegistro', [VigilanteController::class, 'nuevoRegistro'])->name('vigilante.registro');

        Route::post('/sub_control_ingreso', [VigilanteController::class, 'registrarElementoEnSubControl'])->name('sub_control_ingreso.store');

        Route::get('/vigilante/elementos/{registroId}', [VigilanteController::class, 'obtenerElementosPorRegistro'])
            ->name('vigilante.elementos');
    });

    // Panel de usuario para roles 3, 4 y 5
    Route::middleware(CheckRole::class . ':3,4,5')->group(function () {
        Route::get('user/panel', [UserController::class, 'userPanel'])->name('user.panel');
    });

    // Rutas generales de perfil y usuario
    Route::get('/editProfile', [AuthController::class, 'showEditProfile'])->name('editProfile');
    Route::get('/usuario/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/usuario/panel', [UserController::class, 'mostrarPerfil']);
    Route::get('/perfil', [UserController::class, 'mostrarPerfil'])->name('perfil');

    // Nueva ruta para obtener detalles del usuario por documento
    Route::get('/usuario/detalles/{documento}', [ReportesIngresosController::class, 'obtenerDetallesUsuario'])
        ->name('usuario.detalles');

    Route::get('/usuario/{documento}/elementos', [ReportesIngresosController::class, 'obtenerElementosUsuario'])
        ->name('usuario.elementos');
});

// Rutas específicas para admin (a completar si es necesario)
// Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    // Nueva ruta para consulta masiva de usuarios
    // Route::get('/admin/usuarios/consulta-masiva', [AdminController::class, 'consultaMasiva'])
        // ->name('admin.usuarios.consultaMasiva');
// });

// Ruta para la página About
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::post('/admin/usuarios/actualizar/{id}', [AdminController::class, 'actualizarUsuario'])
    ->name('admin.usuarios.actualizar');
