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
				print("<tr><th>Prodotto</th><th>Giorni di consegna</th><th>Prezzo del fornitore</th><th>Codice prodotto dal fornitore</th></tr>");
				$querydispo = "SELECT prodotto, giorniconsegna, prezzofornitore, codprodfornitore FROM Disponibilita WHERE fornitore='".$_POST['piva']."'";
				$resultdispo = pg_query($conn, $querydispo);
				
				while ($rowdispo = pg_fetch_array($resultdispo)) {
                    
                    print("<tr><td>$rowdispo[prodotto]</td><td>$rowdispo[giorniconsegna]</td><td>$rowdispo[prezzofornitore]</td><td>$rowdispo[codprodfornitore]</td><td>");
                    
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
                print("</table>");

			}
        }
        
        //INSERIMENTO disponibilità
        print("<h2>Inserimento prodotti disponibili del fornitore</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<th>Fornitore</th> <td><select name=\"fornitore\" id=\"fornitore\"><option value=\"\"></option>");
        $query="SELECT piva FROM Fornitore";
        $result = pg_query($conn, $query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[piva]\">$row[piva]</option>");
        }
        print("</td></tr>");       
        print("<th>Prodotto</th> <td><select name=\"prodotto\" id=\"prodotto\"><option value=\"\"></option>");
        $query="SELECT codbarre FROM Prodotto";
        $result = pg_query($conn, $query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[codbarre]\">$row[codbarre]</option>");
        }
        print("</td></tr>");   
        print("<tr><th>Tempo di consegna</th><td><input type=\"text\" name=\"giorniconsegna\"></td></tr>");
        print("<tr><th>Prezzo del fornitore</th><td><input type=\"text\" name=\"prezzofornitore\"></td></tr>");
        print("<tr><th>Codice prodotto del fornitore</th><td><input type=\"text\" name=\"codprodfornitore\"></td></tr>");
		//print("<input type=\"hidden\" name=\"citta\" value=\"Milano\">");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Send\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Send') {   

			$fornitore=isset($_POST['fornitore'])?$_POST['fornitore']:'';
			$prodotto=isset($_POST['prodotto'])?$_POST['prodotto']:'';
			$giorniconsegna=isset($_POST['giorniconsegna'])?$_POST['giorniconsegna']:'';
            $prezzofornitore=isset($_POST['prezzofornitore'])?$_POST['prezzofornitore']:'';
            $codprodfornitore=isset($_POST['codprodfornitore'])?$_POST['codprodfornitore']:'';

			$query="INSERT INTO disponibilita (fornitore, prodotto, giorniconsegna, prezzofornitore, codprodfornitore) VALUES ('$fornitore','$prodotto','$giorniconsegna','$prezzofornitore','$codprodfornitore')";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: fornitore.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
		}

    ?>
</body>
</html>