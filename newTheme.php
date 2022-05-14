<?php 

    session_start();

    require 'base.php';

    if(!isset($_SESSION['user']) && !$_SESSION['user']["canOrganize"]) {
        header("Location: index.php");
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>TierLection</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="Tierlection.css"/>
</head>
<body>

    <div id="titre">
        <img id="tierlection-logo" src="data/TierLection-Logo.png">
        <h2>Nouveau vote</h2>
        <div id="profile">
                    <form action="profile.php">
                        <button type="submit">Mon profil</button>  
                    </form>
                    <form action="disconnect.php">
                        <button type="submit">Déconnexion</button>
                    </form>

    </div>

    <div class="panel" id="panelNewTheme">
        <form action="uploadTheme.php" method="post">
            <div id="valeurs">
                <div>
                    <label for="name">Nom du thème</label>
                    <input type="text" id="name" name="name" size="20"/>
                </div>
                <div id="newItems">
                    <div id="item1">
                        <div class="newItemName">
                            <label for="item1-name">Nom du 1er item</label>
                            <input type="text" id="item1-name" name="item1-name" size="20">
                        </div>
                        <div>
                            <label for="item1-description">Description du 1er item</label>
                            <input type="text" id="item1-description" name="item1-description" size="20">
                        </div>
                        <div class="newItemDescription">
                            <label for="item1-file">Image du 1er item</label>
                            <input type="file" id="item1-file" name="item1-file" size="20">
                        </div>
                    </div>
                </div>
                <div id="newItemButton">Cliquez ici pour ajouter un item</div>
            </div>

            <div id="create">
                <button type="submit">Créer</button>
            </div>          
        </form>
    </div>

    <div class="messages">

        <?php

            if(isset($_POST["name"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["theme"])) {

                $todayYear = intval(date('y'));
                $todayMonth = intval(date('m'));
                $todayDay = intval(date('d'));

                $startYear = intval(substr($_POST["startDate"], 2, -6));
                $startMonth = intval(substr($_POST["startDate"], 5, -3));
                $startDay = intval(substr($_POST["startDate"], 8));

                if($startYear < $todayYear || ($startYear >= $todayYear && ($startMonth < $todayMonth || ($startMonth >= $todayMonth && $startDay < $todayDay)))) {
                    echo "<p class='errors'> Date de début incorrecte. </p>";
                } else {

                    $endYear = intval(substr($_POST["endDate"], 2, -6));
                    $endMonth = intval(substr($_POST["endDate"], 5, -3));
                    $endDay = intval(substr($_POST["endDate"], 8));

                    if(($endYear < $todayYear || ($endYear >= $todayYear && ($endMonth < $todayMonth || ($endMonth >= $todayMonth && $endDay < $todayDay)))) || $endYear > $startYear || ($endYear <= $startYear && ($endMonth > $startMonth || ($endMonth <= $startMonth && $endMonth > $startDay)))) {
                        echo "<p class='errors'> Date de fin incorrecte. </p>";
                    } else {

                        $idElection = createElection($_POST["name"], $_POST["startDate"], $_POST["endDate"], $_SESSION["user"]["idUser"], $_POST["theme"]);

                        foreach(getIdItems($idElection) as $item) {
                            createVotes($idElection, $item["idItem"]);
                        }

                        header("Location: electionPage.php?idElection=".$idElection);

                    }

                }
                
            }

        ?>

    </div>

    <script type="text/javascript" src="logoIndex.js"></script>
    <script type="text/javascript">
        
        var nbrItems = 1;

        document.getElementById("newItemButton").addEventListener("click", function() {

            nbrItems++;

            newItems = document.getElementById("newItems");
            newItems.innerHTML += '<div id="item' + nbrItems + '">\
                        <div class="newItemName">\
                            <label for="item' + nbrItems + '-name">Nom du ' + nbrItems + 'er item</label>\
                            <input type="text" id="item' + nbrItems + '-name" name="item' + nbrItems + '-name" size="20">\
                        </div>\
                        <div>\
                            <label for="item' + nbrItems + '-description">Description du ' + nbrItems + 'er item</label>\
                            <input type="text" id="item' + nbrItems + '-description" name="item' + nbrItems + '-description" size="20">\
                        </div>\
                        <div class="newItemDescription">\
                            <label for="item' + nbrItems + '-file">Image du ' + nbrItems + 'er item</label>\
                            <input type="file" id="item' + nbrItems + '-file" name="item' + nbrItems + '-file" size="20">\
                        </div>\
                    </div>';


        });


    </script>

</body>
</html>

<?php 

    

?>