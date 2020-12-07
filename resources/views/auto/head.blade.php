<div class="content-header py-0">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12 d-flex">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 col-12">
          <span class="text-dark d-inline h4 pt-2 mr-0">
            <a href="{{ route('autos.index') }}" title="">
              <small><i class="nav-icon icon fa fa-car">&nbsp;</i></small>{{ __('Autos') }}
            </a>
          </span>
          @if (Auth::user()->role_id === 4)
            <form class="form-inline ml-0" action="{{ route('autos.create') }}">
              <input type="hidden" name="search" value="{{ $search }}">
              <input type="hidden" name="trashed" id="trashed" value="{{ $trashed }}">
              <div class="input-group ml-3" title="{{ __('Nuevo Registro') }}">
                <div class="">
                  <button class="btn btn-primary btn-sm" type="submit">
                    <i class="icon ion-android-add px-1"></i>
                  </button>
                </div>
              </div>
            </form>
          @endif
          <form id="searchForm" class="form-inline ml-3" action="{{ route('autos.index') }}">
            <div class="input-group input-group-sm">
              <input type="hidden" name="buscar" value="true">
              <input id="search" name="search" class="form-control form-control-navbar" autocomplete="off" type="search" placeholder="{{ __('Buscar') }}" aria-label="Search" value="{{ $search }}">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
         
              <button 
                type="button" 
                class="btn btn-primary btn-circle btn-md ml-md-3"
                data-toggle="modal" 
                data-target="#helpModal"
              > 
						    <i class="icon fa fa-question"></i>
					    </button>
            </div>
          </form>
        </nav>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@include('layouts.modal')