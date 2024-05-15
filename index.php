<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Pokémon Lookup Onepage</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sticky-footer-navbar/">

    <!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<link href="css/main.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">
  </head>
  <body class="d-flex flex-column h-100">
   
<?php	
			if(isset($_GET["error"])){
				echo '<div class="error"><p>No Pokemon found...</p></div>'; 
			}
			
			if(isset($_GET["pokemon"])) {
				$search = $_GET["pokemon"];
			}
			else{
				$search = "magikarp";
			}
				try{
					if(isset($_GET["natdex"])){
						$json = getSmogonJson($search, "sm");
					}
					else{
						$json = getSmogonJson($search);
					}
					
					//$strJsonFileContents = file_get_contents("pokemon.json");
					//var_dump($strJsonFileContents); // show contents
					
					//echo "Json: " . $json;
		
					$pokemonArray = json_decode($json, true);
										
					//var_dump($pokemonArray);
					//echo htmlspecialchars($json);

					if(!isset($pokemonArray["injectRpcs"][1][1]["pokemon"])){ 
						echo '<div class="error"><p>No Pokemon found...</p></div>'; 
						$search = "magikarp";
						$json = getSmogonJson("magikarp"); 
						$pokemonArray = json_decode($json, true);
						
					}
					$success = false;
					foreach($pokemonArray["injectRpcs"][1][1]["pokemon"] as $pkmn){
						//echo strtolower($pkmn["name"]) . " vs. " . strtolower($search) . "<br>";
						
						if (strpos(strtolower($pkmn["name"]), strtolower($search)) === 0){
							
							
							$success = true;
							if(strtolower($pkmn["name"] !== strtolower($search))){
								$json = getSmogonJson($pkmn["name"]);
								$pokemonArray = json_decode($json, true);
							}
							
							//file_put_contents("pokemon.json", $json, LOCK_EX);
							
							//Dirty bugfix for mew OwO
							if(strtolower($search) == "mew" && strtolower($pkmn["name"]) == "mewtwo"){ continue; }
?>
   
<header>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Pokémon Lookup</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://bulbapedia.bulbagarden.net/w/index.php?title=Special%3ASearch&search=<?php echo lowerDash($pkmn["name"]); ?>&go=Go" target="_blank">Bulbapedia</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="https://www.smogon.com/dex/<?php if(isset($_GET["gen"])){ echo "sv"; } else { echo "sm"; }?>/pokemon/<?php echo lowerDash($pkmn["name"]); ?>/"  target="_blank">Smogon</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="https://pokemondb.net/type/"  target="_blank">Type Chart</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="https://bulbapedia.bulbagarden.net/wiki/List_of_Pok%C3%A9mon_by_National_Pok%C3%A9dex_number"  target="_blank">Pokédex</a>
          </li>
        </ul>
        <form class="d-flex" action="index.php" method="get">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="pokemon">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
</header>

<!-- Begin page content -->
<main class="flex-shrink-0">
	<div class="container">
		<div class="row">
			<div class="col-12 d-md-none">
				<form class="d-flex" action="index.php" method="get">
				  <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="pokemon">
				  <button class="btn btn-outline-success" type="submit">Search</button>
				</form>
			</div>
		</div>
		<div class="row flex-align">
			<div class="col-12 col-md-4">
				<img src="https://www.smogon.com//dex/media/sprites/xy/<?php echo lowerDash($pkmn["name"]); ?>.gif"></img>
			</div>
	
			<div class="col-12 col-md-4">
				<?php 
					echo '<medium class="h6">' . $pkmn["name"] . '</medium>'; 
					if($pkmn["oob"]["evos"]){
						echo "<br>" . getEvoData(($pkmn["name"]));
					}
				?>
			</div>
			<div class="col-12 col-md-2">
				<?php echo getCombinedStatsAsProgressBar($pkmn); ?>
				<div class="progress position-relative">
					<div class="progress-bar progress-bar-striped bg-<?php echo $pkmn["types"][0]; ?>" role="progressbar" style="width:100%"></div>
					<medium class="justify-content-center d-flex position-absolute w-100 h6 strokeme">
						<a href="https://pokemondb.net/type/<?php echo lowerDash($pkmn["types"][0]); ?>" target="_blank"><?php echo $pkmn["types"][0]; ?></a>
					</medium>
				</div>
			</div>
			<div class="col-12 col-md-2">
				<?php echo getTierAsProgressBar($pkmn["formats"][0], $pkmn["name"]) ?>
				<div class="progress position-relative">
					<div class="progress-bar progress-bar-striped bg-<?php if(isset($pkmn["types"][1])){ echo $pkmn["types"][1]; }else{ echo "None"; } ?>" role="progressbar" style="width:100%"></div>
					<medium class="justify-content-center d-flex position-absolute w-100 h6 strokeme">
						<a href="https://pokemondb.net/type/<?php if(isset($pkmn["types"][1])){ echo $pkmn["types"][1]; } ?>" target="_blank"><?php if(isset($pkmn["types"][1])){ echo $pkmn["types"][1]; } ?></a>
					</medium>
					
				</div>
			</div>
		</div>
			
		<div class="placeholder-row"> </div>
			
		<div class="row">
			<div class="col-12 col-md-4">			
					<?php
					foreach($pkmn["abilities"] as $ability){
						?>
						<a href="https://www.smogon.com/dex/sv/abilities/<?php echo lowerDash($ability); ?>" target="_blank"><?php echo $ability; ?></a><br>
						<?php
						echo showAbilityInfo($ability);	
						echo "<br><br>";
					}
					?>				
			</div>
			<div class="col-12 col-md-8">
				<div class="HP"><?php echo generateStatsProgressBar($pkmn["hp"], "HP"); ?></div>
				<div class="Atk"><?php echo generateStatsProgressBar($pkmn["atk"], "Atk"); ?></div>
				<div class="SpAtk"><?php echo generateStatsProgressBar($pkmn["spa"], "SpAtk"); ?></div>
				<div class="Def"><?php echo generateStatsProgressBar($pkmn["def"], "Def"); ?></div>
				<div class="SpDef"><?php echo generateStatsProgressBar($pkmn["spd"], "SpDef"); ?></div>
				<div class="Speed"><?php echo generateStatsProgressBar($pkmn["spe"], "Speed"); ?></div>
				
			</div>
		</div>
		
		<div class="placeholder-row"> </div>
		
		<div class="row">
			<div class="type-matchup">
			<?php 
				$type1 = "";
				if(isset($pkmn["types"][1])){
					$type1 = $pkmn["types"][1];
				}
				displayTypeInfo( getTypeInfo($pkmn["types"][0], $type1, "defensive"), "defensive" ); 
			?></div>
		</div>
		<div class="row">
			<div class="type-matchup">
			<?php 
				displayTypeInfo( getTypeInfo($pkmn["types"][0], $type1, "offensive"), "offensive" ); 
			?></div>
		</div>
		
		<div class="placeholder-row"> </div>
			
		<div class="row">
			<?php
				if(isset($pokemonArray["injectRpcs"][2][1]["strategies"][0]["movesets"])){
					foreach($pokemonArray["injectRpcs"][2][1]["strategies"][0]["movesets"] as $moveset){
						echo '<div class="col-12 col-md-4">';
						echo "<b>" . $moveset["name"] . "</b><br>";
						echo "<br><b>Ability</b>: ";
						foreach($moveset["abilities"] as $ability){
							echo '<a data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="' . returnAbilityDescription($ability, $pokemonArray) . '">' . $ability . "</a> ";
						}
						echo "<br><b>Item</b>: ";
						foreach($moveset["items"] as $item){
							echo '<a data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="' . returnItemDescription($item, $pokemonArray) . '">' . $item . "</a> ";
						}
						echo "<br><b>Nature</b>: ";
						foreach($moveset["natures"] as $nature){
							echo '<a data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="' . returnNatureSummary($nature, $pokemonArray) . '">' . $nature . "</a> ";	
						}
						echo "<br>";
						foreach($moveset["evconfigs"] as $ev){
							if($ev["hp"] > 0){
								echo "<b>HP</b>: " . $ev["hp"] . " ";	
							}
							if($ev["atk"] > 0){
								echo "<b>ATK</b>: " . $ev["atk"] . " ";	
							}
							if($ev["def"] > 0){
								echo "<b>DEF</b>: " . $ev["def"] . " ";	
							}
							if($ev["spa"] > 0){
								echo "<b>SPA</b>: " . $ev["spa"] . " ";	
							}
							if($ev["spd"] > 0){
								echo "<b>SPD</b>: " . $ev["spd"] . " ";	
							}
							if($ev["spe"] > 0){
								echo "<b>SPE</b>: " . $ev["spe"] . " ";	
							}
							echo "<br>";
						}
						
						echo "<br>";

						foreach($moveset["moveslots"] as $moveslot){
							?>
								<div class="progress position-relative" style="width:100%;">
									<div class="progress-bar progress-bar-striped bg-<?php echo returnMoveType($moveslot[0]["move"], $pokemonArray); ?>" role="progressbar" style="width:100%"></div>
									<medium class="justify-content-center d-flex position-absolute w-100 h6 strokeme">
										<a data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?php echo returnMoveDescription($moveslot[0]["move"], $pokemonArray); ?>"><?php echo $moveslot[0]["move"] ?></a>
									</medium>
								</div>
							<?php
							
							displayMoveInfoAsHTML($moveslot[0]["move"], $pokemonArray);
						}
						
						echo "<br /><br />";
						echo "</div>";
					}
				}
			?>
		</div>
	</div>
		
		
		<?php
							break;
						}
					}
					
					if(!$success){
						//header('Location: index.php?pokemon=magikarp&error=true');
						?>
						<meta http-equiv="refresh" content="0; url=index.php?pokemon=magikarp&error=true" />
						<?php
					}
				}
				catch (Exception $e){
					echo '<div class="error"><p>No Pokemon found...</p></div>';
				}
		?>
	
	  
