<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Lavoratore</h1>
    <?php
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Lavoratore";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table class="form">
				<tr>
					<th>Cod Fiscale</th>
					<th>Nome</td>
					<th>Cognome</th>
                    <th>Telefono</th>
                    <th>Data Nascita</th>
                    <th>E-mail</th>
                    <th>Mansione</th>
                    <th>Livello</th>
                    <th>Città</th>
                    <th>Via</th> 
                    <th>CAP</th>
                    <th>Data Assunzione</th>
                    <th>Reparto</th>
				</tr>';
				while ($row = pg_fetch_array($result)) {
					echo '<tr>
						<td>'. $row['codfiscale'].'</td>
						<td>'. $row['nome'].'</td>
						<td>'. $row['cognome'].'</td>
                        <td>'. $row['telefono'].'</td>
                        <td>'. $row['datanascita'].'</td>
                        <td>'. $row['email'].'</td>
                        <td>'. $row['mansione'].'</td>
                        <td>'. $row['livello'].'</td>
                        <td>'. $row['citta'].'</td>
                        <td>'. $row['via'].', '.$row['nciv'].'</td>
                        <td>'. $row['cap'].'</td>
                        <td>'. $row['dataassunzione'].'</td>
                        <td>'. $row['reparto'].'</td>
					</tr>';
				};
				echo '</table>';
			};
        }
    ?>
</body>
</html>