<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Cliente [<a href="index.html">home</a>]</h1>
<?php
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Cliente";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table class="form">
				<tr>
					<th>Codice Carta</th>
					<th>Nome</th>
					<th>Cognome</th>
                    <th>Punti</th>
                    <th>Telefono</th>
					<th>Data di nascita</th>
					<th>Email</th>
				</tr>';
				while ($row = pg_fetch_array($result)) {
					echo '<tr>
						<td>'. $row['codcarta'].'</td>
						<td>'. $row['nome'].'</td>
						<td>'. $row['cognome'].'</td>
                        <td>'. $row['punti'].'</td>
                        <td>'. $row['telefono'].'</td>
						<td>'. $row['datanascita'].'</td>
						<td>'. $row['email'].'</td>
					</tr>';
				}
				echo '</table>';
			}
        }
        
        //INSERIMENTO cliente
        print("<h2>Inserimento</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Codice Carta</th><td><input type=\"text\" name=\"codcarta\" required pattern=\".{10,10}\" title=\"Codice carta di 10 cifre\"></td></tr>");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\"></td></tr>");
        print("<tr><th>Cognome</th><td><input type=\"text\" name=\"cognome\"></td></tr>");
		print("<tr><th>Telefono</th><td><input type=\"text\" name=\"telefono\"></td></tr>");
        print("<tr><th>Data di nascita</th><td><input type=\"date\" name=\"datanascita\"></td></tr>");
		print("<tr><th>Email</th><td><input type=\"email\" name=\"email\"></td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Send\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Send') {   

			$codcarta=isset($_POST['codcarta'])?$_POST['codcarta']:'';
			$nome=isset($_POST['nome'])?$_POST['nome']:'';
            $cognome=isset($_POST['cognome'])?$_POST['cognome']:'';
            $datanascita=isset($_POST['datanascita'])?$_POST['datanascita']:'';
            $telefono=isset($_POST['telefono'])?$_POST['telefono']:'';
            $email=isset($_POST['email'])?$_POST['email']:'';

			$query="INSERT INTO cliente (codcarta, nome, cognome, datanascita, telefono, email) VALUES ('$codcarta','$nome','$cognome','$datanascita','$telefono','$email')";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: cliente.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
		}
    
	    //CANCELLAZIONE Cliente
		print("<h2>Cancellazione</h2>");
        print("<table class=\"form\">");
        print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Cliente</th><td> <select name=\"codcarta\" id=\"codcarta\"><option value=\"\"></option>");
        $query="SELECT codcarta, nome, cognome FROM cliente";
		$result =  pg_query($conn, $query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[codcarta]\">$row[codcarta] - $row[nome] $row[cognome]</option>");
        }
		print("</td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Delete\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Delete') {   

			$codcarta=isset($_POST['codcarta'])?$_POST['codcarta']:'';

			$query="DELETE FROM cliente WHERE codcarta='$codcarta'";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: cliente.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
        }
    
?>
</body>
</html>