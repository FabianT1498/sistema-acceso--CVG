<?php


function setActive($nameRoute, $nameClass = 'active'){
	$clase = '';
	if (!is_array($nameRoute)) {
		$clase = (getRouteIsCrud($nameRoute) ? $nameClass : '');
	}else{
		foreach ($nameRoute as $route) {
			if ($clase === '') {
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

function splitURI($uri){
	$new_uri = $uri;

	if ($new_uri[0] === '/'){
		$new_uri = substr($new_uri, 1, strlen($new_uri) - 1);
	}

	return explode('/', $new_uri, 2);

}


function getSearchOptions(){

	$name = request()->route()->uri;
	
	$arrName = splitURI($name);

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
	} else if ($arrName[0] === 'visitas' || $arrName[0] === 'mis-visitas'){
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