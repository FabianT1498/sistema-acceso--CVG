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
	if(Auth::user())
	{
		if(Auth::user()->role->name == "ALMACENISTA")
		{
			return redirect()->route('almacen');
		}
	}
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
	Route::get('usuarios/{id}/delete', 'UserController@destroy')->name('usuarios-destroy');

	/*********************************************/
	/*********************************************/
	/********           GENERAL         **********/
	/*********************************************/
	/*********************************************/

	Route::get('/general', 'GeneralController@index')->name('general');

	/********           COMPANY         **********/
	Route::resource('empresas', 'CompanyController')->except(['show']);


	/********           PROVIDER         **********/
	Route::resource('proveedores', 'ProviderController')->except(['show']);

	/********           LOICATION         **********/
	Route::resource('localidades', 'LocationController')->except(['show']);

	/*********************************************/
	/*********************************************/
	/********           ITEMS         **********/
	/*********************************************/
	/*********************************************/

	Route::get('/items', 'ItemsController@index')->name('items');

	/********           GROUP         **********/
	Route::resource('grupos', 'GroupController')->except(['show']);

	/********           SUBGROUP         **********/
	Route::resource('sub_grupos', 'SubGroupController')->except(['show']);

	/********           TYPE         **********/
	Route::resource('tipos', 'TypeController')->except(['show']);

	/********           PRESENTATION         **********/
	Route::resource('presentaciones', 'PresentationController')->except(['show']);

	/********           ITEM         **********/
	Route::resource('productos', 'ItemController')->except(['show']);



	/*********************************************/
	/*********************************************/
	/********           COINTRA         **********/
	/*********************************************/
	/*********************************************/

	/********           INVOICE         **********/
	Route::resource('compras', 'InvoiceController')->except(['show', 'edit', 'store', 'update']);

	/********           DELIVERY         **********/
	Route::resource('entregas', 'DeliveryController')->except(['show', 'edit', 'store']);


	/********             INVENTORY           **********/
	Route::delete('inventarios/{inventory}/destroy', 'InventoryController@destroy')->name('inventarios.destroy');
	Route::delete('inventarios/{inventory}/destroy-item', 'InventoryController@destroyRegister')->name('inventarios.destroy.item');

	Route::get('visitantes/{id}/delete', 'VisitorController@destroy')->name('visitantes-destroy');

});
/*********************************************/


/*********************************************/
/*********************************************/
/********           STORER          **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth', 'storer']], function () {

	/*********************************************/
	/*********************************************/
	/********          WAREHOUSE        **********/
	/*********************************************/
	/*********************************************/
	Route::get('/almacen', 'WarehouseController@index')->name('almacen');
	Route::get('/recepcion-paquete', 'WarehouseController@package_reception')->name('recepcion-paquetes');
	Route::get('/recepcion-paquete/{id}/confirmar', 'WarehouseController@confirm_reception')->name('recepcion-confirmar');
	Route::put('/recepcion-paquete/{id}/confirmacion', 'WarehouseController@confirming_reception')->name('recepcion-confirmada');
	Route::get('/inventarios-almacen', 'WarehouseController@pending_inventory')->name('inventarios-almacen');
	Route::get('/inventarios-almacen/{id}/confirmar', 'WarehouseController@confirm_inventory')->name('inventarios-confirmar');
	Route::put('/inventarios-almacen/{id}/confirmacion', 'WarehouseController@confirming_inventory')->name('inventarios-confirmada');


	/********           INVENTORY         **********/
	Route::resource('inventarios', 'InventoryController')->parameters(['inventarios' => 'inventory'])->except(['destroy', 'edit', 'show', 'create', 'store']);
	Route::post('inventarios/registro', 'InventoryController@updateRegister')->name('actualizar_reg');
	Route::get('inventarios/{inventory}/imprimir', 'InventoryController@printRegister')->name('inventarios.printing');

});

/*********************************************/


/*********************************************/
/*********************************************/
/********          ANALYST        **********/
/*********************************************/
/*********************************************/

Route::group(['middleware' => ['auth', 'analyst']], function () {
/*********************************************/
/*********************************************/
/********           COINTRA         **********/
/*********************************************/
/*********************************************/
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

/********           INVOICE         **********/
Route::resource('compras', 'InvoiceController')->except([ 'destroy', 'show']);

/********           DELIVERY         **********/
Route::resource('entregas', 'DeliveryController')->except(['update', 'destroy', 'show']);
Route::get('entregas/{delivery}/imprimir', 'DeliveryController@printRegister')->name('entregas.printing');
Route::get('entregas/imprimirlst', 'DeliveryController@printList')->name('entreta.imprimirlst');



/********             STOCK           **********/
Route::get('/stocks', 'StockController@index')->name('stocks');
//Route::get('/stocks/{id}/item', 'StockController@edit')->name('stocks.edit');
Route::get('/stocks/{id}/items', 'StockController@update')->name('stocks.update');
Route::get('/item-stock/{id}', 'StockController@stock_item')->name('stocks.stock_item');


});

/*********************************************/


/*********************************************/
/*********************************************/
/********            AUTH           **********/
/*********************************************/
/*********************************************/
Route::group(['middleware' => ['auth']], function () {

	/********           WAREHOUSE         **********/
Route::get('/paquetes-confirmados', 'WarehouseController@confirmed_invoices')->name('almacen.compras_confirmadas');
Route::get('/paquetes-confirmados/{id}/show', 'WarehouseController@show_confirmed_invoices')->name('almacen.compra_confirmada');

/********           DELIVERY         **********/
Route::get('/entregas-items', 'DeliveryController@items_delivery');
Route::get('/cantidad/{id}/items-disponibles', 'DeliveryController@item_quantity');

/********           INVOICE         **********/
Route::get('/compras-items', 'InvoiceController@items_invoice');

/********           INVENTORY         **********/
Route::resource('inventarios', 'InventoryController')->parameters(['inventarios' => 'inventory'])->except(['update', 'destroy']);
Route::get('/inventory-items', 'InventoryController@items_inventory');

/********           VISITOR         **********/
Route::resource('visitantes', 'VisitorController')->except(['destroy']);

Route::get('/home', function(){
	if(Auth::user())
	{
		if(Auth::user()->role->name === "ALMACENISTA")
		{
			return redirect()->route('almacen');
		}
		
		return redirect()->route('dashboard');
	}
})->name('home');
});

/*********************************************/










/*********************************************/
/*********************************************/
/********       BUSQUEDAS AJAX      **********/
/*********************************************/
/*********************************************/

Route::get('grupo/{id}/cargarSubGrupos', 'SubGroupController@loadSubGroups');
Route::get('sub_grupo/{id}/cargarTipos', 'TypeController@loadTypes');
/*                                                       */

