<div class="content-header py-0">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12 d-flex">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 col-12">
          <span class="text-dark d-inline h4 pt-2 mr-0">
            <a href="{{ route('almacen.compras_confirmadas') }}" title="">
              <small><i class="ion ion-ios-color-filter-outline">&nbsp;</i></small>{{ __('PAQUETES CONFIRMADOS') }}
            </a>
          </span>
          
          <form class="form ml-3" action="{{ route('almacen.compras_confirmadas') }}">
            <div class="container">
              
              <div class="row">
                <div class="col-lg-11">
                  <div class="input-group input-group-sm">
              <input type="hidden" name="buscar" value="true">
              <input id="search" name="search" class="form-control form-control-navbar" type="search" placeholder="{{ __('Buscar') }}" aria-label="Search" value="{{ $search }}">
            </div>
              <div class="w-100"></div>
            <!-- ACOMODAR (Quisiera que estuviera debajo de esto)-->
              <div class="form" style="padding-top: 5px">
                <label for="start_date">{{ _('Desde:') }}&nbsp;</label>
              <input type="date" class="form-control-medium" id="start_date" name="start_date" title="Desde" placeholder="Desde" value="{{ $start_date }}">

                 <label for="finish_date">&nbsp;{{ _('Hasta:') }}&nbsp;</label>
              <input type="date" class="form-control-medium" id="finish_date" name="finish_date" title="Hasta" placeholder="Hasta" value="{{ $finish_date }}">
              </div>

              <!-- FIN ACOMODAR-->
                </div>

                <div class="col-1">
                  <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
                </div>
              </div>

            </div>
            

              
          </form>
        </nav>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>