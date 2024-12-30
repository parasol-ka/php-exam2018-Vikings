<?php 
	include "_connexionBD.php";
	$reqPillages=$bd->prepare("SELECT p.id_pillage, p.annee, p.saison, p.lieu, p.id_chef, p.vikings, p.pertes, p.butin, p.icone FROM pillages AS p ORDER BY p.annee DESC, p.saison DESC, p.id_pillage DESC;");
	$reqPillages->execute();

	


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
					
					$saison_names=array('hiver', 'printemps', 'été', 'automne');
					$saison=$saison_names[$pillages['saison']];

					$place=$pillages['lieu'];
					$id_chef=$pillages['id_chef'];
					$vikings_nbr=$pillages['vikings'];
					$losts=$pillages['pertes'];
					$vikings_alive=$vikings_nbr-$losts;
					$gold=$pillages['butin'];
					$icon=$pillages['icone'];

					echo "<div id='pillage_line'>";
					if ($id_chef=NULL){
						echo "<p class='pillages_lines'>";
						$closing='echo "</p>"';
					}else {
						echo"<a href='pillages.php?pillage=$id_pillage' class='pillages_links'>";
						$closing='echo "</a>"';}

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

					$closing;
					echo "</div>";

				}
				
				?>
			</div>
			<div id="pillages_form_container">
			</div>
		</main>
	</body>
</html>