<?php 
 	$tabla = basename(getcwd(), '.php');
	include '../checklogin.php'; 
	include '../conf.php';
?>

<html>
	<head>
		<title><?php echo $tabla; ?> | Baja</title>
		<link rel = stylesheet type = 'text/css' href = '../layout.css'>
	   	<meta charset = 'utf-8'> 
	</head>
	<body>
		<div id = 'wrap'><div id = 'main'>
			<?php include '../head.php'; ?>
			<?php include '../menuPrimarias.php'; ?>
					
		 	<div class = 'tabla' style = 'padding-right:150pt; padding-left:150pt;'>
				<table class = 'empleados'>
					<tr><th class = 'header'>Message</th></tr>
					<tr><td style = 'padding-top:20pt; padding-bottom:20pt' align = 'center'>		
						<!--hacerBaja-->
							<?php
								if($_GET['id'] != '')
								{
									include '../conexionBD.php';
									$return = true;
									//eliminar entrada
									if (!mysql_query('delete from '.$tabla.' where id = '.$_GET['id'].';'))
									{
										echo 'Error while deleting entry.<br>';
										$return = false;
										if(strpos(mysql_error(), 'constraint'))
										{
											 echo 'The entry is being used in another table.';
											 //if(strpos(mysql_error(), "`entregas`.`visitas`")) { echo "<b>Visitas</b>."; } //dependencia FALTA ELIMINARLAS
										}	
									}
									else 
									{
										//después eliminar imagen
										for($i = 0; $i< $campos; $i++)
											if($hereda[$i] == '*')
											{
												$query = 'SELECT '.$campo[$i].' from '.$tabla.' where id = '.$_GET['id'].';';
												if (!$rs = mysql_query($query))
												{
													$return = false;
													echo 'Error while deleting image';
												}
												else
												{
													$fila = mysql_fetch_array($rs);
													unlink($fila[$campo[$i]]);
													header('Location: V.php');
												}
											}
										if($return) header('Location: V.php');
									}
								}
								else header('Location: V.php');
							?>
						<!---->

					</td></tr>

					<tr><td align = 'center' style = 'padding-top:20pt; padding-bottom:20pt'>
						<button type = 'button' onClick = "location.href='V.php'">Continue</button>
					</td></tr>

				</td></tr>
				</table>
			</div>		
		</div></div>
		<div id = 'footer'><?php include '../foot.php'; ?></div>
	</body>
</html>