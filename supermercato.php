<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermercato</title>
</head>
<body>
<p class="form">
<?php
    if(isset($_POST['vdata']) and  !empty($_POST['vdata']))
    {
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			echo 'Connessione al database fallita.';
			exit();
			//die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Supermercato";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table>
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
					</tr>';//<td>'. $row['nint'].'</td>
				};
				echo '</table>';
		
			};

		}
    }
    else 
    {
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
	    print("<input type=\"submit\" name=\"vdata\" value=\"View Data\">");
	    print("</form>");    
    }
   
?>
	</p>
</body>
</html>