<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect()->route('home');
});

Auth::routes(['register' => false, 'reset' => false]);

/*********************************************/
/*********************************************/
/********         SUPER ADMIN       **********/
/*********************************************/
/*********************************************/

/*********************************************/


/*********************************************/
/*********************************************/
/********           ADMIN         **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth', 'admin']], function () {

	/********           USER         **********/
	Route::resource('usuarios', 'UserController')->except(['destroy']);
	Route::delete('usuarios/{usuario}/delete', 'UserController@destroy')->name('usuarios-destroy');
	Route::put('usuarios/{usuario}/restore', 'UserController@restore')->name('usuarios-restore');
	Route::post('/username', 'UserController@getUsername');
	
	/********           DASHBOARD         **********/
	Route::get('/dashboard', 'HomeController@index')->name('dashboard');

	/*************        PASES  **************** */
	Route::get('reportes/', 'ReportController@index')->name('reportes.index');
	Route::get('reportes/{reporte}', 'ReportController@show')->name('reportes.show');
});

/*********************************************/

/*********************************************/
/*********************************************/
/********           TRABAJADOR      **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth', 'worker']], function () {

});

/*********************************************/
/*********************************************/
/* RECEPCIONISTA BASE Y DEPARTAMENTO *********/
/*********************************************/
/*********************************************/

Route::group(['middleware' => ['auth', 'base_receptionist']], function () {
	
	/********           VISIT         **********/
	Route::get('visitas', 'VisitController@index')->name('visitas.index');
	
	/*************      PASES ************** */
	Route::get('/generar_pase/{id}/pdf', 'ReportController@generatePDF')->name('reportes.generar_pase');

	/********           AUTO         **********/	
	Route::resource('autos', 'AutoController')->except(['destroy']);
	Route::post('/autos_modelos', 'AutoController@getAutoModels');
	Route::post('/autos_marcas', 'AutoController@getAutoBrands');
	Route::post('/auto', 'AutoController@getAuto');
});

/*********************************************/
/*********************************************/
/******** RECEPCIONISTA DEPARTAMENTO *********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth', 'department_recepcionist']], function () {

	/********           WORKER         **********/	
	Route::post('trabajador', 'WorkerController@getWorker');
});

/*********************************************/


/*********************************************/
/*********************************************/
/********            AUTH           **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth']], function () {

	/********           VISIT         **********/
	Route::resource('visitas', 'VisitController')->except(['destroy', 'index']);
	Route::get('mis-visitas/{status?}', 'VisitController@myVisits')->name('mis_visitas');
	Route::put('visita/{id}/anular', 'VisitController@denyVisit')->name('visitas.denyVisit');
	Route::put('visita/{id}/confirmar', 'VisitController@confirmVisit')->name('visitas.confirmVisit');
	Route::post('/visitas-por-confirmar', 'VisitController@getVisitsByConfirm');

	/********           VISITOR         **********/	
	Route::resource('visitantes', 'VisitorController')->except(['destroy']);
	Route::post('/visitante', 'VisitorController@getVisitor');

	/******  AJAX REQUESTS ********/
	Route::post('/departamentos', 'VisitController@getDepartments');
	Route::post('/edificios', 'VisitController@getBuildings');	
	
	Route::get('/home', function(){
		if(Auth::user())
		{	
			if (Auth::user()->role_id === 3){
				return redirect()->route('mis_visitas');
			} else if (Auth::user()->role_id === 4 || Auth::user()->role_id === 5){
				return redirect()->route('visitas.index');
			}

			return redirect()->route('dashboard');
		}
	})->name('home');
});