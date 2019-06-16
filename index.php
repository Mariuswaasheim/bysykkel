<!DOCTYPE html>
<html>
<head>
    <!--Generell metadata. Viewport tillater scaling for mobiler og tablets, lang setter språk for skjermlesere og annet. Keywords gjør det enklere for søkemotorer å finne siden -->
    <title>Bysykkel Stasjonsinformasjon</title>
    <meta charset="UTF-8">
    <meta name="description" content="Oversikt over tilgjengelige bysykler i Oslo">
    <meta name="keywords" content="bysykkel, Oslo, oversikt, tilgjengelig, kollektiv">
    <meta name="author" content="Marius W. Aasheim">
    <meta lang="nb">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
//Henter stasjonsinformasjon
$stasjoner_url = "https://gbfs.urbansharing.com/oslobysykkel.no/station_information.json";
$stasjoner_datasett = json_decode(execute($stasjoner_url),true);
$stasjoner = $stasjoner_datasett['data']['stations'];

//Henter informasjon om plasser
$plasser_url = "https://gbfs.urbansharing.com/oslobysykkel.no/station_status.json";
$plasser_datasett = json_decode(execute($plasser_url), true);
$plasser = $plasser_datasett['data']['stations'];

//oppretter arrays for enkel matching av stasjonsid og navn/addresse
$stasjoner_navn = array();
$stasjoner_adresse = array();

//Setter opp arrayene for å enklere kunne printe ut riktig stasjonsnavn til riktig ID
foreach($stasjoner as $p){
    $stasjoner_navn[$p['station_id']] = $p['name'];
    $stasjoner_adresse[$p['station_id']] = $p['address'];
}

//Skriver ut informasjonen
echo"<table> <tr><th>Stasjonnavn</th><th>Adresse</th><th>Ledige sykler</th><th>Ledige plasser</th></tr>";
foreach($plasser as $text){
    echo "<tr><td>".$stasjoner_navn[$text['station_id']]."</td>"
    ."<td>".$stasjoner_adresse[$text['station_id']]."</td>"
    ."<td>".$text['num_bikes_available']."</td>"
    ."<td>".$text['num_docks_available']."</td></tr>";
}
echo "</table>";

//Setter opp CURL-requesten og sender den. Skriver ut eventuelle feilmeldinger som kommer.
function execute($url)
{
    $client_name = [
        'client-name' => 'Mwaasheim-Bysykkel'
    ];

    $ch = curl_init($url);

    //Setter options for forespørselen, og sender klient-navn jmf. forespørsel fra Bysykkel.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$client_name);

    //Håndterer svar mottatt
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $errno = curl_errno($ch);

    //Hvis forespørselen var vellykket
    if (is_resource($ch)) {
        curl_close($ch);
    }

    //Hvis noe gikk galt
    $feilmelding = "Noe gikk galt ved innhenting av data. Vi gjør vårt beste for å løse problemet! Vennligst prøv igjen senere. <br> Feilmelding: ";
    if (0 !== $errno) {
        throw new \RuntimeException($feilmelding.$error, $errno);
    }

    return $response;
}
?>
</body>
</html>