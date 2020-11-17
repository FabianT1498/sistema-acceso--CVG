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

	/*********************************************/
	/*********************************************/
	/********           COINTRA         **********/
	/*********************************************/
	/*********************************************/

	/********             VISITOR           **********/
	Route::delete('visitantes/{id}/delete', 'VisitorController@destroy')->name('visitantes-destroy');

	/********             REPORT           **********/
	Route::delete('reportes/{id}/delete', 'ReportController@destroy')->name('reportes-destroy');

	/********             Auto           **********/
	Route::delete('autos/{id}/delete', 'AutoController@destroy')->name('autos-destroy');	
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
	
		/********           WORKER         **********/	
		Route::post('lista_trabajadores', 'WorkerController@getWorkers');
});

/*********************************************/


/*********************************************/
/*********************************************/
/********            AUTH           **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth']], function () {

	/*********************************************/
	/*********************************************/
	/********           COINVI         **********/
	/*********************************************/
	/*********************************************/
	Route::get('/dashboard', 'HomeController@index')->name('dashboard');


	/********           VISITOR         **********/
	Route::resource('visitantes', 'VisitorController')->except(['destroy']);
	Route::post('/lista_visitantes', 'VisitorController@getVisitors');
	Route::post('/autos_visitante', 'VisitorController@getVisitorAutos');

	/********           REPORT         **********/
	Route::resource('reportes', 'ReportController')->except(['destroy']);
	Route::get('/generar_pase/{id}/pdf', 'ReportController@generatePDF')->name('reportes.generar_pase');

	/********           AUTO         **********/
	Route::resource('autos', 'AutoController')->except(['destroy']);
	Route::post('/autos_modelos', 'AutoController@getAutoModels');

	
	Route::get('/home', function(){
		if(Auth::user())
		{	
			return redirect()->route('dashboard');
		}
	})->name('home');
		
});