<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>TierLection</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="Tierlection.css"/>
</head>
<body>
    <div class="general">
        <img src="data/TierLection.png">
        <div id="connexion">
            <form action="connexion.php">
                <button type="submit">Connexion</button>
            </form>
            <form action="inscription.php">
                <button type="submit">Inscription</button>  
            </form>
        </div>

        <div id="introduction">
            <h2>Bienvenue chez TierLection</h2>
            <p>
                Elections par Comparaisons. le but est de se créer un compte ou l'on pourra voter pour les meilleurs objets, personnages... les résultats seront visibles ainsi que des statistiaues pour connaitre la moyenne d'age des joueurs, savoir qui vote pour quoi... POssibilité également de faire une tiers-liste si les participatns ont un compte Organisateur qui leur permet de crér des sondages . sinon il n'y a que la possibilité de voter.
            </p>
        </div>

        <div id="credits">
            <button type="submit">Crédits</button>
            <button type="submit">FAQ</button>
        </div>
        
    </div>
</body>
</html>