</main>

<footer class="footer mt-auto py-3 bg-light">
  <div class="container">
    <span class="text-muted">
		Made with ♥ by <a href="https://www.adarkhero.de" target="_blank">ADarkHero</a> | Ressources by <a href="https://www.smogon.com" target="_blank">Smogon</a> and <a href="https://pokeapi.co" target="_blank">PokeAPI</a>
	</span>
  </div>
</footer>

	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/jquery-3.7.1.min.js"></script>

	
	<script>
		const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
		const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
	</script>

      
  </body>
</html>


<?php

function lowerDash($str){
	$strNew = strtolower($str);
	$strNew = str_replace(" ", "-", $strNew);
	return $strNew;
}

function getCombinedStats($pkmn){
	return $pkmn["hp"] + $pkmn["atk"] + $pkmn["spa"] + $pkmn["def"] + $pkmn["spd"] + $pkmn["spe"];
}



function getCombinedStatsAsProgressBar($pkmn){
	$stats = getCombinedStats($pkmn);
	$color = "";
	
	switch(true){
		case $stats >= 575: $color = ""; break;
		case $stats >= 500: $color = "bg-success"; break;
		case $stats >= 400: $color = "bg-info"; break;
		case $stats >= 300: $color = "bg-warning"; break;
		default: $color = "bg-danger"; break;
	}
	
	return returnProgressBar($color, '100', '', $stats);
}



