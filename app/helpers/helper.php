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



