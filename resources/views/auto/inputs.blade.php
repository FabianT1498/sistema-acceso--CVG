<h3 class="h3 mb-md-5 text-center title-subline">Datos del auto</h3>
<div class="form-row mb-md-4">
    <div class="form-group col-md-3">
        <label for="autoEnrrolment">Matricula del auto:&nbsp;<sup class="text-danger">*</sup></label>
        <input 
            type="text" 
            class="form-control" 
            id="autoEnrrolment" 
            name="auto_enrrolment" 
            style="text-transform:uppercase"
            placeholder="Ingrese Matricula"
            autocomplete="off"
            value="{{old('auto_enrrolment')}}"
            required
        >                   
        <input 
            type="hidden" 
            id="autoID" 
            name="auto_id" 
            value="{{old('auto_id') ? old('auto_id') : -1}}"
            readonly
        >
    </div>
    <div class="form-group col-md-3">
        <label for="autoBrand">Marca del auto:&nbsp;<sup class="text-danger">*</sup></label>
        <input 
            type="text" 
            class="form-control" 
            id="autoBrand" 
            name="auto_brand" 
            placeholder="INGRESE LA MARCA"
            value="{{old('auto_brand')}}"
            required
        >
        <input 
            type="hidden" 
            id="autoBrandID" 
            name="auto_brand_id" 
            value="{{old('auto_brand_id') ? old('auto_brand_id') : -1}}"
            readonly
        >                 
    </div>
    <div class="form-group col-md-3">
        <label for="autoModel">Modelo del auto:&nbsp;<sup class="text-danger">*</sup></label>
        <input 
            type="text" 
            class="form-control" 
            id="autoModel" 
            name="auto_model" 
            placeholder="INGRESE EL MODELO"
            value="{{old('auto_model')}}"
            required
        >                   
        <input 
            type="hidden" 
            id="autoModelID" 
            name="auto_model_id" 
            value="{{old('auto_model_id') ? old('auto_model_id') : -1}}"
            readonly
        >                 
    </div>
    <div id="autoLoader" class="col-md-1 pt-md-3">
        <div class="mt-md-4 loading d-none"></div> 
    </div>
    <div id="autoResult" class="col-md-2 pt-md-3"></div>  
</div>
    
<div class="form-row">
    <div class="form-group col-md-3">
        <label for="autoColor">Color:&nbsp;<sup class="text-danger">*</sup></label>
        <input 
            type="text" 
            class="form-control" 
            id="autoColor" 
            name="auto_color" 
            value="{{old('auto_color')}}"
            placeholder="COLOR DEL AUTO"
            required
        >                   
    </div> 
</div>