function generateStatsProgressBar($value, $type){
	$progress = $value * 0.75;
	$text = $type . ": ";
	$color = "";
	
	switch(true){
		case $value >= 110: $color = ""; break;
		case $value >= 95: $color = "bg-success"; break;
		case $value >= 80: $color = "bg-info"; break;
		case $value >= 65: $color = "bg-warning"; break;
		default: $color = "bg-danger"; break;
	}
	return returnProgressBar($color, $progress, $text, $value);
}



function getTierAsProgressBar($tier, $pkmnName){
	$color = "";
	$text = $tier . " / ";
	
	switch($tier){
		case "Uber": $text .= "S"; $color = ""; break;
		case "OU": $text .= "A"; $color = "bg-success"; break;
		case "OUBL": $text .= "A"; $color = "bg-success"; break;
		case "UU": $text .= "B"; $color = "bg-info"; break;
		case "UUBL": $text .= "B"; $color = "bg-info"; break;
		case "RU": $text .= "C"; $color = "bg-warning"; break;
		case "RUBL": $text .= "C"; $color = "bg-warning"; break;
		case "NU": $text .= "D"; $color = "bg-danger"; break;
		case "NUBL": $text .= "D"; $color = "bg-danger"; break;
		case "PU": $text .= "E"; $color = "bg-danger"; break;
		case "PUBL": $text .= "E"; $color = "bg-danger"; break;
		case "LC": $text .= "?"; $color = "bg-dark"; break;
		case "NFE": $text .= "?"; $color = "bg-dark"; break;
		case "National Dex": 
			$text .= "?"; 
			$color = "bg-dark"; 
			if(!isset($_GET["natdex"])){
				echo '<meta http-equiv="refresh" content="0; url=index.php?pokemon=' . $pkmnName . '&natdex=true" />';
			}
			break;
		default: $text .= "F"; $color = "bg-danger"; break;
	}
	
	
	return returnProgressBar($color, '100', $text, '');
}



