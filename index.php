<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokedex in PHP</title>
</head>
<body>

<form id="inputField" method="post">
    <h1>Pokemon Name</h1>
    <label for="PokemonName"></label><input type="text" id="PokemonName" placeholder="Enter Pokemon or id here" name="PokemonName"/>
    <button type="submit" name="button" value="button" id="callApi">
    Search </button>
</form>

<?php

    //Get input
    if (isset($_POST['button']) && !empty($_POST['PokemonName'])){
        $pokemonName = $_POST['PokemonName'];
        getArray($pokemonName);
    }

    //Get content from API
    function getArray($userInput){
        $pokeData = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $userInput . "/");
        getInformation($pokeData);
    }

    //Get relevant data of API
    function getInformation($pokeData){
        $response = json_decode($pokeData, true);//With true it converts it to an array
        //URL of species
        $speciesUrl = $response['species']['url'];
        //Extract name of pokemon
        $namePoke = $response['name'];
        //extract id
        $idPoke = $response['id'];
        //extract the img
        $imgPoke = $response['sprites']['front_default'];
        //extract moves
        $movesPoke = $response['moves'];
        //Make array of max 4 moves
        $arrayMoves = array();
        if(count($movesPoke > 4)){
            for($i=0 ; $i < 4; $i++){
                array_push($arrayMoves, $movesPoke[$i]['move']['name']);
            }
        } else {
            foreach ($movesPoke as $key => $trend){
                $arrayMoves[$key] = $trend['move']['name'];
            }
        }

        printData($namePoke, $idPoke, $imgPoke, $arrayMoves);
        previousForm($speciesUrl);
    }

    //Show data on the page
    function printData($namePoke, $idPoke, $imgPoke, $movesPoke){
        echo '<div class="divStyle">
        <h2 class="nameStyle">' . $idPoke . '</h2>
        <h2 class="nameStyle"> ' . $namePoke . '</h2>
        <img class="imageStyle" src="' . $imgPoke . '" alt="sprite of poke">
        <ul class="nameStyle"> Abilities ';

        for ($i = 0; $i < 4; $i++) {
            echo '<li class="nameStyle"> ' . $movesPoke[$i] . '</li>';
        }
        echo '</ul>
              </div>';
    }

    //Get previous form if there is one
    function previousForm($speciesUrl){
        $speciesData = file_get_contents($speciesUrl);
        $responseSpeciesData = json_decode($speciesData, true);
        $previousEvolution = $responseSpeciesData['evolves_from_species'];
        if ($previousEvolution != null){
            echo $previousEvolution;
            getArray($responseSpeciesData['evolves_from_species']['name']);
        } else {
            echo '<div class="divStyle">
        <h3 class="nameStyle">This is the first evolution. </h3>  </div>';
        }
    }

?>

</body>
</html>



