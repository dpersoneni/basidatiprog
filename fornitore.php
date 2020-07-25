<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title></head>
<body>
    <h1>Fornitore</h1>
    <?php
        	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
            if (!$conn){
                die('Connessione al database fallita.');
            } else {
                //echo "Connessione riuscita."."<br/>";
                $query="SELECT * FROM Fornitore";
                $result =  pg_query($conn, $query);
                if (!$result) {
                    echo "Si è verificato un errore.<br/>";
                    echo pg_last_error($conn);
                    exit();
                } else {
                    echo '<br><table class="form">
                    <tr>
                        <th>Partita Iva</th>
                        <th>Ragione sociale</th>
                        <th>Modalità di pagamento</th>
                        <th>email</th>
                        <th>Telefono</th>
                        <th>Città</th>
                        <th>Via</th>
                        <th>Numero Civico</th>
                        <th>CAP</th>
                    </tr>';
                    while ($row = pg_fetch_array($result)) {
                        echo '<tr>
                            <td>'. $row['piva'].'</td>
                            <td>'. $row['ragionesociale'].'</td>
                            <td>'. $row['modpagamento'].'</td>
                            <td>'. $row['email'].'</td>
                            <td>'. $row['telefono'].'</td>
                            <td>'. $row['citta'].'</td>
                            <td>'. $row['via'].'</td>
                            <td>'. $row['nciv'].'</td>
                            <td>'. $row['cap'].', '. $row['nciv'].', ('. $row['cap'].')'.'</td>
                        </tr>';
                    }
                    echo '</table>';
                }
            }

            	//Disponibiltà
		print("<h2>Prodotti disponibili per fornitore</h2>");		
        $query="SELECT piva FROM Fornitore";
        $result =  pg_query($conn, $query);
        if (!$result) {
            echo "Si è verificato un errore.<br/>";
            echo pg_last_error($conn);
            exit();
        } else {
			print("<table class=\"form\">");
			print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");	
			print("<th>Partita Iva</th> <td><select name=\"piva\" id=\"piva\"><option value=\"\"></option>");
			$query="SELECT piva FROM Fornitore";
			$result = pg_query($conn, $query);
			while ($row = pg_fetch_array($result)) {
				print("<option value=\"$row[piva]\">$row[piva]</option>");
			}
			print("</td></tr>");
			print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Filter\"></td></tr>");
			print("</form>");
			print("</table>");
			if( isset($_POST['idata']) and $_POST['idata']=='Filter' and isset($_POST['piva']) and $_POST['piva']!='') {

				print("<table class=\"form\">");
				print("<tr><th>Prodotto</th><th>Fornitore</th><th>Giorni di consegna</th><th>Prezzo del fornitore</th><th>Codice prodotto dal fornitore</th></tr>");
				$querydispo = "SELECT prodotto, fornitore, giorniconsegna, prezzofornitore, codprodfornitore FROM Disponibilita WHERE fornitore='".$_POST['piva']."'";
				$resultdispo = pg_query($conn, $querydispo);
				
				while ($rowdispo = pg_fetch_array($resultdispo)) {
                    
                    print("<tr><td>$rowdispo[prodotto]</td><td>$rowdispo[fornitore]</td><td>$rowdispo[giorniconsegna]</td><td>$rowdispo[prezzofornitore]</td><td>$rowdispo[codprodfornitore]</td><td>");
                    
                    /*
                    $queryorari = "SELECT orainizio, orafine, giorno FROM Turno  WHERE Lavoratore='".$rowpersonale['codfiscale']."'";
					$resultorari = pg_query($conn, $queryorari);
					while ($roworari = pg_fetch_array($resultorari)) {
						print("$roworari[giorno]: $roworari[orainizio] -  $roworari[orafine]<br/>");
						
                    }
                    */
					print("</td>");
				}
				print("</tr>");
			}
		}

    ?>
</body>
</html>