function returnProgressBar($color, $progress, $text, $value){
	$html = '
	<div class="progress position-relative">
		<div class="progress-bar progress-bar-striped '.$color.'" role="progressbar" style="width:'.$progress.'%"></div>
		<medium class="justify-content-center d-flex position-absolute w-100 h6 strokeme">'.$text.$value.'</medium>
	</div>
	';
	return $html;
}



function showAbilityInfo($ability){
	try{		
		$strJsonFileContents = file_get_contents("pokemon.json");
		//var_dump($strJsonFileContents); // show contents
		
		$pokemonArray = json_decode($strJsonFileContents, true);
		
		foreach($pokemonArray["injectRpcs"][1][1]["abilities"] as $abi){
			if (strpos(strtolower($abi["name"]), strtolower($ability)) === 0){
				return $abi["description"];
			}
		}
	}
	catch(Exception $ex){
	}
}



function getTypeInfo($type1, $type2, $offDef){
	try{						
		$effectiveness_array = array();
		if($type2 != ""){
			$types_array = array($type1, $type2);
		}
		else{
			$types_array = array($type1);
		}
		
		
		foreach($types_array as $type){
			$filename = "json/types/" . $type . ".json";
			$json = "";

			if(file_exists($filename)){
				$json = file_get_contents($filename);
			}
			//If the data was not crawled, download it from smogon
			else{
				//Check if url exists
				$url = "https://pokeapi.co/api/v2/type/" . lowerDash($type) . "/";
				
				$headers = get_headers($url);
				$head = $headers[0];
				if(strpos($head, "200")){
					$json = file_get_contents($url);
					
					file_put_contents($filename, $json, LOCK_EX);
				}
			}		
			$typesArray = json_decode($json, true);
			
			if(strtolower($offDef) == "offensive"){
				$str = "to";
			}
			else{
				$str = "from";
			}

			foreach($typesArray["damage_relations"]["double_damage_" . $str] as $val){
				if(isset($effectiveness_array[$val["name"]])){
					$effectiveness_array[$val["name"]] = $effectiveness_array[$val["name"]] * 2;
				}
				else{
					$effectiveness_array[$val["name"]] = 2;
				}
			}
			foreach($typesArray["damage_relations"]["half_damage_" . $str] as $val){
				if(isset($effectiveness_array[$val["name"]])){
					$effectiveness_array[$val["name"]] = $effectiveness_array[$val["name"]] * 0.5;
				}
				else{
					$effectiveness_array[$val["name"]] = 0.5;
				}
			}
			foreach($typesArray["damage_relations"]["no_damage_" . $str] as $val){
				if(isset($effectiveness_array[$val["name"]])){
					$effectiveness_array[$val["name"]] = $effectiveness_array[$val["name"]] * 0;
				}
				else{
					$effectiveness_array[$val["name"]] = 0;
				}
			}
		}

		return $effectiveness_array;
	}
	catch(Exception $ex){
	}
}

