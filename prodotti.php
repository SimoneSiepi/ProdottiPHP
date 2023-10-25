<?php
   function calcolaIncassoTotale($prodotti) {
    $totaleIncasso = 0;
    foreach ($prodotti as $prodotto) {
        $incasso = (int)$prodotto['quantita'] * (int)$prodotto['prezzo'];
        $totaleIncasso += $incasso;
    }
    return $totaleIncasso;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>prodotti in php</title>
</head>
<body>
    <?php
     $prodotti=[];
     $action= isset($_GET["action"]) ? (int)$_GET["action"]: 0; // Converto action in un numero intero

    $fileName="./FileTxT/prodotti.txt";
    $testo=fopen($fileName,"r") or exit("impossibile caricare il file");
    while (!feof($testo)) {
        $riga=explode(";",fgets($testo));
        $prodotti[] = [
            'nome' => $riga[0],
            'negozio' => $riga[1],
            'quantita' => $riga[2],
            'prezzo' => $riga[3],
            'tipologia'=> $riga[4]
        ];
    }
    fclose($testo);

        switch ($action) {
            case 1:
                echo '<h2>Tutti i Prodotti</h2>';
                echo '<table>';
                foreach($prodotti as $prodotto){
                    $incasso = (int)$prodotto['quantita'] * (int)$prodotto['prezzo'];
                    echo "<tr>";
                    echo '<td>' . $prodotto['nome'] . '</td>';
                    echo '<td>' . $prodotto['negozio'] . '</td>';
                    echo '<td>' . $prodotto['quantita'] . '</td>';
                    echo '<td>' . $prodotto['prezzo'] . '</td>';
                    echo '<td>' . $incasso . '</td>';
                    echo '</tr>';

                }

                echo "</table>";
                echo "<h2>Incasso totale ".calcolaIncassoTotale($prodotti)." Euro</h2>";
                break;

            case 2:
                
                echo '<h2>Aggiungi un Prodotto</h2>';
                echo '<form method="post">';
                echo 'Nome: <input type="text" name="nome" id="nome"><br>';
                echo 'negozio: <input type="text" name="negozio" id="negozio"><br>';
                echo 'Quantità: <input type="number" name="quantita" id="quantita"><br>';
                echo 'Prezzo: <input type="number" step="0.01" name="prezzo" id="prezzo"><br>';
                echo 'Tipologia: <input type="text" name="tipologia" id="tipologia"><br>';
                echo '<input type="submit" value="Aggiungi">';
                echo '</form>';

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $Newnome=$_POST["nome"];
                    $Newnegozio=$_POST["negozio"];
                    $Newquantita=$_POST["quantita"];
                    $Newprezzo=$_POST["prezzo"];
                    $Newtipologia=$_POST["tipologia"];

                    $newLine="\n".$Newnome.";". $Newnegozio. ";". $Newquantita. ";". $Newprezzo. ";". $Newtipologia;

                    $testo=fopen($fileName,"a") or exit("impossibile aprire il file per la modifica");
                    fwrite($testo,$newLine);

                    fclose($testo);
                }
                
                break;
            case 3:
                echo "<h2>Ricerca Filtrata</h2>";
                echo '<form method="post">';
                echo 'Nome: <input type="text" name="ricerca" id="ricerca"><br>';
                echo '<input type="submit" value="Cerca">';
                echo "</form>";

                if ($_SERVER['REQUEST_METHOD']== 'POST') {
                    $ricerca=$_POST['ricerca'];

                    echo "<table>";
                    echo '<tr><th>Nome</th><th>Categoria</th><th>Quantità</th><th>Prezzo</th></tr>';

                    foreach($prodotti as $prodotto){
                        if (stripos($prodotto['tipologia'],$ricerca) !== false) {
                            echo "<tr>";
                            echo '<td>' . $prodotto['nome']. '</td>';
                            echo '<td>' . $prodotto['negozio'] . '</td>';
                            echo '<td>' . $prodotto['quantita'] . '</td>';
                            echo '<td>' . $prodotto['prezzo'] . '</td>';
                            echo '<td>' . $prodotto['tipologia'] . '</td>';
                            echo '</tr>'; 
                        }
                    }
                        
                    echo "</table>"; 
                }
                break;
            case 4:
                echo "<h2>Prodotti venduti per citta</h2>";
                echo '<form method="post">';
                echo 'Nome: <input type="text" name="negozio" id="negozio"><br>';
                echo '<input type="submit" value="Cerca">';
                echo "</form>";

                if ($_SERVER['REQUEST_METHOD']== 'POST') {
                    $ricerca=$_POST['negozio'];
                    $incasso=0;

                    foreach($prodotti as $prodotto){
                        if (stripos($prodotto['negozio'],$ricerca) !== false) {
                            $incasso += (int)$prodotto['quantita'] * (int)$prodotto['prezzo'];
                        }
                    }
                    
                    echo "<h3>"."l'incasso totale della citta " .$ricerca. " e' di " .$incasso."</h3>";
                }
                
                break;
                        
            default:
                # code...
                break;
        }
    ?>
</body>
</html>