<?php

//$dbconn = pg_connect("dbname=marie");
// connexion à une base de données nommée "marie"

//$dbconn2 = pg_connect("host=localhost port=5432 dbname=papao");
// connexion à une base de données nommée "marie" sur l'hôte "localhost" sur le port "5432"

$dbconn3 = pg_connect("host=localhost port=5432 dbname=papao user=postgres password=9ovHLcZvDraaq4NH");
// connexion à une base de données nommée "marie" sur l'hôte "mouton" avec un
// nom d'utilisateur et un mot de passe

//$conn_string = "host=mouton port=5432 dbname=test user=agneau password=bar";
//$dbconn4 = pg_connect($conn_string);
// connexion à une base de données nommée "test" sur l'hôte "mouton" avec un
// nom d'utilisateur et un mot de passe

//$dbconn5 = pg_connect("host=localhost options='--client_encoding=UTF8'");
//connexion à la base sur "localhost" et passage d'un paramètre de la ligne de
// commande concernant l'encodage UTF-8

if(!$dbconn3){
    echo "Error be manadala";
}


$url = "https://www.cdiscount.com/juniors/radiocommande-robot/tumbling-crosslander-r-voiture-telecommandee-re/f-12085-lexrc55.html#mpos=0|cd";
$ch = curl_init();
$timeout = 5;
$cookie = tempnam ("/tmp", "CURLCOOKIE");
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    //var_dump($content);

    $doc = new DOMDocument();
    // nettoyger html pour qu'il parse

//    $doc->loadHTML($content);
//    $books = $dom->getElementsByTagName('p');
//    var_dump($books);
    $file = 'people.txt';
    // Open the file to get existing content
    $current = file_get_contents($file);
    // Append a new person to the file
    $current .= "John Smith\n";
    // Write the contents back to the file
    file_put_contents($file, $content);



//echo $doc->saveHTML();


?>