<div class="content-header py-0">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12 d-flex">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 col-12">
          <span class="text-dark d-inline h4 pt-2 mr-0">
            <a href="{{ route('stocks') }}" title="">
              <small><i class="ion ion-soup-can-outline">&nbsp;</i></small>{{ __('STOCKS') }}
            </a>
          </span>
          <form id="stock_form" class="form-inline ml-3" action="{{ route('stocks') }}">
            <div class="input-group input-group-sm">
              <input type="hidden" name="buscar" value="true">
              <input id="search" name="search" class="form-control form-control-navbar" type="search" placeholder="{{ __('Buscar') }}" aria-label="Search" value="{{ $search }}">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div class="inline" style="padding-left: 5px">
              Localidad:
              <select id="select_location" name="location_id">
                @if (!$location_id)
                <option value="0" selected>TODOS</option>  
                @else
                <option value="0">TODOS</option>  
                @endif
                @foreach ($locations as $location)
                @if ($location->id == $location_id)
                  <option value="{{ $location->id }}" selected>{{ $location->name }}</option>}
                @else
                  <option value="{{ $location->id }}">{{ $location->name }}</option>}
                @endif
                @endforeach
              </select>
            </div>
          </form>
        </nav>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>