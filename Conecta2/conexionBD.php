<?php
	mysql_connect('127.0.0.1', $dbusr, $dbpass) or die(mysql_error());
	mysql_select_db($proy) or die(mysql_error());
?>