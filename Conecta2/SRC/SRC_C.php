<?php 
	$tabla = basename(getcwd(), '.php');
	include '../checklogin.php'; 
	include '../conf.php'; 
?>

<html>
	<head>
		<title><?php echo $tabla; ?> | Cambios</title>
	 	<meta http-equiv = 'content-type' content = 'text/html; charset=UTF-8'>
	    <script type = 'text/javascript' src = 'http://code.jquery.com/jquery-1.8.3.js'></script><style type = 'text/css'></style>
	    <link rel = stylesheet type = 'text/css' href = '../layout.css'>      
	    <script type = 'text/javascript' src = 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js'></script> 
	    <script type = 'text/javascript' src = 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
    	<script type = 'text/javascript'> 
        	$(window).load(function(){$(document).ready(function (){
		    	$('#formulario').validate
	        	({ 
	                rules: { <?php for($i = 0; $i < $campos; $i++) if($hereda[$i] != '*') echo $campo[$i].": 'required', \n";?> },
	       			messages: { <?php for($i = 0; $i < $campos; $i++) if($hereda[$i] != '*') echo $campo[$i].": 'Please fill in this field', \n"; ?> }
	        	});
           	});});  
    	</script>
	</head>
	<body><div id='wrap'><div id='main'>
		
		<?php include '../head.php'; ?>
		<?php include '../menuPrimarias.php'; ?>
		
		<!--posibleModificacion-->
		<?php
			$deboHacerCambios = true;
			for($i = 0; $i < $campos; $i++)
				if($_POST[$campo[$i]] == '' && $hereda[$i] != '*') $deboHacerCambios = false; 
			
			if($deboHacerCambios)
			{
				include '../conexionBD.php';
				
				for($i = 0; $i< $campos; $i++)
					if($hereda[$i] == '*')
					{
						if($_FILES[$campo[$i]]['name'] != '')
						{
							$currentDirectory = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
						    $uDirectory = $_SERVER['DOCUMENT_ROOT'] . $currentDirectory; 
						    for($now = time(); file_exists($uFilename = $uDirectory.$now.'-'.$_FILES[$campo[$i]]['name']); $now++);
							
						    if  (
						            isset($_POST['submit']) && 
						            $_FILES[$campo[$i]]['error'] == 0 && 
						            @is_uploaded_file($_FILES[$campo[$i]]['tmp_name']) && 
						            @move_uploaded_file($_FILES[$campo[$i]]['tmp_name'], $uFilename)
						        ) ;
					        else 
						        echo "Error uploading file";
						    //eliminar la vieja
							$query = 'SELECT '.$campo[$i].' from '.$tabla.' where id = '.$_GET['id'].';';
							if (!$rs = mysql_query($query))
								echo 'Error while deleting image';
							else
							{
								$fila = mysql_fetch_array($rs);
								unlink($fila[$campo[$i]]);
							}

							$sentencia_sql = $sentencia_sql.$campo[$i]." = '".$now.'-'.$_FILES[$campo[$i]]['name']."', ";
						}
					}
					else
						$sentencia_sql = $sentencia_sql.$campo[$i]." = '".$_POST[$campo[$i]]."', ";
				$sentencia_sql = substr($sentencia_sql, 0, -2); // le quita la ultima coma

				$sentencia_sql = 'UPDATE '.$tabla.' set '.$sentencia_sql.' where id = '.$_GET['id'].';';		
				
				if (!$resultado = mysql_query($sentencia_sql))
				{
					echo 'Error al modificar datos'.mysql_error().$sentencia_sql;
					header('Refresh: 4; URL= '.basename($_SERVER['SCRIPT_NAME']).'?id='.$GET['id'] ); 
				}
				else{ header('Location: V.php'); }
			}		
		?>
		<!---->
		
		<!--Forma -->
		<br><br>
        <div class = 'contenido'>
        	<?php //obtiene la info del sujeto
				include '../conexionBD.php';
				$query = 'SELECT '.$tabla.'.* from '.$tabla.' where id = '.$_GET['id'].';';
				$rs = mysql_query($query);
                if(!$rs){ echo 'Error al cargar datos'.mysql_error().$query; }
                else $filaI= mysql_fetch_array($rs);
         	?>
         	
			<form action= <?php echo basename($_SERVER['SCRIPT_NAME']).'?id='.$_GET['id']; ?> method = 'post' align = 'left' id = 'formulario' enctype="multipart/form-data">
				<input type = 'hidden' name = 'id' value = "<?php echo $_GET['id'] ?>" ;>
				 
				<?php
					include '../conexionBD.php';		
            		for($i = 0; $i < $campos; $i++)
            		{
            			if($hereda[$i] == '*')
            			{
            				echo $mostrar[$i].":<br>";
            				echo "&nbsp;&nbsp;&nbsp;&nbsp;<img HEIGHT=30 WIDTH=40 src='".$filaI[$campo[$i]]."' alt = '".$filaI[$campo[$i]]."'><br>";
            				echo "&nbsp;&nbsp;&nbsp;&nbsp;Change file?  <input type='file' name='".$campo[$i]."'><br>";
            			}
            			else if($hereda[$i] != '')
						{
							//recolectar datos de otra tabla
							if (!$rs = mysql_query('select id, '.$human[$hereda[$i]].' from '.$seccion[$hereda[$i]].';')) 
								echo 'Error al cargar datos';
							else
								for($k = 0; $fila = mysql_fetch_array($rs); $k++)
								{
									$ddbId[$k] = $fila['id'];
									$ddbHuman[$k] = $fila[$human[$hereda[$i]]];
								}

							//drop down box
							echo $mostrar[$i]."A: <select name='".$campo[$i]."'>";
							for($j = 0; $j < count($ddbId); $j++)
								if($ddbId[$j] == $filaI[$campo[$i]]) 	echo '<option value ='.$ddbId[$j].' selected>'.$ddbHuman[$j].'</option>';
								else 									echo '<option value ='.$ddbId[$j].'>'.$ddbHuman[$j].'</option>';
							echo '</select><br>';
						}
						else 
							if($length[$i]>400) 	echo $mostrar[$i].": &nbsp;&nbsp; <textarea rows='4' cols='50' name='".$campo[$i]."'>".$filaI[$campo[$i]]."</textarea><br>";
							else 					echo $mostrar[$i].": &nbsp;&nbsp; <input type='".$type[$i]."' name='".$campo[$i]."' value='".$filaI[$campo[$i]]."'><br>";
            		}
            	?> 

				<input type='submit' name = 'submit'/>
			</form>
			<br><br><br><br>
		</div>
		<!---->
		
		</div></div><div id='footer'>
		 <?php include '../foot.php'; ?></div>
	</body>
</html>