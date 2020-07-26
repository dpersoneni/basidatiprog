<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Reparto [<a href="index.html">home</a>]</h1>
<?php
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Reparto";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table class="form">
				<tr>
					<th>Id</th>
					<th>Nome</th>
					<th>Supermercato</th>
					<th>Responsabile</th>
				</tr>';
				while ($row = pg_fetch_array($result)) {
					echo '<tr>
						<td>'. $row['id'].'</td>
						<td>'. $row['nome'].'</td>
						<td>'. $row['supermercato'].'</td>
						<td>'. $row['responsabile'].'</td>
					</tr>';
				}
				echo '</table>';
			}
        }
        
        //INSERIMENTO Reparto
		print("<h2>Inserimento</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" required pattern=\".{5,}\" title=\"no valid value\"\"></td></tr>");
        print("<tr><th>Supermercato</th><td> <select name=\"supermercato\" id=\"supermercato\"><option value=\"\"></option>");
        $query="SELECT nome FROM Supermercato";
		$result =  pg_query($conn, $query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[nome]\">$row[nome]</option>");
        }
		print("</td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Insert\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Insert') {   
        	
			$nome=isset($_POST['nome'])?$_POST['nome']:'';
			$supermercato=isset($_POST['supermercato'])?$_POST['supermercato']:'';
            
			$query="INSERT INTO reparto (nome, supermercato) VALUES ('$nome','$supermercato')";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: reparto.php');
			}else{
					echo "Si è verificato un errore.<br/>";
					echo pg_last_error($conn);
			}
		}
    
	    //CANCELLAZIONE Reparto
        print("<h2>Cancellazione</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Id</th><td><input type=\"text\" name=\"id\" pattern=\".{1,}\" title=\"id reparto da eliminare\"></td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Delete\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Delete') {   
        			
            $id=isset($_POST['id'])?$_POST['id']:'';
            
			$query="DELETE FROM Reparto WHERE id='$id'";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: reparto.php');
			}else{
					echo "Si è verificato un errore.<br/>";
					echo pg_last_error($conn);
			}
		}

        //MODIFICA Reparto
        print("<h2>Modifica</h2>");
        $query="SELECT * FROM Reparto";
        $result =  pg_query($conn, $query);
        if (!$result) {
            echo "Si è verificato un errore.<br/>";
            echo pg_last_error($conn);
            exit();
        } else {
			print("<table class=\"form\">");
			print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");	
			print("<th>Reparto</th> <td><select name=\"reparto\" id=\"reparto\"><option value=\"\"></option>");
			$query="SELECT id,nome,supermercato FROM Reparto";
			$result = pg_query($conn, $query);
			while ($row = pg_fetch_array($result)) {
				print("<option value=\"$row[id]\">$row[id] ($row[nome], $row[supermercato])</option>");
			}
			print("</td></tr>");
			print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Modify\"></td></tr>");
			print("</form>");
            print("</table>");
            
            if( isset($_POST['idata']) and $_POST['idata']=='Modify' and isset($_POST['reparto']) and $_POST['reparto']!='') {

                $querymod="SELECT * FROM Reparto WHERE id='".$_POST['reparto']."'";
                $resultmod = pg_query($conn, $querymod);
                $mod=pg_fetch_array($resultmod);
			
                print("<table class=\"form\">");
                print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
                print("<tr><th colspan=4>Id reparto: $mod[id]. Supermercato: $mod[supermercato]</th></tr>");
                print("<input type=\"hidden\" name=\"id\" value=\"$mod[id]\">");
                print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" value=\"$mod[nome]\" required pattern=\".{5,}\" title=\"no valid value\"\"></td>");
                
                print("<th>Responsabile</th><td> <select name=\"responsabile\" id=\"responsabile\">");
                print("<option value=\"$mod[responsabile]\">$mod[responsabile]</option>");
                $queryresp="SELECT codfiscale FROM Lavoratore JOIN Reparto ON reparto=id WHERE supermercato='".$mod[supermercato]."'"; 
                $resultresp=  pg_query($conn, $queryresp);
                while ($rowresp = pg_fetch_array($resultresp)) {
                   if($rowresp[codfiscale] != $mod[responsabile]){
                        print("<option value=\"$rowresp[codfiscale]\">$rowresp[codfiscale]</option>");
                   }   
                }
                
                print("</td></tr>");
                print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Update\"></td></tr>");
                print("</form>");
                print("</table>"); 
                
            }
            if( isset($_POST['idata']) and $_POST['idata']=='Update') { 
                                        
                $nome=isset($_POST['nome'])?$_POST['nome']:'';
                $responsabile=isset($_POST['responsabile'])?$_POST['responsabile']:'';
                
                
                $query="UPDATE reparto SET nome='".$nome."', responsabile='".$responsabile."' WHERE id='".$_POST['id']."'";
                $result = pg_query($conn,$query);
                if ($result){
                        header('Location: reparto.php');
                }else{
                        echo "Si è verificato un errore.<br/>".$query."<br>";
                        echo pg_last_error($conn);
                }
            }
        }
        
        //MANSIONI Reparto
        print("<h2>Personale nei reparti</h2>");
        $query="SELECT L.mansione, R.nome, R.id, R.supermercato  FROM lavoratore L JOIN reparto R ON L.reparto=R.id ORDER BY R.supermercato";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<table class="form">
				<tr>
					<th>Mansione</th>
					<th>Reparto</td>
					<th>Lavoratore</td>
				</tr>';
				while ($row = pg_fetch_array($result)) {

					$query1="SELECT codfiscale, nome, cognome FROM Lavoratore WHERE mansione='".$row['mansione']."' AND reparto='".$row['id']."'";
                    $result1 =  pg_query($conn, $query1);
                    
                    echo '<tr>
						<td>'. $row['mansione'].'</td>
						<td>'. $row['supermercato'].', '.$row['nome'].'</td>';
					
					print("<td>");
					while ($row1 = pg_fetch_array($result1)) {
						print("<br> $row1[codfiscale]. $row1[nome] $row1[cognome]");
					}
					print("</td></tr>");
				}
				echo '</table>';
			}		

		//TURNI Reparto
		print("<h2>Orari</h2>");		
        $query="SELECT * FROM Reparto";
        $result =  pg_query($conn, $query);
        if (!$result) {
            echo "Si è verificato un errore.<br/>";
            echo pg_last_error($conn);
            exit();
        } else {
			print("<table class=\"form\">");
			print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");	
			print("<th>Reparto</th> <td><select name=\"reparto\" id=\"reparto\"><option value=\"\"></option>");
			$query="SELECT id,nome,supermercato FROM Reparto";
			$result = pg_query($conn, $query);
			while ($row = pg_fetch_array($result)) {
				print("<option value=\"$row[id]\">$row[id] ($row[nome], $row[supermercato])</option>");
			}
			print("</td></tr>");
			print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Filter\"></td></tr>");
			print("</form>");
			print("</table>");
			if( isset($_POST['idata']) and $_POST['idata']=='Filter' and isset($_POST['reparto']) and $_POST['reparto']!='') {

				print("<table class=\"form\">");
				print("<tr><th>Reparto</th><th>Lavoratore</th><th>Turni</th></tr>");
				$querypersonale = "SELECT DISTINCT codfiscale FROM Lavoratore JOIN Turno ON lavoratore=codfiscale WHERE reparto='".$_POST['reparto']."'";
				$resultpersonale = pg_query($conn, $querypersonale);
				
				while ($rowpersonale = pg_fetch_array($resultpersonale)) {
					print("<tr><td>$_POST[reparto]</td><td>$rowpersonale[codfiscale]</td><td>");
					$queryorari = "SELECT orainizio, orafine, giorno FROM Turno  WHERE Lavoratore='".$rowpersonale['codfiscale']."'";
					$resultorari = pg_query($conn, $queryorari);
					while ($roworari = pg_fetch_array($resultorari)) {
						print("$roworari[giorno]: $roworari[orainizio] -  $roworari[orafine]<br/>");
						
					}
					print("</td>");
				}
				print("</tr>");
			}
		}

?>
</body>
</html>

