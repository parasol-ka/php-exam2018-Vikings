<?php 
    include "_connexionBD.php";
    $reqPillage=$bd->prepare("SELECT p.id_pillage, p.annee, p.saison, p.lieu, p.drakkars, p.vikings, p.pertes, p.butin, p.esclaves, p.commentaire, p.naufrages, c.id_chef, c.nom, c.titre, c.icone FROM pillages AS p JOIN chefs AS c ON p.id_chef=c.id_chef WHERE p.id_pillage=:id_pillage;");
    $reqPillage->bindvalue("id_pillage", (int)$_GET['pillage']);
    $reqPillage->execute();
    $pillage=$reqPillage->fetch();
    
    if (!$pillage){
        header("Location:index.php");
        exit();
    }else {
        $id_pillage=$_GET['pillage'];
        $year_pillage=$pillage['annee'];
					
        $saison_names=array('hiver', 'printemps', 'été', 'automne');
        $saison=$saison_names[$pillage['saison']];

        $place=$pillage['lieu'];
        $id_chef=$pillage['id_chef'];
        $vikings_nbr=$pillage['vikings'];
        $losts=$pillage['pertes'];
        $gold=$pillage['butin'];
		$icon=$pillage['icone'];
        $slaves=$pillage['esclaves'];
        $drakkars=$pillage['drakkars'];
        $commentaire=$pillage['commentaire'];
        $naufrages=$pillage['naufrages'];
        $chef_titre=$pillage['titre'];
        $chef_name=$pillage['nom'];
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pillage</title>
</head>
<body>
    <main>
        <div id="pillage_info_container">
            <div id="chef_info">
                <?php
                    echo "<p><img src='icons/$icon.png' alt=''>$place, $saison $year_pillage</p>";
                    echo "<p>Pillage mené par $chef_name $chef_titre</p>";
                ?>
            </div>
            <a href="index.php" id='back_links'><-- Retour à la page d'accueil</a>
            
            <div id="army_container">
                <div id="drakkars_container">
                    <?php
                        for ($i=0; $i < $drakkars; $i++) { 
                            echo "<img src='icons/drakkar.png'>";}

                        if ($naufrages!=0){echo "<p>- $naufrages</p>";}
                        
                        echo "<div id='army_statistics'>
                        <figure><img src='icons/viking.png' alt=''>
                        <figcaption>$vikings_nbr</figcaption></figure>
                        <figure><img src='icons/crane.png' alt=''>
                        <figcaption>$losts</figcaption></figure>
                        <figure><img src='icons/butin.png' alt=''>
                        <figcaption>$gold</figcaption></figure>
                        <figure><img src='icons/esclave.png' alt=''>
                        <figcaption>$slaves</figcaption></figure></div>";
                        echo "<p>$commentaire</p>"
                    ?>
                </div>
            </div>
            
        </div>

    </main>
</body>
</html>