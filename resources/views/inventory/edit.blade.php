@extends('layouts.app')

@section('mascss')
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection

@section('masjs')
  <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/wharehouse.js') }}"></script>
  <script src="{{ asset('js/inventory.js') }}"></script>
@endsection

@section('migasdepan')
    @if (Auth::user()->role->name == "ANALISTA")  
      <a href="{{ route('home') }}">{{ __('ALMACEN') }}</a>
    @else
      <a href="{{ route('almacen') }}">{{ __('ALMACEN') }}</a>
    @endif
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('INVENTARIOS') }}
     <span class="text-success">({{ __('Editar') }})</span>
@endsection

@section('content')
  <input type="hidden" id="edit" value="{{ App\Http\Controllers\WebController::EDIT }}">
  <input type="hidden" id="vista" value="{{ $vista }}">
  @include('layouts.navbar')

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('layouts.sidebar')
  </aside>
  <!-- Fin Main Sidebar Container -->
  <!-- Content Header (Page header) -->
  @include('inventory.head')
  <!-- /.content-header -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div class="col-12 col-md-10">
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('inventarios.update', $registro) }}"  role="form">
                @csrf
                @method('PUT')
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="description">{{ _('Inventario') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="description" name="description" type="text" class="form-control" placeholder="{{ __('Descripción') }}" value="{{ old('description', $registro->description) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="location">{{ _('Almacen') }}&nbsp;<sup class="text-danger"></sup></label>
                    <input id="location" name="location" type="text" class="form-control" placeholder="{{ __('Descripción') }}" value="{{ old('location', $registro->location->name) }}" disabled>
                  </div>
                  <div class="container">
                    <div class="row">
                      <div class="col-12">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th><span class="display-4">{{ __('Productos') }}</span></th>
                            </tr>
                          </thead>
                          <tbody>
                            <input type="hidden" id="strUrl" value="{{ route('actualizar_reg') }}">
                            <input type="hidden" id="reg_updated" value="{{ __('Actualizado') }}">
                            @foreach ($items as $item)
                              <tr>
                                <td>
                                  <div class="container-">
                                    <div class="row">
                                      <div class="col-12">
                                        <div class="pl-3">
                                          @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE)
                                            <input class="form-check-input checkImprimir" title="{{ __('Imprimir este Registro') }}" type="checkbox" id="chkImprimir-{{ $item->id }}" name="chkImprimir[]" value="{{ $item->id }}" checked="true">
                                          @endif
                                          <label class="form-check-label h3" for="chkImprimir-{{ $item->id }}">
                                            <strong>{{ $item->description }}</strong>
                                          </label>
                                          
                                          @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE && (Auth::user()->role->name == "SUPERADMIN" || Auth::user()->role->name == "ADMIN"))
                                            @php
                                                $mensaje = __('Esta acción no se puede deshacer. ¿Desea continuar?')
                                              @endphp
                                            @if (count($items)===1)
                                              @php
                                                $mensaje = __('Se eliminará el Item y el Inventario. \nEsta acción no se puede deshacer. \n¿Desea continuar?')
                                              @endphp
                                            @endif
                                            &nbsp;<sup><a title="{{ __('Eliminar') }}" href="#" onclick="
                                                event.preventDefault();
                                                confirm('{{ $mensaje }}') ?
                                                  document.getElementById('frmEliminarItem_{{ $item->id }}').submit() : false;
                                              "
                                              class="btn btn-small btn-outline-danger border-0 py-0">
                                              <i class="fa fa-trash"></i>
                                            </a></sup>
                                            <form></form> <!--OJO SI SE QUITA ESTE FORM NO APARECE EL DE ABAJO EN LA PRIMERA ITERACCIÓN  DE Items (HAY QUE REVISAR ¿POR QUE?)-->
                                            <form method="POST" id="frmEliminarItem_{{ $item->id }}" action="{{ route('inventarios.destroy.item', $registro->id) }}" class="d-none">
                                                @method('DELETE')
                                                @csrf
                                                <input type="hidden" name="search" value="{{ $search }}">
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <input type="hidden" name="count_items" value="{{ count($items) }}">
                                            </form>
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                    @php $total = 0; $total_inventory = 0; @endphp
                                    @foreach ($details as $detail)
                                      @if ($item->id === $detail->item_id)
                                        @php
                                          $total += $detail->quantity_stock;
                                          $total_inventory += $detail->quantity_inventory ? $detail->quantity_inventory : 0;
                                        @endphp
                                        <div class="row bg-info border align-items-center">
                                          <div id="message-success-{{ $detail->id }}" class="col-12 text-warning text-right">
                                          </div>
                                          <div class="col-12 col-lg-6">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <strong>{{ __('Lote:') }}</strong>
                                              {{ $detail->invoice_detail->invoice->description }}
                                              </label>
                                            </div>
                                          </div>
                                          <div class="col-12 col-lg-2">
                                            <strong>{{ __('Stock') }}</strong>:&nbsp;
                                            <span id="quantity_{{ $detail->id }}">{{ $detail->quantity_stock }}</span>
                                            @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE)
                                              <button id="btnAsignarStock_{{ $detail->id }}" type="button" class="btn-asignar-stock btn btn-small btn-outline-primary border-0 py-0" title="{{ __('Igualar Valores') }}">
                                                <i class="ion-android-arrow-dropright"></i>
                                                <i class="ion-android-arrow-dropright"></i>
                                              </button>
                                            @endif
                                          </div>
                                          <div class="col-12 col-lg-2 mb-1">
                                            <div class="form-group pt-3 d-inline">
                                              <strong>{{ __('Existencia') }}</strong>
                                              @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE)
                                                <input type="text" id="quantity_stock_{{ $detail->id }}" name="quantity_stock_{{ $detail->id }}" class="form-control quantity quantity-inventory" value="{{ old('quantity_inventory', $detail->quantity_inventory)   }}">
                                                @else
                                                  <span>{{ old('quantity_inventory', $detail->quantity_inventory)   }}</span>
                                                @endif
                                            </div>
                                          </div>
                                          @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE && Auth::user()->role->name != "ANALISTA")
                                            <div class="col-12 col-lg-2 mt-3">
                                              <button id="btnEditar_{{ $detail->id }}" type="button" class="btn btn-outline-secondary btn-small p-0 px-1 btn-editar" title="{{ __('Agregar Observación') }}">
                                                <i class="ion-compose h4"></i>
                                              </button>
                                              <button id="btnActualizar_{{ $detail->id }}" type="button" class="btn btn-outline-secondary btn-small p-0 px-1 btn-actualizar" title="{{ __('Actualizar Registro') }}">
                                                <i class="ion-archive h4"></i>
                                              </button>
                                            </div>
                                          @endif
                                          <div class="col-12 pb-1 @if(!old('note', $detail->note)) collapse @endif" id="areaNote_{{ $detail->id }}">
                                            <div class="row">
                                              <div class="col-12 col-md-2">
                                                <label for="">{{ __('Observación') }}:</label>
                                              </div>
                                              <div class="col-12 col-md-10">
                                                @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE)
                                                  <input type="text" id="note_{{ $detail->id }}" name="note_{{ $detail->id }}" class="form-control" value="{{ old('note', $detail->note) }}">
                                                @else
                                                  <span>{{ old('note', $detail->note) }}</span>
                                                @endif
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      @endif
                                    @endforeach
                                    <div class="row bg-secondary justify-content-center">
                                      <div class="col-12 col-lg-3">Total Stock: {{ $total }}</div>
                                      <div class="col-12 col-lg-3">Total Existencia: <span id="total_inventory">{{ $total_inventory }} </span></div>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                        <div class="form-check text-primary">
                          @if (Auth::user()->role->name != "ANALISTA")
                            @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE)
                            <input class="form-check-input" style="width: 20px;height: 20px;margin-top:.8rem;" type="checkbox" value="finalizado" id="finish_invenroty" name="finish_invenroty">
                            <label class="form-check-label ml-3" for="finish_invenroty">
                              <strong class="h2">{{ __('Finalizar Inventario y Actualizar Stock') }}</strong>
                            </label>
                            @else
                              <center>
                              <label class="form-check-label bg-warning px-3" for="finish_invenroty">
                                <strong class="h2">
                                  {{ __('Inventario Finalizado el') }}
                                </strong>
                                  &nbsp;<h2 class="d-inline text-danger">{{ date('d/m/yy', strtotime($registro->finish_date)) }}</h2>
                              </label>
                            </center>
                            @endif  
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                @if($registro->state_id==App\Http\Controllers\InventoryController::PENDIENTE && Auth::user()->role->name != "ANALISTA")
                  <div class="card-footer">
                    <button type="submit" class="btn btn-success align-items-center">
                      <i class="ion-archive h1"></i>&nbsp;&nbsp;
                      <div>
                        {{ __('Actualizar Todo') }}
                      </div>
                    </button>
                    <a title="{{ __('Imprimir') }}" href="#" onclick="
                              event.preventDefault();
                              document.getElementById('frm_imprimir_{{ $registro->id }}').submit();" class="btn btn-primary align-items-center">
                      <i class="ion-printer h1"></i>
                      <div>
                        {{ __('Imprimir Inventario') }}
                      </div>
                    </a>
                    <form target="_blank" method="GET" id="frm_imprimir_{{ $registro->id }}" action="{{ route('inventarios.printing', $registro->id) }}" class="d-none">
                        <input type="hidden" id="chkRegistrosImprimir" name="chkRegistrosImprimir" value="">
                    </form>
                  </div>
                @endif
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
