<!-- 
	Denne filen skal behandle data som har blitt sendt til The Things Network.
	Her er tanken å behandle JSON-data fortløpende og lagre dette i databasen som har blitt opprettet. 
	For å få til dette er vi også nødt til å parse strengen slik at den er kompatibel med databasen.-->
<!DOCTYPE html>
<html>
<head>
	<title>savethedata</title>
	<meta charset="utf-8">
</head>
<body>
<?php 
//Tilkoblingsinfo
$servername = "mysql.stud.iie.ntnu.no";
$username = "christng";
$password = "PZubNMih";
$dbname = "christng";

//Kobler til databasen
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully \n <br>";

$conn->set_charset("utf8");
//Testdata/innmat for test-tabellen
/*
$data = '{
	"fornavn": "Lars",
	"etternavn": "Lundheim",
	"alder": "50"
}';

$person = json_decode($data);

//Dette funker for å sette inn data
$sql = "INSERT INTO test (test_id, fornavn, etternavn, alder)
		VALUES (NULL, '$person->fornavn', '$person->etternavn', $person->alder)";
//Kobler til tabellen og gjør utfører en sql-setning
if ($conn->query($sql) === TRUE) {
	echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

*/



#Dette er koden for behandling av data og lagring i databasen.


$headers = getallheaders();

/*if($headers["Authorization"] == "luckybird") {
	$data = file_get_contents('php://input');

	$state = json_decode($data, TRUE);

	$mydata = array(
			'led' => $state['payload_fields']['led'],
			'time' => $state['metadata']['time'],
			'gateways' => $state['metadata']['gateways']
		);
	if ($mydata['led']) {
		$aktivitet = $mydata['led'];
	} else {
		$aktivitet = 0;
	}
}*/

if($headers["Authorization"] == "ttt4255") {
	$data = file_get_contents('php://input');

	$state = json_decode($data, TRUE);

	$mydata = array(
			'count' => $state['payload_fields']['count'],
			'humidity' => $state['payload_fields']['humidity'],
			'temperature' => $state['payload_fields']['temp'],
			'id' => $state['payload_fields']['id']
		);
	
	$count = $mydata['count'];
	$humidity = $mydata['humidity'];
	$temperature = $mydata['temperature'];
	$id = $mydata['id'];
	$battery = 0;
}



/*$humidity = $state->humidity_level;
$temperature = $state->temperature_level;
$luckybird_id = $state->luckybird_id;
$bird_count = $state->bird_count;
$battery_level = $state->battery_level;

$sql = "INSERT INTO state (state_id, humidity_level, temperature_level, luckybird_id, bird_count, battery_level, day) 
		VALUES (NULL, $humidity, $temperature, $luckybird_id, $bird_count, $battery_level, NOW())";
*/

$sql = "INSERT INTO testState (testState_id, humidity_level, temperature_level, luckybird_id, bird_count, battery_level, day)
		VALUES (NULL, $humidity, $temperature, $id, $count, $battery, NOW())";


if ($conn->query($sql) === TRUE) {
	echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
</body>
</html>