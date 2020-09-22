<div class="content-header py-0">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12 d-flex">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 col-12">
          <span class="text-dark d-inline h4 pt-2 mr-0">
            <a href="{{ route('entregas.index') }}" title="">
              <small><i class="ion ion-ios-color-filter-outline">&nbsp;</i></small>{{ __('ENTREGAS') }}
            </a>
          </span>
          <form class="form-inline ml-0" action="{{ route('entregas.create') }}">
            <input type="hidden" name="search" value="{{ $search }}">
            <input type="hidden" name="start_date" value="{{ $start_date }}">
            <input type="hidden" name="finish_date" value="{{ $finish_date }}">
            <input type="hidden" name="trashed" id="trashed" value="{{ $trashed }}">
            <input type="hidden" name="group_id" value="{{ $group_id }}">
            @if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN") 
            <input type="hidden" name="analyst_id" value="{{ $analyst_id }}">                
            @endif
            <div class="input-group ml-3" title="{{ __('Nuevo Registro') }}">
              <div class="">
                <button class="btn btn-primary btn-sm" type="submit">
                  <i class="icon ion-android-add px-1"></i>
                </button>
              </div>
            </div>
          </form>
          <div class="container">
          <form class="form ml-3" action="{{ route('entregas.index') }}">
              
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
              @if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="{{ ($trashed) ? $trashed : 0 }}" id="check_trashed" @if($trashed) checked="true" @endif  id="check_trashed" name="trashed">
                <label class="form-check-label" for="check_trashed">
                  Eliminados
                </label>
              </div>
              @endif
                </div>
              </div>
              
              <div class="row">
                <div class="col-12">
                  <div class="d-block">
                    <div class="d-inline">
                      Grupo:
                      <select id="select_group" name="group_id">
                        @if (!$group_id)
                        <option value="0" selected>TODOS</option>  
                        @else
                        <option value="0">TODOS</option>  
                        @endif
                        @foreach ($groups as $group)
                        @if ($group->id == $group_id)
                          <option value="{{ $group->id }}" selected>{{ $group->name }}</option>}
                        @else
                          <option value="{{ $group->id }}">{{ $group->name }}</option>}
                        @endif
                        @endforeach
                      </select>

                      Empresa:
                      <select id="select_company" name="company_id">
                        @if (!$company_id)
                        <option value="0" selected>TODAS</option>  
                        @else
                        <option value="0">TODAS</option>  
                        @endif
                        @foreach ($companies as $company)
                        @if ($company->id == $company_id)
                          <option value="{{ $company->id }}" selected>{{ $company->name }}</option>}
                        @else
                          <option value="{{ $company->id }}">{{ $company->name }}</option>}
                        @endif
                        @endforeach
                      </select>

                    </div>

                    @if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
                    <div class="d-inline">
                      Analista:
                      <select id="select_analyst" name="analyst_id">
                        @if (!$analyst_id)
                        <option value="0" selected>TODOS</option>  
                        @else
                        <option value="0">TODOS</option>  
                        @endif
                        @foreach ($analysts as $analyst)
                        @if ($analyst->id == $analyst_id)
                          <option value="{{ $analyst->id }}" selected>{{ $analyst->firstname }} {{ $analyst->lastname }} ({{ $analyst->dni }})</option>}
                        @else
                          <option value="{{ $analyst->id }}">{{ $analyst->firstname }} {{ $analyst->lastname }} ({{ $analyst->dni }})</option>}
                        @endif
                        @endforeach
                      </select>


                    </div>

                    @endif
                    
                  </div>
                </div>
                
              </form>

              @if ($vista == App\Http\Controllers\WebController::READ)
              <div style="padding-top: 5px">
                <div class="col-12">
                  <div class="d-block">
                    <div class="d-inline">
    
                    <a title="{{ __('Imprimir Listado') }}" href="#" style="height: 31px" onclick="
                              event.preventDefault();
                              document.getElementById('frm_imprimir').submit();" class="btn btn-primary d-flex align-items-center font-size ml-2">
                      <i class="ion-printer mr-2"></i>
                      <div>
                        {{ __('Imprimir') }}
                      </div>
                    </a>
                    <form target="_blank" method="GET" id="frm_imprimir" action="{{ route('entreta.imprimirlst') }}" class="d-none">
                        <input type="hidden" id="chkRegistrosImprimir" name="chkRegistrosImprimir" value="">
                        <input type="hidden" name="start_date_query" value="{{ $start_date }}">
                        <input type="hidden" name="finish_date_query" value="{{ $finish_date }}">
                        <input type="hidden" id="group_id" name="group_id" value="{{ $group_id }}">
                        <input type="hidden" name="trashed" id="trashed" value="{{ $trashed }}">
                        <input type="hidden" id="company_id" name="company_id" value="{{ $company_id }}">
                        @if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN") 
                        <input type="hidden" id="analyst_id" name="analyst_id" value="{{ $analyst_id }}">                          
                        @endif
                    </form>
                  </div>
    
                  </div>
                </div>
              </div> 
              @endif
                
              </div>

              

         
        </div>
          
        </nav>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>