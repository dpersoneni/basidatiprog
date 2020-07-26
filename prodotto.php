<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>EsseCorta</title>
</head>
<body>
    <h1>Prodotto [<a href="index.html">home</a>]</h1>
<?php
    	$conn = pg_connect("host=localhost port=5432 dbname=essecorta user=postgres password=postgres");
		if (!$conn){
			die('Connessione al database fallita.');
		} else {
			//echo "Connessione riuscita."."<br/>";
			$query="SELECT * FROM Prodotto";
			$result =  pg_query($conn, $query);
			if (!$result) {
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
				exit();
			} else {
				echo '<br><table class="form">
				<tr>
					<th>Codice Barre</th>
					<th>Nome</td>
					<th>Punti</th>
					<th>Categoria</th>
					<th>Prezzo Pubblico</th>
				</tr>';
				while ($row = pg_fetch_array($result)) {
					echo '<tr>
						<td>'. $row['codbarre'].'</td>
						<td>'. $row['nome'].'</td>
						<td>'. $row['punti'].'</td>
						<td>'. $row['categoria'].'</td>          
						<td>'. $row['prezzopubblico'].'</td>	
					</tr>';
				}
				echo '</table>';
			}
		}

		//INSERIMENTO Supermercato
        print("<h2>Inserimento</h2>");
        print("<table class=\"form\">");
		print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Codice Barre</th><td><input type=\"text\" name=\"codbarre\" required pattern=\"[1-9].{10,}\" title=\"no valid value\"></td></tr>");
        print("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\"></td></tr>");
        print("<tr><th>Punti</th><td><input type=\"text\" name=\"punti\"></td></tr>");
        print("<tr><th>categoria</th><td><input type=\"text\" name=\"categoria\"></td></tr>");
		print("<tr><th>Prezzo Pubblico</th><td><input type=\"text\" name=\"prezzopubblico\"></td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Send\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if(isset($_POST['idata']) and $_POST['idata']=='Send') {   

			$codbarre=(isset($_POST['codbarre'])and is_numeric($_POST['codbarre']))?$_POST['codbarre']:0;
			$nome=isset($_POST['nome'])?$_POST['nome']:'';
			$punti=(isset($_POST['punti'])and is_numeric($_POST['punti']))?$_POST['punti']:0;
			$categoria=isset($_POST['categoria'])?$_POST['categoria']:0;
			$prezzopubblico=isset($_POST['prezzopubblico'])?$_POST['prezzopubblico']:0;

			$query="INSERT INTO prodotto (codbarre, nome, punti, categoria, prezzopubblico) VALUES ('$codbarre','$nome','$punti','$categoria','$prezzopubblico')";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: prodotto.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
		}
	
		//CANCELLAZIONE Supermercato
		print("<h2>Cancellazione</h2>");
        print("<table class=\"form\">");
        print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");
        print("<tr><th>Prodotto da eliminare</th><td> <select name=\"codbarre\" id=\"codbarre\"><option value=\"\"></option>");
        $query="SELECT codbarre, nome FROM prodotto";
		$result =  pg_query($conn, $query);
        while ($row = pg_fetch_array($result)) {
            print("<option value=\"$row[codbarre]\">$row[codbarre], $row[nome]</option>");
        }
		print("</td></tr>");
        print("<tr><td><input type=\"submit\" name=\"idata\" value=\"Delete\"></td></tr>");
        print("</form>");
		print("</table>"); 
		
		if( isset($_POST['idata']) and $_POST['idata']=='Delete') {   

			$codbarre=isset($_POST['codbarre'])?$_POST['codbarre']:'';

			$query="DELETE FROM prodotto WHERE codbarre='$codbarre'";
			$result = pg_query($conn,$query);
			if ($result){
				header('Location: prodotto.php');
			}else{
				echo "Si è verificato un errore.<br/>";
				echo pg_last_error($conn);
			}
        }
        
        //PRODOTTI ASSEMBLATI
        print("<h2>Prodotti Assemblati</h2>");
        print("<table class=\"form\">");
        print("<tr><th>Prodotto</th><th>Componenti</th></tr>");
        $querycomposto='SELECT DISTINCT codbarre, nome FROM Prodotto JOIN Assemblaggio ON codbarre=composto';
        $resultcomposto= pg_query($conn,$querycomposto);
        while ($rowcomposto = pg_fetch_array($resultcomposto)) {
            print("<tr><td>$rowcomposto[codbarre], $rowcomposto[nome]</td><td>");

            $querycomponente='SELECT DISTINCT codbarre, nome, qta FROM Prodotto JOIN Assemblaggio ON codbarre=componente WHERE composto=\''.$rowcomposto['codbarre'].'\'';
            $resultcomponente= pg_query($conn,$querycomponente);
            while ($rowcomponente = pg_fetch_array($resultcomponente)) {
                print("$rowcomponente[codbarre], $rowcomponente[nome] - quantità: $rowcomponente[qta]<br/>");
            }
            Print("</td></tr>");
        }
        print("</table>");

        //PRODOTTI STOCK
        print("<h2>Prodotti in Stock</h2>");
        print("<table class=\"form\">");
        print("<tr><th>Supermercato</th><th>Prodotto</th><th>Data Scadenza</th><th>Quantità</th></tr>");
        $query='SELECT s.nome as supermercato, k.datascadenza, k.qta, p.codbarre, p.nome FROM Supermercato s JOIN Stock k ON s.nome=k.supermercato JOIN Prodotto p ON p.codbarre=k.prodotto';
        $result= pg_query($conn,$query);
        while ($row = pg_fetch_array($result)) {
            print("<tr><td>$row[supermercato]</td><td>$row[codbarre], $row[nome]</td><td>$row[datascadenza]</td><td>$row[qta]</td></tr>");
        }

        print("<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">");



        print("</table>"); 
?>
</body>
</html>