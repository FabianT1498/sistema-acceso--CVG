<div class="card-body">
    <h3 class="h3 mb-md-5 text-center title-subline">Datos del visitante</h3>
    <div class="form-row mb-md-4">

        <div class="form-group col-md-4">
            <label for="visitorFirstname">Nombre(s):&nbsp;<sup class="text-danger">*</sup></label>
            <input 
                type="text" 
                class="form-control" 
                id="visitorFirstname" 
                name="visitor_firstname" 
                placeholder="Nombre del visitante"
                value="{{ old('visitor_firstname') ? old('visitor_firstname') : (isset($visitor) ? $visitor->firstname : '')}}"
                autocomplete="off"
                {{isset($is_show_view) && $is_show_view ? "readonly" : ""}}
                required
            >                   
        </div>

        <div class="form-group col-md-4">
            <label for="visitorLastname">Apellido(s):&nbsp;<sup class="text-danger">*</sup></label>
            <input 
            type="text" 
            class="form-control" 
            id="visitorLastname" 
            name="visitor_lastname" 
            placeholder="Apellido del visitante"
            value="{{ old('visitor_lastname') ? old('visitor_lastname') : (isset($visitor) ? $visitor->lastname : '')}}"
            autocomplete="off"
            {{isset($is_show_view) && $is_show_view ? "readonly" : ""}}
            required
            >                   
        </div>

        <div class="form-group col-md-4">
            <label for="visitorPhoneNumber">Telefono:&nbsp;</label>
            <input 
            type="text" 
            class="form-control" 
            id="visitorPhoneNumber" 
            name="visitor_phone_number" 
            value="{{ old('visitor_phone_number') ? old('visitor_phone_number') : (isset($visitor) ? $visitor->phone_number : '')}}"
            placeholder="Telefono del visitante"
            {{isset($is_show_view) && $is_show_view ? "readonly" : ""}}
            autocomplete="off"
            >
        </div>
    </div>
    <div class="form-row">

        @if (isset($is_form_visit) && !$is_form_visit)
            <div class="form-group col-md-4">
                <label for="visitorDNI">Cedula del visitante:&nbsp;<sup class="text-danger">*</sup></label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="visitorDNI" 
                    name="visitor_dni" 
                    placeholder="Cedula del visitante"
                    value="{{ old('visitor_dni') ? old('visitor_dni') : (isset($visitor) ? $visitor->dni : '')}}"
                    autocomplete="off"
                    {{isset($is_show_view) && $is_show_view ? "readonly" : ""}}
                    required
                >                   
            </div>
        @endif

        @if (isset($is_show_view) && !$is_show_view)
            <div class="form-group col-md-4">
                <label for="file">Foto del visitante &nbsp;</label>
                <input type="file" name="image" class="file" accept="image/*">
                <div class="input-group">
                    <input type="text" class="form-control" disabled placeholder="Subir Foto" id="file">
                    <div class="input-group-append">
                        <button type="button" class="browse btn btn-primary">Buscar...</button>
                    </div>
                </div>
            </div>
        @endif
        <div class="form-group col-md-2 ml-md-4">                      
            @if (isset($photo))
                <img src="{{ Storage::url($photo->path) }}" id="preview" class="img-thumbnail">
            @else
                <img src="" id="preview" class="img-thumbnail">
            @endif                  
        </div>
    </div>
</div>