<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Acquisto [<a href="index.html">home</a>]</h1>
<?php
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Acquisto";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table class="form">
				<tr>
					<th>Quantità</th>
					<th>Numero Scontrino</th>
					<th>Data</th>
                    <th>Acquirente</th>
                    <th>Cassa</th>
					<th>Prodotto</th>
				</tr>';
				while ($row = pg_fetch_array($result)) {
					echo '<tr>
						<td>'. $row['qta'].'</td>
						<td>'. $row['numeroscontrino'].'</td>
						<td>'. $row['data'].'</td>
                        <td>'. $row['cliente'].'</td>
                        <td>'. $row['cassa'].'</td>
						<td>'. $row['prodotto'].'</td>
					</tr>';
				}
				echo '</table>';
			}
        }
        
    
     
       
?>
</body>
</html>

