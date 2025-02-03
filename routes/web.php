<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Login\LoginController;
use App\Http\Controllers\Controles\ControlController;
use App\Http\Controllers\Backend\Roles\RolesController;
use App\Http\Controllers\Backend\Roles\PermisoController;
use App\Http\Controllers\Backend\Perfil\PerfilController;
use App\Http\Controllers\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Backend\Configuracion\ConfiguracionController;
use App\Http\Controllers\Backend\Procesos\ProcesosController;

// --- LOGIN ---

Route::get('/', [LoginController::class,'index'])->name('login');

Route::post('admin/login', [LoginController::class, 'login']);
Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// --- CONTROL WEB ---

Route::get('/panel', [ControlController::class,'indexRedireccionamiento'])->name('admin.panel');

// --- ROLES ---

Route::get('/admin/roles/index', [RolesController::class,'index'])->name('admin.roles.index');
Route::get('/admin/roles/tabla', [RolesController::class,'tablaRoles']);
Route::get('/admin/roles/lista/permisos/{id}', [RolesController::class,'vistaPermisos']);
Route::get('/admin/roles/permisos/tabla/{id}', [RolesController::class,'tablaRolesPermisos']);
Route::post('/admin/roles/permiso/borrar', [RolesController::class, 'borrarPermiso']);
Route::post('/admin/roles/permiso/agregar', [RolesController::class, 'agregarPermiso']);
Route::get('/admin/roles/permisos/lista', [RolesController::class,'listaTodosPermisos']);
Route::get('/admin/roles/permisos-todos/tabla', [RolesController::class,'tablaTodosPermisos']);
Route::post('/admin/roles/borrar-global', [RolesController::class, 'borrarRolGlobal']);

// --- PERMISOS A USUARIOS ---

Route::get('/admin/permisos/index', [PermisoController::class,'index'])->name('admin.permisos.index');
Route::get('/admin/permisos/tabla', [PermisoController::class,'tablaUsuarios']);
Route::post('/admin/permisos/nuevo-usuario', [PermisoController::class, 'nuevoUsuario']);
Route::post('/admin/permisos/info-usuario', [PermisoController::class, 'infoUsuario']);
Route::post('/admin/permisos/editar-usuario', [PermisoController::class, 'editarUsuario']);
Route::post('/admin/permisos/nuevo-rol', [PermisoController::class, 'nuevoRol']);
Route::post('/admin/permisos/extra-nuevo', [PermisoController::class, 'nuevoPermisoExtra']);
Route::post('/admin/permisos/extra-borrar', [PermisoController::class, 'borrarPermisoGlobal']);

// --- PERFIL DE USUARIO ---
Route::get('/admin/editar-perfil/index', [PerfilController::class,'indexEditarPerfil'])->name('admin.perfil');
Route::post('/admin/editar-perfil/actualizar', [PerfilController::class, 'editarUsuario']);

// --- SIN PERMISOS VISTA 403 ---
Route::get('sin-permisos', [ControlController::class,'indexSinPermiso'])->name('no.permisos.index');




// --- CONFIGURACION ---

// - Año
Route::get('/admin/anio/index', [ConfiguracionController::class,'vistaAnio'])->name('admin.anio.index');
Route::get('/admin/anio/tabla', [ConfiguracionController::class,'vistaAnioTabla']);
Route::post('/admin/anio/nuevo', [ConfiguracionController::class, 'nuevaAnio']);
Route::post('/admin/anio/informacion', [ConfiguracionController::class, 'informacionAnio']);
Route::post('/admin/anio/editar', [ConfiguracionController::class, 'actualizarAnio']);


// - Fuente
Route::get('/admin/fuente/index', [ConfiguracionController::class,'vistaFuente'])->name('admin.fuente.index');
Route::get('/admin/fuente/tabla', [ConfiguracionController::class,'vistaFuenteTabla']);
Route::post('/admin/fuente/nuevo', [ConfiguracionController::class, 'nuevaFuente']);
Route::post('/admin/fuente/informacion', [ConfiguracionController::class, 'informacionFuente']);
Route::post('/admin/fuente/editar', [ConfiguracionController::class, 'actualizarFuente']);
Route::post('/admin/fuente/mostrar', [ConfiguracionController::class, 'actualizarFuenteMostrar']);
Route::post('/admin/fuente/ocultar', [ConfiguracionController::class, 'actualizarFuenteOcultar']);



// - Empresa
Route::get('/admin/empresa/index', [ConfiguracionController::class,'vistaEmpresa'])->name('admin.empresa.index');
Route::get('/admin/empresa/tabla', [ConfiguracionController::class,'vistaEmpresaTabla']);
Route::post('/admin/empresa/nuevo', [ConfiguracionController::class, 'nuevaEmpresa']);
Route::post('/admin/empresa/informacion', [ConfiguracionController::class, 'informacionEmpresa']);
Route::post('/admin/empresa/editar', [ConfiguracionController::class, 'actualizarEmpresa']);

// --- NUEVO PROCESO ---
// - Nuevo Registros
Route::get('/admin/procesosnuevo/index', [ProcesosController::class,'vistaNuevoProceso'])->name('admin.proceso.index');
Route::post('/admin/procesosnuevo/nuevo', [ProcesosController::class, 'nuevoRegistroProceso']);


// --- LISTADO DE PROCESOS ---
Route::get('/admin/procesos/index', [ProcesosController::class,'vistaListadoProcesos'])->name('admin.listado.proceso.index');
Route::get('/admin/procesos/tabla/{id}', [ProcesosController::class,'tablaListadoProcesos']);
Route::post('/admin/procesos/informacion', [ProcesosController::class, 'informacionProceso']);



