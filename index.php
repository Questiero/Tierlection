<?php 
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>TierLection</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="TierLection.css"/>
</head>
<body>
    
    <?php if(!isset($_SESSION['user'])) {

        echo "
        <div class=\"general\">
            <div id=\"titre\">
                 <img id=\"tierlection-logo\" src=\"data/TierLection-Logo.png\">
                 <h1>BIENVENUE CHEZ TIERLECTION</h1>
                    <div id=\"connexion\">
                        <form action=\"connexion.php\">
                             <button type=\"submit\">Connexion</button>
                         </form>
                         <form action=\"inscription.php\">
                             <button type=\"submit\">Inscription</button>  
                         </form>
                    </div>
            </div>

            <div id=\"introduction\">
                
                <p>
                    Tierlection est une application révolutionnaire qui vous permettra de réaliser une élection par comparaisons qui s'affichera sous forme de Tier List ! <br>
                    En effet, lors d'un debat entre amis, sur des sujets divers et variés, il est souvent difficile de se mettre d'accord et de partager des avis communs <br>
                    Et c'est là que nous entrons en scène ! Réaliser dès maintenant une élections par comparaison où chaque utilisateurs contribura à créer une Tier List unique et incontestable ! <br>
                    Ce site a été créé par Jules, Chloé, Alexandre et Mathilde, quatre étudiants en L2 Mathématiques Informatique Appliquées aux Sciences Humaines et Sociales parcours Sciences Cognitives dans le cadre d'un projet Web de l'année 2021 - 2022.
                </p>
            </div>
        </div>";

    } else {

        // Page d'accueil alternative
        echo "
        <div class=\"general\">
            <div id=\"titre\">
                <img id=\"tierlection-logo\" src=\"data/TierLection-Logo.png\">
                <h1>BIENVENUE CHEZ TIERLECTION</h1>
                <div id=\"profile\">
                    <form action=\"profile.php\">
                        <button type=\"submit\">Mon profil</button>  
                    </form>
                    <form action=\"disconnect.php\">
                        <button type=\"submit\">Déconnexion</button>
                    </form>

                </div>

            <div id=\"introduction\">
                <p>
                    Tierlection est une application révolutionnaire qui vous permettra de réaliser une élection par comparaisons qui s'affichera sous forme de Tier List ! <br>
                    En effet, lors d'un debat entre amis, sur des sujets divers et variés, il est souvent difficile de se mettre d'accord et de partager des avis communs <br>
                    Et c'est là que nous entrons en scène ! Réaliser dès maintenant une élections par comparaison où chaque utilisateurs contribura à créer une Tier List unique et incontestable ! <br>
                    Ce site a été créé par Jules, Chloé, Alexandre et Mathilde, quatre étudiants en L2 Mathématiques Informatique Appliquées aux Sciences Humaines et Sociales parcours Sciences Cognitives dans le cadre d'un projet Web de l'année 2021 - 2022.
                </p>
            </div>

            
            <form action=\"electionList.php\" >
                 <button type=\"submit\" id=\"elec_list\">Liste des élections</button>  
            </form>";
            
            echo "<form action=\"newElection.php\">
                   <button type=\"submit\" id=\"elec_new\">Créer une élection</button>  
                  </form>";

             echo "</div>";
            

    }

    ?>

    <script type="text/javascript" src="logoIndex.js"></script>

</body>
</html>
