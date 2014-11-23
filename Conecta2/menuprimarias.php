<div class="banner">
	<br><br>
    <table align = 'right' cellspacing = '0' cellpadding = '0'><tr>
      <?php
        for ($i = 0; $i < $sections; $i++) //Botones
        {
          echo '<td><div class = ';
          if($i == 0) echo 'btnf';
          else        echo 'btn';
            echo "><a href='../".$seccion[$i]."/V.php'>".$Seccion[$i]."</a>";
          echo "</div></td>"; 
        }
      /*
      if (substr(basename($_SERVER['SCRIPT_NAME']),0,4) == substr($seccion[$i], 0, 4))
        echo "<a href='../".$seccion[$i]."/".$seccion[$i].".php'><img src='../imagenes/".$seccion[$i]."Active.png'alt='Actividades'/></a>"; 
      else
        echo "<a href='../".$seccion[$i]."/".$seccion[$i].".php'><img src='../imagenes/".$seccion[$i].".png' onmouseover=\"this.src='../imagenes/".$seccion[$i]."Hover.png'\" onmouseout=\"this.src='../imagenes/".$seccion[$i].".png'\" alt='Contactos'/></a>";  
      */
      ?> 	
      </tr></table>
</div>        

<div style = 'text-align:left; float:left; color:black';>
  <div align='left' style='clear:both;'>
  <a href='../<?php echo $seccion[0]; ?>/V.php'>Home</a>
  <?php
     	for ($i = 0; $i < $sections; $i++) //para aterrizar
   			if($tabla == $seccion[$i] ) 
   			{
  			   $actual = $i;
				   echo "><a href='V.php'>".$Seccion[$i]."</a>";
   			}
   		include '../conexionBD.php';
   		switch (basename($_SERVER['SCRIPT_NAME'])) //para el cielo
   		{
   			case 'A.php': echo "> <a href='".basename($_SERVER['SCRIPT_NAME'])."'>Create</a>"; break;
 				case 'C.php': 
          $query = "select ".$human[$actual]." from ".$seccion[$actual]." where id = ".$_GET['id'].";";
 					if(!$rs = mysql_query($query)) 
            echo "Error. ".$query."actual:".$actual;
  				$fila = mysql_fetch_array($rs); 
          $link = basename($_SERVER['SCRIPT_NAME'])."?id=".$_GET['id']; $nombre = "Update '".$fila[$human[$actual]]."'";
  				echo " ><a href= $link > $nombre</a>";
 	        break;
			}   		
      echo "</div>";
	?> 
</div>