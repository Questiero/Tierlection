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
    <link rel="stylesheet" href="TierLection.css"/>
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
        <form enctype="multipart/form-data" action="uploadTheme.php" method="post">
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
                        <div class="newItemFile">
                            <label for="item1-file">Image du 1er item</label>
                            <input type="file" id="item1-file" name="item1-file" accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>
                    <div id="item2">
                        <div class="newItemName">
                            <label for="item2-name">Nom du 2eme item</label>
                            <input type="text" id="item2-name" name="item2-name" size="20">
                        </div>
                        <div>
                            <label for="item2-description">Description du 2eme item</label>
                            <input type="text" id="item2-description" name="item2-description" size="20">
                        </div>
                        <div class="newItemFile">
                            <label for="item2-file">Image du 2eme item</label>
                            <input type="file" id="item2-file" name="item2-file" accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>
                    <div id="item3">
                        <div class="newItemName">
                            <label for="item3-name">Nom du 3eme item</label>
                            <input type="text" id="item3-name" name="item3-name" size="20">
                        </div>
                        <div>
                            <label for="item3-description">Description du 3eme item</label>
                            <input type="text" id="item3-description" name="item3-description" size="20">
                        </div>
                        <div class="newItemFile">
                            <label for="item3-file">Image du 3eme item</label>
                            <input type="file" id="item3-file" name="item3-file" accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>
                </div>
                <div id="newItemButton">Cliquez ici pour ajouter un item</div>
            </div>

            <div id="create">
                <button type="submit">Créer</button>
                <input type="hidden" name="nbrItems" id="nbrItems" value="3"></label>
            </div>          
        </form>
    </div>

    <div class="messages">

        <?php

            if(isset($_GET["done"])) {

                if($_GET["done"] == 0) {
                    echo "<p class'errors'>Une erreur est survenue</p>";
                } else {
                    echo "<p class'errors'>Thème créé avec succès</p>";
                }
                
            }

        ?>

    </div>

    <script type="text/javascript" src="logoIndex.js"></script>
    <script type="text/javascript">
        
        var nbrItems = 3;

        // A chaque clic pour rajouter un item, création de nouvelles div dans le form et incrémentation du nombre d'items
        document.getElementById("newItemButton").addEventListener("click", function() {

            nbrItems++;

            document.getElementById("nbrItems").value = "" + nbrItems;

            newItems = document.getElementById("newItems");
            newItems.innerHTML += '<div id="item' + nbrItems + '">\
                        <div class="newItemName">\
                            <label for="item' + nbrItems + '-name">Nom du ' + nbrItems + 'eme item</label>\
                            <input type="text" id="item' + nbrItems + '-name" name="item' + nbrItems + '-name" size="20">\
                        </div>\
                        <div>\
                            <label for="item' + nbrItems + '-description">Description du ' + nbrItems + 'eme item</label>\
                            <input type="text" id="item' + nbrItems + '-description" name="item' + nbrItems + '-description" size="20">\
                        </div>\
                        <div class="newItemFile">\
                            <label for="item' + nbrItems + '-file">Image du ' + nbrItems + 'eme item</label>\
                            <input type="file" id="item' + nbrItems + '-file" name="item' + nbrItems + '-file" accept="image/png, image/jpeg, image/jpg">\
                        </div>\
                    </div>';


        });

    </script>

</body>
</html>

<?php 

    

?>