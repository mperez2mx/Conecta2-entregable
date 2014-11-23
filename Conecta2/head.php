<div class = header>
	<div style = 'text-align:left; float:left;'> <a href='../<?php echo $seccion[0]; ?>/V.php'><img src='../imagenes/logo.png' alt = 'Immansoft'></a> </div>
	<?php echo 'Welcome '.$_SESSION['user'].'!&nbsp;&nbsp;&nbsp;&nbsp; Privileges: '.$_SESSION['type'].'&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?>
	<a href = '../logout.php'>Cerrar sesion [x]</a>
</div>