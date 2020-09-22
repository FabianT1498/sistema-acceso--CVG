<div class="content-header py-0">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-12 d-flex">
          <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 col-12">
            <span class="text-dark d-inline h4 pt-2 mr-0">
              <a href="{{ route('almacen') }}" title="">
                <small><i class="ion ion-ios-color-filter-outline">&nbsp;</i></small>{{ __('ALMACEN') }}
              </a>
            </span>
            <form class="form-inline ml-3" action="{{ route('inventarios-almacen') }}">
              <div class="input-group input-group-sm">
                <input type="hidden" name="buscar" value="true">
                <input id="search" name="search" class="form-control form-control-navbar" type="search" placeholder="{{ __('Buscar') }}" aria-label="Search" value="{{ $search }}">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </nav>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>