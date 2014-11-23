<?php
	$iSecc = 0;
	$proy = 'Conecta2';
	$dbusr = 'mike';
	$dbpass = 'Notgreenm1.';
	$msgfoot = 'ITESM CVA';

	$seccion[$iSecc] = 'AccessPoints';	$Seccion[$iSecc] = ' Access Points';	$human[$iSecc++] = 'latitud';
	$seccion[$iSecc] = 'users';	$Seccion[$iSecc] = 'Users';	$human[$iSecc++] = 'user';

	if($_SESSION['type'] == 'admin') $sections = $iSecc;
	else $sections = $iSecc - 1;

	$campos = 0;
	$iSecc = 0;

	switch ($tabla)
	{
		case $seccion[$iSecc++]:
			$campo[$campos] = 'latitud';	$mostrar[$campos] = 'Latitud';	$hereda[$campos] = '';	$type[$campos] = 'double';	$length[$campos++] = '';
			$campo[$campos] = 'longitud';	$mostrar[$campos] = 'Longitud';	$hereda[$campos] = '';	$type[$campos] = 'double';	$length[$campos++] = '';
			$campo[$campos] = 'nombre';	$mostrar[$campos] = 'Nombre';	$hereda[$campos] = '';	$type[$campos] = 'text';	$length[$campos++] = '';
			$campo[$campos] = 'ranking';	$mostrar[$campos] = 'Ranking';	$hereda[$campos] = '';	$type[$campos] = 'int';	$length[$campos++] = '';
			$campo[$campos] = 'categoria';	$mostrar[$campos] = 'Categoria';	$hereda[$campos] = '';	$type[$campos] = 'text';	$length[$campos++] = '';
			$campo[$campos] = 'votos';	$mostrar[$campos] = 'Votos';	$hereda[$campos] = '';	$type[$campos] = 'int';	$length[$campos++] = '';
			$campo[$campos] = 'reporte';	$mostrar[$campos] = 'Reporte';	$hereda[$campos] = '';	$type[$campos] = 'int';	$length[$campos++] = '';
		break;

		case $seccion[$iSecc++]:
			$campo[$campos] = 'user';	$mostrar[$campos] = 'User';	$hereda[$campos] = '';	$type[$campos] = 'text';	$length[$campos++] = '';
			$campo[$campos] = 'pass';	$mostrar[$campos] = 'Pass';	$hereda[$campos] = '';	$type[$campos] = 'text';	$length[$campos++] = '';
			$campo[$campos] = 'type';	$mostrar[$campos] = 'Type';	$hereda[$campos] = '';	$type[$campos] = 'text';	$length[$campos++] = '';
		break;
	}
?>
