<?php


function setActive($nameRoute, $nameClass = 'active'){
	$clase = '';
	if (!is_array($nameRoute)) {
		$clase = (getRouteIsCrud($nameRoute) ? $nameClass : '');
	}else{
		foreach ($nameRoute as $route) {
			if (!$clase) {
				$clase = (getRouteIsCrud($route) ? $nameClass : '');
			}
		}
	}
	return $clase;
}

function getRouteIsCrud($nameRoute){
	$isRoute = (request()->routeIs($nameRoute) ? true :
	(request()->routeIs($nameRoute . '.index') ? true :
		(request()->routeIs($nameRoute . '.store') ? true :
			(request()->routeIs($nameRoute . '.create') ? true :
				(request()->routeIs($nameRoute . '.show') ? true :
					(request()->routeIs($nameRoute . '.update') ? true :
						(request()->routeIs($nameRoute . '.destroy') ? true :
							(request()->routeIs($nameRoute . '.edit') ? true : false))))))));
	return $isRoute;

}


function getSearchOptions(){

	$name = request()->route()->uri;
	
	if ($name[0] === '/'){
		$name = str_replace("/", "", $name);
	}

	$arrName = explode('/', $name, 2);

	$options = null;

	if ($arrName[0] === 'usuarios'){
		$options = array(
			'Nombre del usuario (ej. juan123)',
			'Cedula del usuario (ej. v-1014823 o e-1014823)'
		);
	} else if ($arrName[0] === 'autos'){
		$options = array(
			'Número de matricula del auto (ej. ABC1246)',
		);
	} else if ($arrName[0] === 'reportes' || $arrName[0] === 'mis-visitas'){
		$options = array(
			'Nombre y apellido del visitante (ej. Juan Perez)',
			'Cedula del visitante (ej. v-1014823 o e-1014823)',
			'Intervalo de tiempo de visitas',
		);
	} else if($arrName[0] === 'visitantes'){
		$options = array(
			'Nombre y apellido del visitante (ej. Juan Perez)',
			'Cedula del visitante (ej. v-1014823 o e-1014823)',
		);
	}

	return $options;
	 
}