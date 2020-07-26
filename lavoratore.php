<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Lavoratore [<a href="index.html">home</a>]</h1>
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
					<th>Nome</th>
                    <th>Cognome</th>
                    <th>Telefono</th>
					<th>Data Nascita</th>
                    <th>E-mail</th>
                    <th>Mansione</th>
					<th>Livello</th>
                    <th>Città</th>
                    <th>Via</th>
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
                        <td>'. $row['via'].', '. $row['nciv'].', ('. $row['cap'].')'.'</td>
                        <td>'. $row['dataassunzione'].'</td>
                        <td>'. $row['reparto'].'</td>
					</tr>';
				}
				echo '</table>';
			}
        }

        //INSERIMENTO Lavoratore
        print("<h2>Inserimento</h2>");
        print("<table class=\"form\">");
        print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Cod Fiscale</th><td><input type=\"text\" name=\"codfiscale\" pattern=\".{13,}\" title=\"CF non idoneo\"></td>");
        print("<th>Nome</th><td><input type=\"text\" name=\"nome\"</td>");
        print("<th>Cognome</th><td><input type=\"text\" name=\"cognome\" </td>");
        print("<th>Telefono</th><td><input type=\"text\" name=\"telefono\" pattern=\"[0-9].{6,10}\" title=\"only digits\"></td></tr>");
        print("<tr><th>Data Nascita</th><td><input type=\"date\" name=\"datanascita\" ></td>");
        print("<th>E-mail</th><td><input type=\"text\" name=\"email\" pattern=\"[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$\" title=\"no valid value\"></td>");
        print("<th>Mansione</th><td><input type=\"text\" name=\"mansione\" ></td>");
        print("<th>Livello</th><td><input type=\"text\" name=\"livello\" pattern=\"[1-5]\" title=\"no valid value\"></td></tr>");
        print("<tr><th>Città</th><td><input type=\"text\" name=\"citta\" ></td>");
        print("<th>Via</th><td><input type=\"text\" name=\"via\" ></td>");
        print("<th>Civico</th><td><input type=\"text\" name=\"nciv\" ></td>");
        print("<th>CAP</th><td><input type=\"text\" name=\"cap\" ></td></tr>");
        print("<tr><th>Data Assunzione</th><td><input type=\"date\" name=\"dataassunzione\" ></td>");
        print("<th>Reparto</th><td colspan=\"2\"> <select name=\"reparto\" id=\"reparto\"><option value=\"\"></option>");
        $query="SELECT id,nome,supermercato FROM Reparto";
		$result =  pg_query($conn, $query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[id]\">$row[id] ($row[nome], $row[supermercato])</option>");
        }
		print("</td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Insert\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Insert') {   
        	
            $codfiscale=isset($_POST['codfiscale'])?$_POST['codfiscale']:'';
            $nome=isset($_POST['nome'])?$_POST['nome']:'';
            $cognome=isset($_POST['cognome'])?$_POST['cognome']:'';
            $telefono=isset($_POST['telefono'])?$_POST['telefono']:'';
            $datanascita=isset($_POST['datanascita'])?$_POST['datanascita']:'';
            $email=isset($_POST['email'])?$_POST['email']:'';
            $mansione=isset($_POST['mansione'])?$_POST['mansione']:'';
            $livello=isset($_POST['livello'])?$_POST['livello']:'';
            $citta=isset($_POST['citta'])?$_POST['citta']:'';
            $via=isset($_POST['via'])?$_POST['via']:'';
            $nciv=isset($_POST['nciv'])?$_POST['nciv']:'';
            $cap=isset($_POST['cap'])?$_POST['cap']:'';
            $dataassunzione=isset($_POST['dataassunzione'])?$_POST['dataassunzione']:'';
            $reparto=isset($_POST['reparto'])?$_POST['reparto']:'';

			$query="INSERT INTO lavoratore (codfiscale, nome, cognome, telefono, datanascita, email, mansione, livello, citta, via, nciv, cap, dataassunzione, reparto) 
                VALUES ('$codfiscale','$nome','$cognome','$telefono','$datanascita','$email','$mansione','$livello','$citta','$via','$nciv','$cap','$dataassunzione','$reparto') ";
			$result = pg_query($conn,$query);
			if ($result){
                echo "<script>
                if(window.location.href.substr(-2) !== \"?r\") {
                     window.location = window.location.href + \"?r\";
                }
                </script>";
			}else{
					echo "Si è verificato un errore.<br/>";
					echo pg_last_error($conn);
			}
        }
        
        //CANCELLAZIONE Lavoratore
        print("<h2>Cancellazione</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codfiscale\"></td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Delete\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Delete') {   
        			
            $codfiscale=isset($_POST['codfiscale'])?$_POST['codfiscale']:'';
            
			$query="DELETE FROM Lavoratore WHERE codfiscale='$codfiscale'";
			$result = pg_query($conn,$query);
			if ($result){
				echo "<script>
                    if(window.location.href.substr(-2) !== \"\") {
                         window.location = window.location.href + \"\";
                    }
                    </script>";
			}else{
					echo "Si è verificato un errore.<br/>";
					echo pg_last_error($conn);
			}
		}
?>
</body>
</html>