/*
* typeInfo = array from getTypeInfo
*/
function displayTypeInfo($typeInfo, $title){
	arsort($typeInfo);
	
	?>
		<div class="type-eff col-5 col-md-1">
			<div class="progress position-relative progress-type type-neutral">
				<div class="progress-bar progress-bar-striped <?php if($title == "offensive"){echo "bg-danger"; } ?>" role="progressbar" style="width:100%"></div>
				<medium class="justify-content-center d-flex position-absolute w-100 h6 strokeme"><?php echo ucfirst($title); ?></medium>
			</div>
		</div>
		<?php
	
	foreach($typeInfo as $key => $val){
		if($val != 1){
		?>
		<div class="type-eff col-5 col-md-1">
			<div class="progress position-relative progress-type type-<?php echo $title . str_replace(".", "", $val); ?>">
				<div class="progress-bar progress-bar-striped bg-<?php echo ucfirst($key); ?>" role="progressbar" style="width:100%"></div>
				<medium class="justify-content-center d-flex position-absolute w-100 h6 strokeme"><?php echo ucfirst($key) . "<br>x" . $val; ?></medium>
			</div>
		</div>
		<?php
		}
	}
}


function getEvoData($pkmn){
	try{			
		$pkmn = lowerDash($pkmn);
		$filename = "json/pokemon-species/" . $pkmn . ".json";
		$json = "";
		$evoBase = "evolves to: ";
		$evo = $evoBase;

		if(file_exists($filename)){
			$json = file_get_contents($filename);
		}
		//If the data was not crawled, download it from smogon
		else{
			//Check if url exists
			$url = "https://pokeapi.co/api/v2/pokemon-species/" . lowerDash($pkmn) . "/";
			
			$headers = get_headers($url);
			$head = $headers[0];
			if(strpos($head, "200")){
				$json = file_get_contents($url);
				
				file_put_contents($filename, $json, LOCK_EX);
			}
		}	
		
		$speciesArray = json_decode($json, true);
				
		$filename = "json/evolution-chain/" . $pkmn . ".json";
		$json = "";

		if(file_exists($filename)){
			$json = file_get_contents($filename);
		}
		//If the data was not crawled, download it from smogon
		else{
			//Check if url exists
			$url = $speciesArray["evolution_chain"]["url"];
			
			$headers = get_headers($url);
			$head = $headers[0];
			if(strpos($head, "200")){
				$json = file_get_contents($url);
				
				file_put_contents($filename, $json, LOCK_EX);
			}
		}
		$evoArray = json_decode($json, true);
		foreach($evoArray["chain"]["evolves_to"] as $curEvo){
			//Only get NEXT evolutions, not PAST ones
			//Start of chain
			if($evoArray["chain"]["species"]["name"]  == $pkmn){
				if($evo != $evoBase){ $evo = $evo . "<br>"; }
				$evo = $evo . getEvoCondition($curEvo);
			}
			
			if($curEvo["species"]["name"] == $pkmn){				
				foreach($curEvo["evolves_to"] as $curEvo2){
					if($evo != $evoBase){ $evo = $evo . "<br>"; }
					$evo = $evo . getEvoCondition($curEvo2);
				}		
			}
		}

		return $evo;
	}
	catch(Exception $ex){
	}
}

function getEvoCondition($evoArr){	
	$evoConditions = "";

	foreach ($evoArr["evolution_details"] as $evos){
		foreach ($evos as $key => $value){
			if(!($value == null)){				
				if(is_array($value) && $key != "trigger"){	
					if($evoConditions != ""){ $evoConditions = $evoConditions . "; "; } //Space after first condition		
					$evoConditions = $evoConditions . $key . ": " . $value["name"];
				}
				else if($key != "trigger"){
					if($evoConditions != ""){ $evoConditions = $evoConditions . "; "; } //Space after first condition
					$evoConditions = $evoConditions . $key . ": " . $value;
				}		
			}
		}
	}
	
	$pkmnName = ucfirst($evoArr["species"]["name"]);
	return '<a href="index.php?pokemon='.$pkmnName.'">' . $pkmnName . "</a> (" . $evoConditions . ")";
}

