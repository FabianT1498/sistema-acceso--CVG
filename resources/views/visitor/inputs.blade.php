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
                value="{{old('visitor_firstname')}}"
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
            value="{{old('visitor_lastname')}}"
            required
            >                   
        </div>

        <div class="form-group col-md-4">
            <label for="visitorPhoneNumber">Telefono:&nbsp;<sup class="text-danger">*</sup></label>
            <input 
            type="text" 
            class="form-control" 
            id="visitorPhoneNumber" 
            name="visitor_phone_number" 
            value="{{old('visitor_phone_number')}}"
            placeholder="Telefono del visitante"
            required
            >
        </div>
    </div>
    <div class="form-row">

        @if (isset($is_form_report) && !$is_form_report)
            <div class="form-group col-md-4">
                <label for="visitorDNI">Cedula del visitante:&nbsp;<sup class="text-danger">*</sup></label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="visitorDNI" 
                    name="visitor_dni" 
                    placeholder="Cedula del visitante"
                    value="{{old('visitor_dni')}}"
                    required
                >                   
            </div>
        @endif


        <div class="form-group col-md-4">
            <label for="file">Foto del visitante &nbsp;<sup class="text-danger">*</sup></label>
            <input type="file" name="image" class="file" accept="image/*" required>
            <div class="input-group">
                <input type="text" class="form-control" disabled placeholder="Subir Foto" id="file" required>
                <div class="input-group-append">
                    <button type="button" class="browse btn btn-primary">Buscar...</button>
                </div>
            </div>
        </div>
        <div class="form-group col-md-2 ml-md-4">                      
            <img src="" id="preview" class="img-thumbnail">                    
        </div>
    </div>
</div>