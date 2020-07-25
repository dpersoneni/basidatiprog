<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Reparto</h1>
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
					<th>Nome</td>
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
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" pattern=\".{5,}\" title=\"no valid value\"\"></td></tr>");
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
            echo '<table class="form">
            <tr>
                <th>Id</th>
                <th>Nome</td>
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
   
?>
</body>
</html>