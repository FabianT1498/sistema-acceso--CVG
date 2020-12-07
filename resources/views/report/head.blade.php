<div class="content-header py-0">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12 d-flex">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 col-12 flex-wrap">
          <div class="row w-100 mb-md-2">
            <span class="text-dark d-inline h4 mr-3">
              <a href="{{ route('reportes.index') }}" title="">
                <small class="mr-md-2"><i class="nav-icon icon fa fa-file"></i></small>{{ __('Visitas') }}
              </a>
            </span>
            @if (Auth::user()->role_id === 4)
            <form class="d-flex mr-md-3" action="{{ route('reportes.create') }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="input-group" title="{{ __('Nuevo Registro') }}">
                  <div class="">
                    <button class="btn btn-primary btn-sm" type="submit">
                      <i class="icon ion-android-add px-1"></i>
                    </button>
                  </div>
                </div>
              </form>
            @endif
            <form id="searchForm" class="d-flex flex-column ml-3" action="{{ route('reportes.index') }}">
              <div class="row mb-md-2">
                <div class="form-inline ">
                  
                  <label class="mr-3" for="search">{{ _('Buscar:') }}</label>
                  <input 
                    type="text" 
                    class="form-control form-control-sm mr-3" 
                    id="search" 
                    name="search" 
                    placeholder="Buscar"
                    autocomplete="off"
                    value="{{ $search }}"
                  >

                  <label class="mr-3" for="statusSelect">Estatus:</label>
                  <select class="form-control form-control-sm" name="status_select" id="statusSelect">
                    <option value="TODAS">Todas</option>
                    <option value="POR CONFIRMAR" {{$status_select === "POR CONFIRMAR" ? 'selected' : '' }}>Por confirmar</option>
                    <option value="CANCELADA" {{$status_select === "CANCELADA" ? 'selected' : '' }}>Cancelada</option>
                    <option value="CONFIRMADA" {{ $status_select === "CONFIRMADA" ? 'selected' : '' }}>Confirmada</option>
                  </select>  
                </div>
              </div>
              <div class="row mb-md-2">
                <div class="form-inline">
                  <label class="mr-3" for="startDate">{{ _('Desde:') }}</label>
                  <input 
                    type="text" 
                    class="form-control form-control-sm mr-3" 
                    id="startDate" 
                    name="start_date" 
                    placeholder=""
                    value=""
                    autocomplete="off"
                  >

                  <label class="mr-3" for="startDate">{{ _('Hasta:') }}</label>
                  <input 
                    type="text" 
                    class="form-control form-control-sm mr-3" 
                    id="finishDate" 
                    name="finish_date" 
                    placeholder=""
                    value=""
                    autocomplete="off"
                  > 
                </div>
              </div>
              @if ($start_date !== '' && $finish_date !== '')
                <div class="row">
                  <p class="text-info">{{'Usted ha buscado desde el ' . $start_date . ' hasta el ' . $finish_date}}</p>
                </div>  
              @endif
            </form>
            <div class="row">
              <button 
                type="button" 
                class="btn btn-primary btn-circle btn-md"
                data-toggle="modal" 
                data-target="#helpModal"
              > 
                <i class="icon fa fa-question"></i>
              </button>

              <button type="button" id="searchBtn" class="btn btn-primary btn-circle btn-md ml-md-2"> 
                <i class="fas fa-search"></i>
              </button> 
            </div>
          </div>
        </nav>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@include('layouts.modal')