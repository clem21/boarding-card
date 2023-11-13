<?php

$servername = "localhost";
$username = "root";
$database = "wanreport";

// Create connection
$conn = mysqli_connect($servername, $username, null, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_GET['getItinerary'])) {
    $sql = "SELECT * FROM boarding_card;";
    $alternatives_start = [];
    $alternatives_end = [];
    if($result = $conn->query($sql)) {
        while($obj = $result->fetch_object()) {
            if($obj->city_start === $_GET['start'] && $obj->city_end === $_GET['end']) {
                echo 'TRAJET DIRECT : by ' . $obj->transportation . ' (seat ' . $obj->seat . ')';
                return;
            } elseif($obj->city_start === $_GET['start']) {
                $alternatives_start[] = $obj;
            } elseif($obj->city_end === $_GET['end']) {
                $alternatives_end[] = $obj;
            }
        }

        // Si pas de trajet direct
        foreach($alternatives_start as $alternative_start) {
            foreach($alternatives_end as $alternative_end) {
                if($alternative_start->city_end === $alternative_end->city_start) {
                    echo 'TRAJET AVEC 1 correspondance :<br>'.
                        $alternative_start->city_start . ' - ' . $alternative_start->city_end . ' by ' . $alternative_start->transportation . ' (seat ' . $alternative_start->seat . ')<br>'
                        . $alternative_end->city_start . ' - ' . $alternative_end->city_end . ' by ' . $alternative_end->transportation . ' (seat ' . $alternative_end->seat . ')'
                    ;
                    return;
                }
            }
        }
    }
    echo 'AUCUNE CORRESPONDANCE';
    die;
}

$cities = ['Nice', 'Antibes', 'Cannes', 'Mandelieu', 'Le Cannet', 'Vallauris', 'Menton', 'Lyon', 'Paris', 'Marseille', 'Chaumont', 'Dijon', 'Bordeaux'];

mysqli_close($conn);
?>

<button id="createDatabase">Générer les données</button>
<div id="result_database">
</div>

<label for="start">Ville de départ</label>
<select id="start">
    <?php foreach ($cities as $city) : ?>
        <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
    <?php endforeach; ?>
</select>

<label for="end">Ville d'arrivée</label>
<select id="end">
    <?php foreach ($cities as $city) : ?>
        <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
    <?php endforeach; ?>
</select>

<button id="getItinerary">Obtenir l'itinéraire</button>

<div id="result_itinerary">
</div>

<script>
    document.getElementById('createDatabase').addEventListener('click', async(e) => {
        await fetch("/database.php").then((result) => {
            result.text().then((res) => {
                document.getElementById('result_database').innerHTML = '<p>' + res + '</p>';
            })
        })
    })

    document.getElementById('getItinerary').addEventListener('click', async(e) => {
        await fetch("/index.php?getItinerary=true&start=" + document.getElementById('start').value + "&end=" + document.getElementById('end').value).then((result) => {
            result.text().then((res) => {
                document.getElementById('result_itinerary').innerHTML = '<p>' + res + '</p>';
            })
        })
    })
</script>
