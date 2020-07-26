<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Supermercato [<a href="index.html">home</a>]</h1>
<?php
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Supermercato";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table class="form">
				<tr>
					<th>Nome</th>
					<th>Città</td>
					<th>Via</th>
					<th>Numero civico</th>
					<th>CAP</th>
				</tr>';
				while ($row = pg_fetch_array($result)) {
					echo '<tr>
						<td>'. $row['nome'].'</td>
						<td>'. $row['citta'].'</td>
						<td>'. $row['via'].'</td>
						<td>'. $row['nciv'].'</td>          
						<td>'. $row['cap'].'</td>	
					</tr>';
				}
				echo '</table>';
			}
		}

		//INSERIMENTO Supermercato
        print("<h2>Inserimento</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" required pattern=\"{10,30}\" title=\"nome supermercato\"></td></tr>");
        print("<tr><th>Via</th><td><input type=\"text\" name=\"via\"></td></tr>");
        print("<tr><th>Numero Civico</th><td><input type=\"text\" name=\"nciv\"></td></tr>");
		print("<tr><th>cap</th><td><input type=\"text\" name=\"cap\"></td></tr>");
		print("<input type=\"hidden\" name=\"citta\" value=\"Milano\">");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Send\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Send') {   

			$nome=isset($_POST['nome'])?$_POST['nome']:'';
			$citta=isset($_POST['citta'])?$_POST['citta']:'';
			$via=isset($_POST['via'])?$_POST['via']:'';
			$nciv=(isset($_POST['nciv'])and is_numeric($_POST['nciv']))?$_POST['nciv']:0;
			$cap=(isset($_POST['cap'])and is_numeric($_POST['cap']))?$_POST['cap']:0;

			$query="INSERT INTO supermercato (nome, citta, via, nciv, cap) VALUES ('$nome','$citta','$via','$nciv','$cap')";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: supermercato.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
		}
	
		//CANCELLAZIONE Supermercato
		print("<h2>Cancellazione</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" pattern=\"{10,30}\" title=\"nome supermercato da eliminare\"></td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Delete\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Delete') {   

			$nome=isset($_POST['nome'])?$_POST['nome']:'';

			$query="DELETE FROM supermercato WHERE nome='$nome'";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: supermercato.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
		}

?>
</body>
</html>