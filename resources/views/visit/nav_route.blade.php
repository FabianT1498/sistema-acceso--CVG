@if ( $is_my_visit === 1)
    
    @section('migasdepan')
        <a href="{{ route('mis_visitas') }}">{{ __('Mis visitas') }}</a>
        &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('Mis visitas') }}
        <span class="text-success">({{ __('Crear') }})</span>
    @endsection

    @section('head_visit')
        @include('visit.head_my_visits')
    @endsection
@else
    
    @section('migasdepan')
        <a href="{{ route('visitas.index') }}">{{ __('Visita') }}</a>
        &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('Visita') }}
        <span class="text-success">({{ __('Crear') }})</span>
    @endsection

    @section('head_visit')
        @include('visit.head')
    @endsection
    
@endif