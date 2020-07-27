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
        print("<th>Nome</th><td><input type=\"text\" name=\"nome\" required></td>");
        print("<th>Cognome</th><td><input type=\"text\" name=\"cognome\" required></td>");
        print("<th>Telefono</th><td><input type=\"text\" name=\"telefono\" required pattern=\"[0-9].{6,10}\" title=\"only digits\"></td></tr>");
        print("<tr><th>Data Nascita</th><td><input type=\"date\" name=\"datanascita\" required></td>");
        print("<th>E-mail</th><td><input type=\"text\" name=\"email\" required pattern=\"[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$\" title=\"no valid value\"></td>");
        print("<th>Mansione</th><td><input type=\"text\" name=\"mansione\" required></td>");
        print("<th>Livello</th><td><input type=\"text\" name=\"livello\" required pattern=\"[1-5]\" title=\"no valid value\"></td></tr>");
        print("<tr><th>Città</th><td><input type=\"text\" name=\"citta\" required></td>");
        print("<th>Via</th><td><input type=\"text\" name=\"via\" required></td>");
        print("<th>Civico</th><td><input type=\"text\" name=\"nciv\" required></td>");
        print("<th>CAP</th><td><input type=\"text\" name=\"cap\" required></td></tr>");
        print("<tr><th>Data Assunzione</th><td><input type=\"date\" name=\"dataassunzione\" required></td>");
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

            if ($reparto == ''){
                $query="INSERT INTO lavoratore (codfiscale, nome, cognome, telefono, datanascita, email, mansione, livello, citta, via, nciv, cap, dataassunzione, reparto)
                VALUES ('$codfiscale','$nome','$cognome','$telefono','$datanascita','$email','$mansione','$livello','$citta','$via','$nciv','$cap','$dataassunzione',null) ";
            } else{
                $query="INSERT INTO lavoratore (codfiscale, nome, cognome, telefono, datanascita, email, mansione, livello, citta, via, nciv, cap, dataassunzione, reparto) 
                VALUES ('$codfiscale','$nome','$cognome','$telefono','$datanascita','$email','$mansione','$livello','$citta','$via','$nciv','$cap','$dataassunzione','$reparto')";
            }
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
        
        //MODIFCA Reparto Dipendente
        print("<h2>Modifica Reparto Dipendente</h2>");
        print("<table class=\"form\">");
        print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Lavoratore</th><td colspan=\"2\"> <select name=\"codfiscale\" id=\"codfiscale\"><option value=\"\"></option>");
        $query="SELECT codfiscale, nome, cognome, reparto FROM Lavoratore";
        $result = pg_query($conn,$query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[codfiscale]$row[reparto]\">$row[codfiscale]. $row[cognome], $row[nome]</option>");
        }
        print("</td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Modify\"></td></tr>");
        print("</form></table>");

        if( isset($_POST['idata']) and $_POST['idata']=='Modify') {

            $valoreconcatenato=isset($_POST['codfiscale'])?$_POST['codfiscale']:'';

            $codfiscale=substr($valoreconcatenato,0,16);
            $reparto=substr($valoreconcatenato,16);

            print("<table class=\"form\">");
            print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
            print("<tr><th>$codfiscale, Reparto $reparto</th><td>Seleziona nuovo reparto</td><td><select name=\"reparto\" id=\"reparto\">");
            $query="SELECT * FROM Reparto WHERE supermercato=(SELECT supermercato FROM Reparto WHERE id='".$reparto."')";
            $result = pg_query($conn,$query);
            while ($row = pg_fetch_array($result)) {
                print("<option value=\"$row[id]\">$row[id]. $row[nome] di $row[supermercato]</option>");
            }
            print("</td><td>Nuova mansione</td><td><input type=\"text\" name=\"mansione\"></td></tr>");
            print("<input type=\"hidden\" name=\"codfiscale\" value=\"$codfiscale\">");
            print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Update\"></td></tr>");
            print("</form></table>");
        }

        if( isset($_POST['idata']) and $_POST['idata']=='Update') {

            $reparto=isset($_POST['reparto'])?$_POST['reparto']:'';
            $codfiscale=isset($_POST['codfiscale'])?$_POST['codfiscale']:'';
            $mansione=isset($_POST['mansione'])?$_POST['mansione']:'';

            $query="UPDATE lavoratore SET reparto='".$reparto."', mansione='".$mansione."' WHERE codfiscale='".$codfiscale."'";
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