<?php 
	include "_connexionBD.php";
	$reqPillages=$bd->prepare("SELECT p.id_pillage, p.annee, p.saison, p.lieu, p.id_chef, p.vikings, p.pertes, p.butin, p.icone FROM pillages AS p ORDER BY p.annee DESC, p.saison DESC, p.id_pillage DESC;");
	$reqPillages->execute();

	$saison_names=array('hiver', 'printemps', 'été', 'automne');


	if (isset($_POST['year_input']) and isset($_POST['place_input']) and isset($_POST['saison_input'])){
		
		$year_cleaned=(int)$_POST['year_input'];
		if($year_cleaned>0 and $year_cleaned<=date("Y")){
			$year_new_pillage=$_POST['year_input'];
			$error_year=False;
		}else {$error_year=True;}

		$place_cleaned=strip_tags($_POST['place_input']);
		$place_cleaned=trim($place_cleaned);
		if($place_cleaned){
			$place_new_pillage=$place_cleaned;
			$error_place=False;
		}else {$error_place=True;}

		if($_POST['saison_input']>=0 and $_POST['saison_input']<=count($saison_names)){ 
			$saison_new_pillage=$_POST['saison_input'];
			$error_saison=False;
		}else {$error_saison=True;}
		
		if (!$error_year and !$error_place and !$error_saison){
			
			$insertPillage=$bd->prepare("INSERT INTO pillages (id_pillage, annee, saison, lieu, id_chef, icone, drakkars, naufrages, butin, esclaves, vikings, pertes, météo, commentaire) 
										VALUES (NULL, :year_new_pillage, :saison_new_pillage, :place_new_pillage, NULL, :icon, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
			$insertPillage->bindvalue("year_new_pillage", $year_new_pillage);
			$insertPillage->bindvalue("place_new_pillage", $place_new_pillage);
			$insertPillage->bindvalue("saison_new_pillage", $saison_new_pillage);
			$insertPillage->bindvalue("icon", 'casque');
			$insertPillage->execute();
			header("Location:index.php");
		}

	}else {
		$error_year=False;
		$error_place=False;
		$error_saison=False;
	}

	


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Vikings</title>
		<style>
		html, body { padding:0; margin:0; font-family:AR Julian, Helvetica; color:#444; }
		header { background-color: #102341; color:#FFF; padding:1rem; }
		footer { background-color: #333; color:#FFF; padding:1rem; }
		.center { width:600px; max-width:100%; margin:0 auto;}
		h1 { margin:0;}
		</style>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<header>
			<div class="center">
				<h1><img src="icons/amulette.png" alt=""> Les Pillages d'Ingvar</h1>
				<p>Toutes les expéditions menées par Ingvar l'intrépide et son clan depuis l'an 812 (année où Ingvar décapita Arnulf le fourbe et prit le pouvoir).</p>
			</div>
		</header>
		<main>
			<div id="pillages_container">
				<?php
				
				while($pillages=$reqPillages->fetch()){
					$id_pillage=$pillages['id_pillage'];
					$year_pillage=$pillages['annee'];
					
					
					$saison=$saison_names[$pillages['saison']];

					$place=$pillages['lieu'];
					$id_chef=$pillages['id_chef'];
					$vikings_nbr=$pillages['vikings'];
					$losts=$pillages['pertes'];
					$vikings_alive=$vikings_nbr-$losts;
					$gold=$pillages['butin'];
					$icon=$pillages['icone'];

					echo "<div id='pillage_line'>";
					if ($id_chef==NULL){
						echo "<p class='pillages_lines'>";
						$closing='</p>';
					}else {
						echo"<a href='pillages.php?pillage=$id_pillage' class='pillages_links'>";
						$closing='</a>';}

					if (empty($icon)){
						if ($vikings_nbr==$losts){
							echo "<img src='icons/crane.png' alt='' class='pillages_icons'>";
						}elseif (($gold/$vikings_alive)>=50) {
							echo "<img src='icons/butin.png' alt='' class='pillages_icons'>";
						}elseif (($vikings_nbr/3)<=$losts) {
							echo "<img src='icons/bataille.png' alt='' class='pillages_icons'>";
						}elseif ($losts==0) {
							echo "<img src='icons/bouclier.png' alt='' class='pillages_icons'>";
						}else {echo "<img src='icons/epee.png' alt='' class='pillages_icons'>";}
					}else {echo "<img src='icons/$icon.png' alt='' class='pillages_icons'>";}

					echo "$place, $saison $year_pillage";

					echo $closing;
					echo "</div>";
				}
				?>
			</div>
		</main>
		<footer>
			<h2>Enregistrer un nouveau pillage</h2>
			<form action="index.php" id="new_pillage_form" method="POST">
				<label for="place_input" <?php if($error_place){echo "class='error_field'";} ?>>Lieu</label>
				<input type="text" name="place_input" required>
				<label for="year_input" <?php if($error_year){echo "class='error_field'";} ?>>Année</label>
				<input type="number" name="year_input" min="0" required>
				<label for="saison_input" <?php if($error_saison){echo "class='error_field'";} ?>>Saison</label>
				<select name="saison_input" id="saison_input" >
				<?php
					foreach ($saison_names as $key => $value) {
						echo "<option value='$key'>$value</option>";
					}
				?>
				</select>
				<input type="submit" value="Enregistrer">
			</form>
		</footer>
	</body>
</html>