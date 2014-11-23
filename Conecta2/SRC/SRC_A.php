<?php 
	$tabla = basename(getcwd(), '.php');
	include '../checklogin.php'; 
	include '../conf.php';
?>

<html>
	<head>
		<title><?php echo $tabla; ?> | Alta</title>
	 	<meta http-equiv = 'content-type' content = 'text/html; charset=UTF-8'>
	    <script type = 'text/javascript' src = 'http://code.jquery.com/jquery-1.8.3.js'></script><style type = 'text/css'></style>
	    <link rel = stylesheet type = 'text/css' href = '../layout.css'>      
	    <script type = 'text/javascript' src = 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js'></script> 
	    <script type = 'text/javascript' src = 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
    	<script type = 'text/javascript'> 
        	$(window).load(function(){$(document).ready(function (){
		    	$('#formulario').validate
	        	({ 
	                rules: { <?php for($i = 0; $i < $campos; $i++) echo $campo[$i].": 'required', \n";?> },
	       			messages: { <?php for($i = 0; $i < $campos; $i++) echo $campo[$i].": 'Please fill in this field', \n"; ?> }
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
					$csv = $csv.$campo[$i].', ';
				$csv = substr($csv, 0, -2); // le quita la ultima coma

				for($i = 0; $i< $campos; $i++)
					if($hereda[$i] == '*')
					{
						$currentDirectory = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
					    $uDirectory = $_SERVER['DOCUMENT_ROOT'] . $currentDirectory;
					    $name =  str_replace(' ', '', $_FILES[$campo[$i]]['name']);
					    for($now = time(); file_exists($uFilename = $uDirectory.$now.'-'.$name); $now++) ;
						
					    if  (
					            isset($_POST['submit']) && 
					            $_FILES[$campo[$i]]['error'] == 0 && 
					            @is_uploaded_file($_FILES[$campo[$i]]['tmp_name']) && 
					            @move_uploaded_file($_FILES[$campo[$i]]['tmp_name'], $uFilename)
					        ) ;
				        else 
					        echo "Error uploading file";

						$values = $values."'".$now.'-'.$name."', ";
					}
					else
						$values = $values."'".$_POST[$campo[$i]]."', ";
				$values = substr($values, 0, -2); // le quita la ultima coma
				
				if (!$resultado = mysql_query('INSERT INTO '.$tabla.' ('.$csv.') values ('.$values.');'))
				{
					echo 'Error al registrar la entrada. <br>'.mysql_error();
					if(strpos(mysql_error(), 'UNIQUE') || strpos(mysql_error(), 'uplicate') ) echo 'Entrada duplicada.';
					header('Refresh: 4; URL= '.basename($_SERVER['SCRIPT_NAME']) );  
				}
				else { header('Location: V.php'); }
			}
		?>
		<!---->
		
		<!--Forma -->
		<br><br>
		<div class = 'contenido'>
			<form action= <?php echo basename($_SERVER['SCRIPT_NAME']); ?> method = 'post' align = 'left' id = 'formulario' enctype="multipart/form-data">
            	
            	<?php
            		include '../conexionBD.php';		
            		for($i = 0; $i < $campos; $i++)
            		{
            			if($hereda[$i] == '*') //MAX_FILE_SIZE = "1073741824"
            			{ 
					        echo "File to upload:  <input type='file' name='".$campo[$i]."'><br>";
            			}
            			else if($hereda[$i] != '')
						{
							$ddbId = '';
							$ddbHuman = '';
							//recolectar datos
							if (!$rs = mysql_query('select id, '.$human[$hereda[$i]].' from '.$seccion[$hereda[$i]].';')) 
								echo 'Error al cargar datos';
							else
								for($k = 0; $fila = mysql_fetch_array($rs); $k++)
								{
									$ddbId[$k] = $fila['id'];
									$ddbHuman[$k] = $fila[$human[$hereda[$i]]];
								}
							//drop down box
							echo $mostrar[$i].": <select name='".$campo[$i]."'>";
							for($j = 0; $j < count($ddbId); $j++)
								echo '<option value ='.$ddbId[$j].'>'.$ddbHuman[$j].'</option>';
							echo '</select><br>';
						}
						else 
							if($length[$i]>400) 	echo $mostrar[$i].": &nbsp;&nbsp; <textarea rows='4' cols='50' name='".$campo[$i]."'></textarea><br>";
							else 					echo $mostrar[$i].": &nbsp;&nbsp; <input type='".$type[$i]."' name='".$campo[$i]."'><br>";
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