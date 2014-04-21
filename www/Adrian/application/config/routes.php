<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
//pagina por defecto
$route['default_controller'] = 'principal';

$route['mensajes/recarga'] = 'mensajes/recarga';
$route['mensajes/cargaConver'] = 'mensajes/cargaConver';
$route['mensajes/verConversacion/(:any)'] = 'mensajes/verConversacion/$1';

//Carga el método create() de usuarios
$route['nuevoUsuario'] = 'usuarios/formCrear';

//Carga el index de usuarios
$route['usuarios'] = 'usuarios';
$route['usuarios/borrar'] = 'usuarios/borrar';
$route['usuarios/crear'] = 'usuarios/crearDesdeTabla';
$route['usuarios/modificar'] = 'usuarios/modificar';

$route['mensajes'] = 'mensajes';
$route['mensajes/enviar'] = 'mensajes/enviar';

$route['miPerfil'] = 'principal/cambiarDatos';

/* End of file routes.php */
/* Location: ./application/config/routes.php */