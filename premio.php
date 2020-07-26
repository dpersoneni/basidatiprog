<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Premio [<a href="index.html">home</a>]</h1>
<?php
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Premio JOIN Catalogo c ON catalogo=c.id";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table class="form">
				<tr>
					<th>Nome</th>
					<th>Punti Richiesti</td>
					<th>Catalogo</th>
				</tr>';
				while ($row = pg_fetch_array($result)) {
					echo '<tr>
						<td>'. $row['nome'].'</td>
						<td>'. $row['puntirichiesti'].'</td>
						<td>'. $row['datainizio'].' - '. $row['datafine'].'</td>
					</tr>';
				}
				echo '</table>';
			}
		}

		//INSERIMENTO Supermercato
        print("<h2>Inserimento</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" required></td></tr>");
        print("<tr><th>Numero punti</th><td><input type=\"text\" name=\"puntirichiesti\" required></td></tr>");

        print("<th>Catalogo</th><td> <select name=\"catalogo\" id=\"catalogo\">");
        $queryresp="SELECT * FROM Catalogo"; 
        $resultresp=  pg_query($conn, $queryresp);
        while ($rowresp = pg_fetch_array($resultresp)) {
                print("<option value=\"$rowresp[id]\">$rowresp[datainizio] - $rowresp[datafine]</option>");  
        }
        print("</td></tr>");  
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Send\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Send') {   

			$nome=isset($_POST['nome'])?$_POST['nome']:'';
			$puntirichiesti=isset($_POST['puntirichiesti'])?$_POST['puntirichiesti']:'';
			$catalogo=isset($_POST['catalogo'])?$_POST['catalogo']:'';

			$query="INSERT INTO Premio (nome, puntirichiesti, catalogo) VALUES ('$nome','$puntirichiesti','$catalogo')";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: premio.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
		}
	
		//CANCELLAZIONE Supermercato
		print("<h2>Cancellazione</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        
        print("<th>Premio</th><td> <select name=\"id\" id=\"id\">");
        $queryresp="SELECT * FROM Premio";
        $resultresp=  pg_query($conn, $queryresp);
        while ($rowresp = pg_fetch_array($resultresp)) {
                print("<option value=\"$rowresp[id]\">$rowresp[nome]</option>");  
        }
        print("</td></tr>");

        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Delete\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Delete') {   

			$id=isset($_POST['id'])?$_POST['id']:'';

			$query="DELETE FROM Premio WHERE id='$id'";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: premio.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
		}
?>
</body>
</html>