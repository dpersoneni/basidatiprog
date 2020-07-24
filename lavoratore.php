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
			}
        }

        print("<h2>Inserimento</h2>");
        print("<table class=\"form\">");
        print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");

        echo '<br><table class="form">
				<tr>
					
                    <th>Telefono</th>
                    <th>Data Nascita</th>
                    <th>E-mail</th>
                    <th>Mansione</th>
                    <th>Livello</th>
                    <th>Città</th>
                    <th>Via</th>
                    <th>Civico</th> 
                    <th>CAP</th>
                    <th>Data Assunzione</th>
                    <th>Reparto</th>
				</tr>';
        print("<tr>");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" required pattern=\"{5,20}\"></td></tr>");

        print("<tr><th>Cod Fiscale</th><td><input type=\"text\" name=\"codfiscale\" required pattern=\".{13,13}\"></td></tr>");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" ></td></tr>");
        print("<tr><th>Cognome</th><td><input type=\"text\" name=\"cognome\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"telefono\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"datanascita\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"email\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"mansione\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"livello\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"citta\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"via\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"nciv\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"cap\" ></td></tr>");
        print("<tr><td><input type=\"date\" name=\"dataassunzione\" ></td></tr>");
        print("<tr><td><input type=\"text\" name=\"reparto\" ></td></tr>");

		print("<tr><th>Responsabile</th><td> <select name=\"codFiscale\" id=\"codFiscale\">");
        $query="SELECT codFiscale FROM Lavoratore JOIN Reparto ON codFiscale=responsabile";
		$result =  pg_query($conn, $query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[codfiscale]\">$row[codfiscale]</option>");
        }
        print("</td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"InsertReparto\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='InsertReparto') {   
        	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
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
	}
	
    ?>
</body>
</html>