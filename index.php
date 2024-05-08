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
			if(isset($_GET["pokemon"])) {
				try{
					$json = getSmogonJson($_GET["pokemon"]);
					//$strJsonFileContents = file_get_contents("pokemon.json");
					//var_dump($strJsonFileContents); // show contents
		
					$pokemonArray = json_decode($json, true);
										
					//var_dump($pokemonArray);
					//echo htmlspecialchars($json);

					foreach($pokemonArray["injectRpcs"][1][1]["pokemon"] as $pkmn){
						if (strpos(strtolower($pkmn["name"]), strtolower($_GET["pokemon"])) === 0){
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
            <a class="nav-link" href="https://www.smogon.com/dex/sv/pokemon/<?php echo lowerDash($pkmn["name"]); ?>/"  target="_blank">Smogon</a>
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
		<div class="row flex-align">
			<div class="col col-md4">
				<img src="https://www.smogon.com//dex/media/sprites/xy/<?php echo lowerDash($pkmn["name"]); ?>.gif"></img>
			</div>
	
			<div class="col col-md4">
				<?php 
					echo '<medium class="h6">' . $pkmn["name"] . '</medium>'; 
					if($pkmn["oob"]["evos"]){
						echo "<br> ( evolves to ";
						foreach($pkmn["oob"]["evos"] as $evo){
							echo '<a href="index.php?pokemon='.$evo.'">' . $evo . '</a> ';
						}
						echo ")";
					}
				?>
			</div>
			<div class="col col-md2">
				<?php echo getCombinedStatsAsProgressBar($pkmn); ?>
				<div class="progress position-relative">
					<div class="progress-bar progress-bar-striped bg-<?php echo $pkmn["types"][0]; ?>" role="progressbar" style="width:100%"></div>
					<medium class="justify-content-center d-flex position-absolute w-100 h6 strokeme">
						<a href="https://pokemondb.net/type/<?php echo lowerDash($pkmn["types"][0]); ?>" target="_blank"><?php echo $pkmn["types"][0]; ?></a>
					</medium>
				</div>
			</div>
			<div class="col col-md2">
				<?php echo getTierAsProgressBar($pkmn["formats"][0], isset($pkmn["oob"]["evos"])) ?>
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
			<div class="col col-4">			
				Ability 1: <a href="https://www.smogon.com/dex/sv/abilities/<?php echo lowerDash($pkmn["abilities"][0]); ?>" target="_blank"><?php echo $pkmn["abilities"][0]; ?></a><br>
				<?php
					echo showAbilityInfo($pkmn["abilities"][0]);
				?>
				<br><br>
				Ability 2: <a href="https://www.smogon.com/dex/sv/abilities/<?php echo lowerDash($pkmn["abilities"][1]); ?>" target="_blank"><?php echo $pkmn["abilities"][1]; ?></a><br>
				<?php
					echo showAbilityInfo($pkmn["abilities"][1]);
				?>
			</div>
			<div class="col col-8">
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
			<div class="col col-4">
				<?php
					showTypeInfo($pkmn["types"][0]);
					if(isset($pkmn["types"][1])){ showTypeInfo($pkmn["types"][1]); }
				?>
			</div>
			<?php
				if(isset($pokemonArray["injectRpcs"][2][1]["strategies"][0]["movesets"])){
					foreach($pokemonArray["injectRpcs"][2][1]["strategies"][0]["movesets"] as $moveset){
						echo '<div class="col col-4">';
						echo "<b>" . $moveset["name"] . "</b><br>";
						foreach($moveset["abilities"] as $ability){
							echo "Ability: " . $ability . "<br>";	
						}
						foreach($moveset["items"] as $item){
							echo "Item: " . $item . "<br>";	
						}
						foreach($moveset["natures"] as $nature){
							echo "Nature: " . $nature . "<br>";	
						}
						foreach($moveset["evconfigs"] as $ev){
							if($ev["hp"] > 0){
								echo "HP: " . $ev["hp"] . " ";	
							}
							if($ev["atk"] > 0){
								echo "ATK: " . $ev["atk"] . " ";	
							}
							if($ev["def"] > 0){
								echo "DEF: " . $ev["def"] . " ";	
							}
							if($ev["spa"] > 0){
								echo "SPA: " . $ev["spa"] . " ";	
							}
							if($ev["spd"] > 0){
								echo "SPD: " . $ev["spd"] . " ";	
							}
							if($ev["spe"] > 0){
								echo "SPE: " . $ev["spe"] . " ";	
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
				}
				catch (Exception $e){
					echo "No Pokemon found...";
				}
			}
			else{
				echo "No Pokemon searched...";
			}
		?>
	
	  
</main>

<footer class="footer mt-auto py-3 bg-light">
  <div class="container">
    <span class="text-muted">
		Made with ♥ by <a href="https://www.adarkhero.de" target="_blank">ADarkHero</a> | Ressources by <a href="https://www.smogon.com" target="_blank">Smogon</a>
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
		case $stats > 600: $color = ""; break;
		case $stats > 500: $color = "bg-success"; break;
		case $stats > 400: $color = "bg-info"; break;
		case $stats > 300: $color = "bg-warning"; break;
		default: $color = "bg-danger"; break;
	}
	
	return returnProgressBar($color, '100', '', $stats);
}



function generateStatsProgressBar($value, $type){
	$progress = $value/80*50;
	$text = $type . ": ";
	$color = "";
	
	switch(true){
		case $value >= 120: $color = ""; break;
		case $value >= 100: $color = "bg-success"; break;
		case $value >= 80: $color = "bg-info"; break;
		case $value >= 60: $color = "bg-warning"; break;
		default: $color = "bg-danger"; break;
	}
	return returnProgressBar($color, $progress, $text, $value);
}



function getTierAsProgressBar($tier, $hasEvos){
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
		case "National Dex": $text .= "?"; $color = "bg-dark"; break;
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



function showTypeInfo($type){
	try{		
		$strJsonFileContents = file_get_contents("pokemon.json");
		//var_dump($strJsonFileContents); // show contents
		
		$pokemonArray = json_decode($strJsonFileContents, true);
		
		foreach($pokemonArray["injectRpcs"][1][1]["types"] as $typ){
			if (strpos(strtolower($typ["name"]), strtolower($type)) === 0){
				foreach($typ["atk_effectives"] as $eff){
					if($eff[1] != 1){
						echo "<br>" . $type . " vs. " . $eff[0] . " " . $eff[1];
					}
				}
			}
		}
	}
	catch(Exception $ex){
	}
}

/*
* move = string
*/
function displayMoveInfoAsHTML($move, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["moves"] as $moveInfo){
		if (strpos(strtolower($move), strtolower($moveInfo["name"])) === 0){		
			echo "<table>";
				echo "<tbody>";
					echo "<tr>";
						echo '<td class="type"><a href="https://pokemondb.net/type/' . lowerDash($moveInfo["type"]) . '"  target="_blank">' . $moveInfo["type"] . "</td>";
						echo '<td class="category">' . str_replace("Non-Damaging", "No-dmg", $moveInfo["category"]) . "</td>";
						echo '<td class="power text-end">' . $moveInfo["power"] . " DMG</td>";
						echo '<td class="accuracy text-end">' . $moveInfo["accuracy"] . "%</td>";
						echo '<td class="priority text-end">' . $moveInfo["priority"] . " Prio</td>";
						echo '<td class="pp text-end">' . $moveInfo["pp"] . " PP</td>";
					echo "</tr>";
				echo "</tbody>";
			echo "</table>";
		}
	}
}

function returnMoveType($move, $pokemonArray){
	foreach($pokemonArray["injectRpcs"][1][1]["moves"] as $moveInfo){
		if (strpos(strtolower($move), strtolower($moveInfo["name"])) === 0){	
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

function getSmogonJson($pkmn){
	$pkmnLower = lowerDash($pkmn);
	$filename = "json/". $pkmnLower . ".json";
	
	//If the data was already crawled, load it from file
	if(file_exists($filename)){
		$json = file_get_contents($filename);
	}
	//If the data was not crawled, download it from smogon
	else{
		$url = "https://www.smogon.com/dex/ss/pokemon/" . $pkmnLower . "/";
		$content = file_get_contents($url);
		$json = strstr($content, '<script type="text/javascript">');
		$json = substr($json, 0, strpos($json, "</script>"));
		$json = substr($json, strpos($json, "dexSettings = {")+14, strlen($json));
		
		file_put_contents($filename, $json, FILE_APPEND | LOCK_EX);
	}
	
	return $json;
}


/*

$content = file_get_contents('https://www.smogon.com/dex/ss/pokemon/' . $_GET["pokemon"]);
										
$json = strstr($content, '<script type="text/javascript">');
$json = substr($json, 0, strpos($json, "</script>"));



// add your JS into $content
echo htmlspecialchars($json);

*/