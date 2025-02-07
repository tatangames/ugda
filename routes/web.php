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
Route::post('/admin/procesos/editar', [ProcesosController::class, 'editarProceso']);
Route::post('/admin/procesos/borrar', [ProcesosController::class, 'borrarProcesoCompleto']);
// Informacion de los documentos que tiene cada Tabla
Route::post('/admin/procesos/informacion/documentos', [ProcesosController::class, 'informacionDocumentos']);
// Consolidado Final
Route::get('/admin/procesos/docpdf/consolidado/{id}', [ProcesosController::class,'consolidadoProcesoFinal']);



// --- VISTAS DE PROCESOS ---
// - Solicitantes
Route::get('/admin/prosolicitante/index/{id}', [ProcesosController::class,'indexProcesoSolicitante']);
Route::get('/admin/prosolicitante/tabla/{id}', [ProcesosController::class,'tablaProcesoSolicitante']);
Route::post('/admin/prosolicitante/nuevo', [ProcesosController::class, 'nuevoProcesoSolicitante']);
Route::post('/admin/prosolicitante/borrar', [ProcesosController::class, 'borrarProcesoSolicitante']);
Route::post('/admin/prosolicitante/informacion', [ProcesosController::class, 'informacionProcesoSolicitante']);
Route::post('/admin/prosolicitante/editar', [ProcesosController::class, 'editarProcesoSolicitante']);
Route::get('/admin/prosolicitante/visualizar/documento/{id}', [ProcesosController::class,'visualizarDocSolicitante']);
Route::get('/admin/prosolicitante/descargar/documento/{id}', [ProcesosController::class,'descargarDocSolicitante']);

// - UCP
Route::get('/admin/proucp/index/{id}', [ProcesosController::class,'indexProcesoUcp']);
Route::get('/admin/proucp/tabla/{id}', [ProcesosController::class,'tablaProcesoUcp']);
Route::post('/admin/proucp/nuevo', [ProcesosController::class, 'nuevoProcesoUcp']);
Route::post('/admin/proucp/borrar', [ProcesosController::class, 'borrarProcesoUcp']);
Route::post('/admin/proucp/informacion', [ProcesosController::class, 'informacionProcesoUcp']);
Route::post('/admin/proucp/editar', [ProcesosController::class, 'editarProcesoUcp']);
Route::get('/admin/proucp/visualizar/documento/{id}', [ProcesosController::class,'visualizarDocUcp']);
Route::get('/admin/proucp/descargar/documento/{id}', [ProcesosController::class,'descargarDocUcp']);

// - Empresas
Route::get('/admin/proucp/empresas/index/{id}', [ProcesosController::class,'indexProcesoUcpEmpresas']);
Route::get('/admin/proucp/empresas/tabla/{id}', [ProcesosController::class,'tablaProcesoUcpEmpresas']);
Route::post('/admin/proucp/empresas/nuevo', [ProcesosController::class, 'nuevoProcesoUcpEmpresas']);
Route::post('/admin/proucp/empresas/borrar', [ProcesosController::class, 'borrarProcesoUcpEmpresas']);
Route::post('/admin/proucp/empresas/informacion', [ProcesosController::class, 'informacionProcesoUcpEmpresas']);
Route::post('/admin/proucp/empresas/editar', [ProcesosController::class, 'editarProcesoUcpEmpresas']);

// - Administrador
Route::get('/admin/proadministrador/index/{id}', [ProcesosController::class,'indexProcesoAdministrador']);
Route::get('/admin/proadministrador/tabla/{id}', [ProcesosController::class,'tablaProcesoAdministrador']);
Route::post('/admin/proadministrador/nuevo', [ProcesosController::class, 'nuevoProcesoAdministrador']);
Route::post('/admin/proadministrador/borrar', [ProcesosController::class, 'borrarProcesoAdministrador']);
Route::post('/admin/proadministrador/informacion', [ProcesosController::class, 'informacionProcesoAdministrador']);
Route::post('/admin/proadministrador/editar', [ProcesosController::class, 'editarProcesoAdministrador']);
Route::get('/admin/proadministrador/visualizar/documento/{id}', [ProcesosController::class,'visualizarDocAdministrador']);
Route::get('/admin/proadministrador/descargar/documento/{id}', [ProcesosController::class,'descargarDocAdministrador']);


// --- BUSCADOR ---
Route::get('/admin/buscador/index', [ProcesosController::class,'indexBuscador'])->name('admin.buscador.index');
Route::post('/admin/buscador/archivos', [ProcesosController::class, 'buscadorArchivos']);
// * Carga el item seleccionado
Route::get('/admin/buscador/encontrado/index/{id}', [ProcesosController::class,'indexBuscadorItemEncontrado']);
Route::get('/admin/buscador/encontrado/tabla/{id}', [ProcesosController::class,'tablaBuscadorItemEncontrado']);


// --- FILTROS ---
Route::get('/admin/filtro/index', [ConfiguracionController::class,'indexVistaFiltros'])->name('admin.filtros.index');
// -- lista procesos sin consolidar
Route::get('/admin/filtro/busqueda/noconsolidado/{idanio}', [ConfiguracionController::class,'indexBusquedaNoConsolidado']);
Route::get('/admin/filtro/busqueda/noconsolidado/tabla/{idanio}', [ConfiguracionController::class,'tablaBusquedaNoConsolidado']);

//- Falta al menos 1 expediente
Route::get('/admin/filtro/busqueda/faltaexpediente', [ConfiguracionController::class,'indexFiltroFaltaExpedientes']);
Route::get('/admin/filtro/busqueda/faltaexpediente/tabla', [ConfiguracionController::class,'tablaFiltroFaltaExpedientes']);

// Procesos por año ya consolidados
Route::get('/admin/filtro/busqueda/yaconsolidados/{id}', [ConfiguracionController::class,'indexFiltroYaConsolidados']);
Route::get('/admin/filtro/busqueda/yaconsolidados/tabla/{id}', [ConfiguracionController::class,'tablaFiltroYaConsolidados']);


