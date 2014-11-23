<?php
	include 'conf.php'; 
?>
<html>
	<head><link rel = stylesheet type = "text/css" href="layout.css"/></head>
	<body>
		<div id="wrapLogIn">
			<div id="main">
				<center>
					<div class = "LogIn"> <img src='imagenes/logo.png' alt = 'logo'></div>       
			        <div class="formLogin">
			        	<table>
			        		<td class="NL"><p>
			        			<img src='imagenes/login1 av.png' alt = 'login'></p>
			          			<p>&nbsp;</p>
			          			<p>&nbsp;</p> 
			          		</td>
			          		<td class="NL">&nbsp; &nbsp; </td>
			          		<td class="NL">
			          			<form class="LogIn" action="login.php" method="post">
			                		<table> 
			                			<tr>
			                				<td class="NL">
			                					<table>
			                						<tr>
			                  							<td class="NL">
			                  								<div align="right"><b>Username:</b></div>
			                  							</td>
			                	  						<td>
			            	      							<input type="text" name="user" value = "<?php if (isset($_COOKIE["user"])) echo $_COOKIE["user"]; else echo "";?>";> 	
			                  							</td> 
			                  						</tr>
			                						<tr> 
			                							<td class="NL">
			                								<div align="right"><b>Password:</b></div>
			                							</td> 
			                							<td class="NL"> <input type="password" name="pass" value = ""> </td> 
			                						</tr>
			                 					</table>
			                				</td>
			                				<td class="NL"><input type="submit" value=""/></td>
			                			</tr>
			                			<tr>
			                				<td align="right" class="NL"><input type="checkbox" name="recordar" value="1" checked="checked">Recordar mis datos &nbsp;</td> 
			                  			</tr>
			                		</table> 
								</form>
			         		</td>
			        	</table>
			        </div> 
					
					<?php		
						if($_POST['user']!= ""){ // recibe usuario
							if ($_POST['recordar'] == 1) //hay cookie
							{
								setcookie("user", $_POST['user'], time()+2592000);
								$_COOKIE["user"] = $_POST['user']; 
							}
							else
							{
								setcookie("user", "",-3600);
								$_COOKIE["user"] = "";
							}
							if ( $_POST['pass']!= ""){ // recibe pass
								include 'conexionBD.php'; 
								$array = mysql_fetch_array(mysql_query("select user from users where user = '".$_POST['user']."';"), MYSQL_BOTH);
								$nombre= $array['user'];	
								$array = mysql_fetch_array(mysql_query("select pass from users where user = '".$nombre."';"), MYSQL_BOTH); 
								$pass= $array['pass'];	
								if($pass != $_POST['pass']) { echo "Password incorrecto";}
								else //pass correcto
								{
									session_start();
									$_SESSION['user']=$nombre;
									
			                		$fila2 = mysql_fetch_array(mysql_query("select type from users where user = '".$_POST['user']."';"));
			                		$_SESSION['type'] = $fila2['type'];

									header('Location: '.str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).$seccion[0].'/V.php');
								}
							} else { echo "You must fill in the password";}
						} else { echo "You must fill in the user";}
					?>
				</center>
			</div>
		</div>
		<div id="footer"><?php include 'foot.php'; ?></div>
	</body>
</html>