/*
* move = string
*/
function displayMoveInfoAsHTML($move, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["moves"] as $moveInfo){
		if (strtolower($move) == strtolower($moveInfo["name"])){		
			echo '<table class="table table-bordered table-responsive table-sm">';
				echo "<tbody>";
					echo "<tr>";
						echo '<td class="type"><a href="https://pokemondb.net/type/' . lowerDash($moveInfo["type"]) . '"  target="_blank">' . $moveInfo["type"] . "</td>";
						echo '<td class="category">' . str_replace("Non-Damaging", "No-dmg", $moveInfo["category"]) . "</td>";
						echo '<td class="power text-end">' . $moveInfo["power"] . " DMG</td>";
						echo '<td class="accuracy text-end">' . $moveInfo["accuracy"] . "%</td>";
						echo '<td class="priority text-end">' . $moveInfo["priority"] . " Pr.</td>";
						echo '<td class="pp text-end">' . $moveInfo["pp"] . " PP</td>";
					echo "</tr>";
				echo "</tbody>";
			echo "</table>";
		}
	}
}

function returnMoveType($move, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["moves"] as $moveInfo){
		if (strtolower($move) === strtolower($moveInfo["name"])){	
			return $moveInfo["type"];
		}
	}		
}

function returnMoveDescription($move, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["moves"] as $moveInfo){
		if (strpos(strtolower($move), strtolower($moveInfo["name"])) === 0){	
			return $moveInfo["description"];
		}
	}		
}

function returnNatureSummary($nature, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["natures"] as $natureInfo){
		if (strpos(strtolower($nature), strtolower($natureInfo["name"])) === 0){	
			return $natureInfo["summary"];
		}
	}		
}

function returnItemDescription($item, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["items"] as $itemInfo){
		if (strpos(strtolower($item), strtolower($itemInfo["name"])) === 0){	
			return $itemInfo["description"];
		}
	}		
}

function returnAbilityDescription($ability, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["abilities"] as $abilityInfo){		
		if (strpos(strtolower($ability), strtolower($abilityInfo["name"])) === 0){	
			return $abilityInfo["description"];
		}
	}		
}

function getSmogonJson($pkmn, $gen = null){
	try{
		if($gen === null){ $gen = "sv"; }
		
		$pkmnLower = lowerDash($pkmn);
		$filename = "json/". $pkmnLower . "_" . $gen . ".json";
		//If the data was already crawled, load it from file
		if(file_exists($filename)){
			$json = file_get_contents($filename);
		}
		//If the data was not crawled, download it from smogon
		else{
			//Check if url exists
			$url = "https://www.smogon.com/dex/" . $gen . "/pokemon/" . $pkmnLower . "/";
			$headers = get_headers($url);
			$head = $headers[0];
			if(strpos($head, "200")){
				$content = file_get_contents($url);
				$json = strstr($content, '<script type="text/javascript">');
				$json = substr($json, 0, strpos($json, "</script>"));
				$json = substr($json, strpos($json, "dexSettings = {")+14, strlen($json));
				
				file_put_contents($filename, $json, LOCK_EX);
			}
			else
			{
				$json = file_get_contents("pokemon.json");
			}	
		}
		return $json;
	}
	catch(Exception $ex){
		$json = file_get_contents("pokemon.json");
		return $json;
	}
}


/*

$content = file_get_contents('https://www.smogon.com/dex/ss/pokemon/' . $_GET["pokemon"]);
										
$json = strstr($content, '<script type="text/javascript">');
$json = substr($json, 0, strpos($json, "</script>"));



// add your JS into $content
echo htmlspecialchars($json);

*/