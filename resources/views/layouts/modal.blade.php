<!-- Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Barra de busqueda</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		<p class="text-justify">Puede buscar de acuerdo a los siguientes criterios</p>
		<ul class="fa-ul">
            @foreach(getSearchOptions() as $option)
                <li><i class="fa-li fa fa-check-square"></i>{{$option}}</li>
            @endforeach
		</ul>
	  </div>
	</div>
  </div>
</div>