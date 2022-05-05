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
    
    <?php if(!isset($_SESSION['username'])) {

        echo "
        <div class=\"general\">
            <img src=\"data/TierLection.png\">
            <div id=\"connexion\">
                <form action=\"connexion.php\">
                    <button type=\"submit\">Connexion</button>
                </form>
                <form action=\"inscription.php\">
                    <button type=\"submit\">Inscription</button>  
                </form>
            </div>

            <div id=\"introduction\">
                <h1>BIENVENUE CHEZ TIERLECTION</h1>
                <p>
                    Elections par Comparaisons. le but est de se créer un compte ou l'on pourra voter pour les meilleurs objets, personnages... les résultats seront visibles ainsi que des statistiaues pour connaitre la moyenne d'age des joueurs, savoir qui vote pour quoi... POssibilité également de faire une tiers-liste si les participatns ont un compte Organisateur qui leur permet de crér des sondages . sinon il n'y a que la possibilité de voter.
                </p>
            </div>

            <div id=\"credits\">
                <button type=\"submit\">Crédits</button>
                <button type=\"submit\">FAQ</button>
            </div>
        </div>";

    } else {

        // Page d'accueil alternative
        echo "
        <div class=\"general\">
            <img src=\"data/TierLection.png\">
            <div id=\"profile\">
                <form action=\"profile.php\">
                    <button type=\"submit\">Mon profil</button>  
                </form>
            </div>

            <div id=\"introduction\">
                <h1>BIENVENUE CHEZ TIERLECTION</h1>
                <p>
                    Elections par Comparaisons. le but est de se créer un compte ou l'on pourra voter pour les meilleurs objets, personnages... les résultats seront visibles ainsi que des statistiaues pour connaitre la moyenne d'age des joueurs, savoir qui vote pour quoi... POssibilité également de faire une tiers-liste si les participatns ont un compte Organisateur qui leur permet de crér des sondages . sinon il n'y a que la possibilité de voter.
                </p>
            </div>

            <form action=\"electionList.php\">
                <button type=\"submit\">Liste des élections</button>  
            </form>";
            
        echo "<form action=\"newElection.php\">
                <button type=\"submit\">Créer une élection</button>  
            </form>";

        echo "</div>";

    }

    ?>

</body>
</html>
