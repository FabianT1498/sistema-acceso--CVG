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
	//Route::delete('visitantes/{id}/delete', 'VisitorController@destroy')->name('visitantes-destroy');

	/********             REPORT           **********/
	//Route::delete('reportes/{id}/delete', 'ReportController@destroy')->name('reportes-destroy');

	/********             Auto           **********/
	//Route::delete('autos/{id}/delete', 'AutoController@destroy')->name('autos-destroy');	
});

/*********************************************/

/*********************************************/
/*********************************************/
/********           TRABAJADOR      **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth', 'worker']], function () {
	Route::put('reporte/{id}/anular', 'ReportController@deny')->name('reportes.deny');
	Route::put('reporte/{id}/confirmar', 'ReportController@confirm')->name('reportes.confirm');
});

/*********************************************/


/*********************************************/
/*********************************************/
/********           RECEPCIONISTA   **********/
/*********************************************/
/*********************************************/

Route::group(['middleware' => ['auth', 'receptionist']], function () {
	
		/********           REPORT         **********/
		Route::get('reportes/crear', 'ReportController@create')->name('reportes.create');
		Route::post('reportes', 'ReportController@store')->name('reportes.store');
		Route::get('/generar_pase/{id}/pdf', 'ReportController@generatePDF')->name('reportes.generar_pase');

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
		// Route::post('/autos_visitante', 'VisitorController@getVisitorAutos');
		
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
	Route::resource('reportes', 'ReportController')->except(['destroy', 'edit', 'update', 'create', 'store']);
	
	/********           DASHBOARD         **********/
	Route::get('/dashboard', 'HomeController@index')->name('dashboard');

	Route::get('/home', function(){
		if(Auth::user())
		{	
			if (Auth::user()->role_id === 3){
				return redirect()->route('reportes.index');
			}

			return redirect()->route('dashboard');
		}
	})->name('home');
});