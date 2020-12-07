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
/*********************************************/
/********           RECEPCIONISTA   **********/
/*********************************************/
/*********************************************/

Route::group(['middleware' => ['auth', 'receptionist']], function () {
	
		/********           REPORT         **********/
		Route::resource('reportes', 'ReportController')->except(['destroy', 'edit', 'update']);
		Route::get('mis-visitas', 'ReportController@myVisits')->name('reportes.myVisits');
		Route::get('/generar_pase/{id}/pdf', 'ReportController@generatePDF')->name('reportes.generar_pase');
		Route::post('/departamentos', 'ReportController@getDepartments');
		Route::post('/edificios', 'ReportController@getBuildings');

		/********           WORKER         **********/	
		Route::post('trabajador', 'WorkerController@getWorker');

		/********           AUTO         **********/	
		Route::resource('autos', 'AutoController')->except(['destroy']);
		Route::post('/autos_modelos', 'AutoController@getAutoModels');
		Route::post('/autos_marcas', 'AutoController@getAutoBrands');
		Route::post('/auto', 'AutoController@getAuto');

		/********           VISITOR         **********/	
		Route::resource('visitantes', 'VisitorController')->except(['destroy']);
		Route::post('/visitante', 'VisitorController@getVisitor');
	}
);

/*********************************************/


/*********************************************/
/*********************************************/
/********            AUTH           **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth']], function () {

	/********           REPORT         **********/	
	Route::get('mis-visitas', 'ReportController@myVisits')->name('reportes.myVisits');
	Route::get('reportes/{reporte}', 'ReportController@show')->name('reportes.show');
	Route::put('reporte/{id}/anular', 'ReportController@denyVisit')->name('reportes.denyVisit');
	Route::put('reporte/{id}/confirmar', 'ReportController@confirmVisit')->name('reportes.confirmVisit');
	
	Route::get('/home', function(){
		if(Auth::user())
		{	
			if (Auth::user()->role_id === 3){
				return redirect()->route('reportes.myVisits');
			} else if (Auth::user()->role_id === 4){
				return redirect()->route('reportes.index');
			}

			return redirect()->route('dashboard');
		}
	})->name('home');
});