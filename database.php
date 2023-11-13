<?php

require_once 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$database = "wanreport";

// Create connection

$conn = mysqli_connect($servername, $username, null, $database);

// Check connection

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";

//Create table
$sql = "
    CREATE TABLE IF NOT EXISTS boarding_card (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        city_start VARCHAR(30) NOT NULL,
        city_end VARCHAR(30) NOT NULL,
        seat INT(6) NOT NULL,
        transportation VARCHAR(30) NOT NULL
    ) 
";

if(mysqli_query($conn, $sql)){
    //echo "Table created successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}

$faker = Faker\Factory::create();

for ($i = 0; $i < 56; $i++) {
    $city = ['Nice', 'Antibes', 'Cannes', 'Mandelieu', 'Le Cannet', 'Vallauris', 'Menton', 'Lyon', 'Paris', 'Marseille', 'Chaumont', 'Dijon', 'Bordeaux'];
    $start = array_rand($city);
    $cityStart = $city[array_rand($city)];
    $end = array_rand($city);
    $cityEnd =  $city[$end === $start ? array_rand($city) : $end];
    $seat = $faker->randomDigit();
    $transportation = $faker->randomElement(['bus', 'train', 'plane', 'ferry']);
    $insertSql = "
        INSERT INTO boarding_card (city_start, city_end, seat, transportation)VALUES 
        (
          '$cityStart',
          '$cityEnd',
          $seat,
          '$transportation'
        )
    ";

    if ($conn->query($insertSql) === TRUE) {
        //echo "New record created successfully";
    } else {
        echo "Error: " . $insertSql . "<br>" . $conn->error;
    }
}

echo 'Données générées avec succès';

mysqli_close($conn);
