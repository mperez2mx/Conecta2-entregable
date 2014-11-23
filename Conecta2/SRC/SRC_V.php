<?php 
error_reporting( error_reporting() & ~E_NOTICE );
	$tabla = basename(getcwd(), '.php');
	include '../checklogin.php';
	include '../conf.php'; 
?>

<html>
	<head>
		<title><?php echo $tabla; ?> | Vista</title>		
		<link rel = 'stylesheet' type = 'text/css' href = '../layout.css'>
        <script src = 'http://code.jquery.com/ui/1.10.2/jquery-ui.js'></script>
        <script>
        	function preguntar(foo, ID_Foo)
        	{ 
            	var eliminar = confirm('You are about to delete the entry '+foo+', sure?'); 
            	if(eliminar) location.href='B.php?id='+ID_Foo;
        	}
    	</script>
        <meta charset = 'utf-8'/> 
	</head>
	
	<body>
		
    <div id = 'wrap'><div id = 'main'>
		
		<?php include '../head.php'; ?>
   		<?php include '../menuPrimarias.php'; ?>
		
		<!--Buscar-->
        	<div class = 'search'> <br>
				<form  action=<?php echo basename($_SERVER['SCRIPT_NAME']); ?> method="get">
					Search: <input id='nombre' name='nombre'>
					<input type = 'image' src = '../imagenes/search1.png' alt = 'Search'/>
				</form>
			*Leave empty to see all entries
			</div>
		<!---->
		
		<div class = 'tabla'>
			<!--PageNumberAndLimits-->
			<?php
				if(isset($_GET['epp']))
					$epp = $_GET['epp'];
				else 
					$epp = 10;
				if(isset($_GET['pagina']))
				{
					$pagina_actual = $_GET['pagina'];
					$limite_superior = $_GET['pagina']*$epp;
					$limite_inferior = $limite_superior - $epp;
				} 
				else
				{
					$limite_inferior = 0;
					$limite_superior = $epp;
					$pagina_actual = 1;
				}
			?>
			<!---->
			<!--QueryForTablaPrincipal-->
				<?php
					$iguales = '';
					$joins = '';
					$tablasJoin = '';
					$order = '';
					$busquedaRef = '';
					for($i = 0; $i<$campos; $i++)
						if($hereda[$i] != '' && $hereda[$i] != '*')
						{
							$joins = $joins.', t'.$i.'.'.$human[$hereda[$i]].' AS ref'.$i;
							$tablasJoin = $tablasJoin.', '.$seccion[$hereda[$i]].' AS t'.$i;
							$iguales = $iguales.'t'.$i.'.id = '.$tabla.'.'.$campo[$i].' AND ';
							$busquedaRef = $busquedaRef.'t'.$i.'.'.$human[$hereda[$i]]." like '%".$_GET['nombre']."%' or ";
						}
					$iguales = substr($iguales, 0, -4); // le quita el ultimo and
					$busquedaRef = ' or '.substr($busquedaRef, 0, -3); // le quita el ultimo or

					$baseRef = 'SELECT '.$tabla.'.*'.$joins.' FROM '.$tabla.$tablasJoin.' WHERE '.$iguales;
					$base = 'SELECT '.$tabla.'.* FROM '.$tabla;
					$limites = ' limit '.$limite_inferior.' , '.$epp.';';
					
					if(isset($_GET['order']) && isset($_GET['asc'])) $order = ' ORDER BY '.$_GET['order'].' '.$_GET['asc'];

					$forConBusqueda = '';
					for($i = 0; $i<$campos; $i++)
						$forConBusqueda = $forConBusqueda.$tabla.'.'.$campo[$i]." like '%".$_GET['nombre']."%' or ";//campo[i]
					if($joins != '')
						$forConBusqueda = ' ('.substr($forConBusqueda, 0, -3).$busquedaRef.')'; // le quita el ultimo or
					else
						$forConBusqueda = ' ('.substr($forConBusqueda, 0, -3).')'; // le quita el ultimo or

					if ($joins != '')	if(isset($_GET['nombre']))	$query = $baseRef.' AND '.$forConBusqueda.$order.$limites;
										else  						$query = $baseRef.$order.$limites;
					else 				if(isset($_GET['nombre']))	$query = $base.' WHERE'.$forConBusqueda.$order.$limites;
										else  						$query = $base.$order.$limites;
					include '../conexionBD.php';
		            if(!$rs = mysql_query($query)) echo 'Error al cargar datos'.mysql_error().'|'.$query.'|'.$joins.'|'.isset($_GET['nombre']);
		            if(isset($_GET['nombre']) && mysql_num_rows($rs) == 0) echo 'No se encontraron entradas<br>';
		       	?>
			<!---->
			<!--TablaPrincipal-->
				<table class= 'empleados' align = 'left'>
					<?php
						//Column tittles
							echo "<tr class = 'header'>";
								for($i = 0; $i<$campos; $i++)
									echo '<th>'.$mostrar[$i].	"<a href = '".basename($_SERVER['SCRIPT_NAME']).
																			'?order='.$campo[$i].
																			'&asc=asc'.
																			(isset($_GET['epp'])?'&epp='.$_GET['epp']:'').
																			(isset($_GET['nombre'])?'&nombre='.$_GET['nombre']:'').
																			"'>▲</a>".
																"<a href = '".basename($_SERVER['SCRIPT_NAME']).
																			'?order='.$campo[$i].
																			'&asc=desc'.
																			(isset($_GET['epp'])?'&epp='.$_GET['epp']:'').
																			(isset($_GET['nombre'])?'&nombre='.$_GET['nombre']:'').
																			"'>▼</a></th>";
								if($_SESSION['type'] == 'admin')
									echo '<th>Options</th>'; 
							echo '</tr>';
						//RowContents
						while($fila= mysql_fetch_array($rs))
						{
							echo '<tr>';
						 		for($i = 0; $i<$campos; $i++)
									if($hereda[$i] == '*')			echo '<td><a href = '.$fila[$campo[$i]]."><img HEIGHT=30 WIDTH=40 src='".$fila[$campo[$i]]."' alt = '".$fila[$campo[$i]]."'></td>";
									else if($hereda[$i] == '')		echo '<td>'.$fila[$campo[$i]].'</td>';
					 				else 							echo '<td>'.$fila['ref'.$i].'</td>';
								if($_SESSION['type'] == 'admin') 
								{echo 		
									'<td><a href = C.php?id='.$fila['id']."><img src='../imagenes/modificar.jpg' alt = 'Modificar'></a> &nbsp;".
									"<a onclick='jsfunction' href = \"javascript:preguntar('".($hereda[0] == ''?$fila[$campo[0]]:$fila['ref0'])."', '".$fila['id']."')\"><img src='../imagenes/eliminar.jpg' alt = 'Eliminar'></a></td>";
								}
							echo'</tr>';
						}
					?>
				</table>
			<!---->
			<!--NumeroDeEntradasYDePaginas() -->
				<?php
					//entradas
					$numero = 0;
					if ($joins != '')	if(isset($_GET['nombre']))	$query = $baseRef.' AND '.$forConBusqueda.$order;
										else  						$query = $baseRef.$order;
					else 				if(isset($_GET['nombre']))	$query = $base.' WHERE'.$forConBusqueda.$order;
										else  						$query = $base.$order;
					if(!$rs_numero = mysql_query($query)) echo 'Error al acceder a la base de datos'.$query;
					else $array = mysql_fetch_array($rs_numero, MYSQL_BOTH); 
					$numero = $array['count(*)'];
					//páginas
					$numero_paginas = 1;
					if($numero%$epp == 0) $numero_paginas = $numero/$epp;
					else $numero_paginas += (int)($numero/$epp);
				?>
			<!---->
			<!--LinksParaOtrasPáginas-->
				<div align= 'right'>
					<?php	
						//epp
						echo '<form action= '.basename($_SERVER['SCRIPT_NAME'])." method = 'get'>";
							//drop down box
							echo "view: <select name='epp'>";
							for($j = 10; $j < 21; $j++)
								echo '<option value ='.$j.'>'.$j.'</option>';
							echo '</select>';
							echo "<input type='submit' value = 'Go'/>    ";
							//Anterior
							if($pagina_actual != 1)
							{
								echo '<a href = '.basename($_SERVER['SCRIPT_NAME']).'?pagina='.($pagina_actual-1).(isset($_GET['epp'])?'&epp='.$_GET['epp']:'').(isset($_GET['nombre'])?'&nombre='.$_GET['nombre']:'')."><img src='../imagenes/flecha.jpg' alt = 'Anterior'></a> &nbsp;";
							}
							else echo "<img src='../imagenes/flecha.jpg' alt = 'Anterior'>";
							//Numeritos
							for($i = 1; $i <= $numero_paginas; $i++)
							{
								if($i != $pagina_actual)
								{
									echo '<a href = '.basename($_SERVER['SCRIPT_NAME']).'?pagina='.$i.(isset($_GET['epp'])?'&epp='.$_GET['epp']:'').(isset($_GET['nombre'])?'&nombre='.$_GET['nombre']:'').'>'.$i.'</a> &nbsp;';
								}else echo $i.'&nbsp';
							}
							//Siguiente
							if($pagina_actual < $numero_paginas)
							{ 
								echo '<a href = '.basename($_SERVER['SCRIPT_NAME']).'?pagina='.($pagina_actual+1).(isset($_GET['epp'])?"&epp=".$_GET['epp']:"").(isset($_GET['nombre'])?'&nombre='.$_GET['nombre']:'')."><img src='../imagenes/flechaSiguiente.jpg' alt = 'Siguiente'></a> &nbsp;";
							}
							else echo "<img src='../imagenes/flechaSiguiente.jpg' alt = 'Siguiente'>";
						echo '</form>';
					?>
				</div>
			<!---->
		</div>
		
		<!--Agregar-->
			<div class='contenido' align='left' style=' padding-left:100pt'>
				<?php 
					if($_SESSION['type'] == 'admin')
					{echo
						"<button type='button' onClick=\"location.href= 'A.php'\">Add</button>";
					}
				?>
			</div>
		<!---->
		
		<?php include '../foot.php'; ?>
		 
	</body>
</html>