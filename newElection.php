<?php 
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>TierLection</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="Tierlection.css"/>
</head>
<body>

    <div class="panel">
        <form action="newElection.php" method="post">
            <div id="valeurs">
                <div>
                    <label for="name">Nom de l'élection</label>
                    <input type="text" id="name" name="name" size="20"/>
                </div>
                <div>
                    <label for="startDate">Date de début</label>
                    <input type="date" id="startDate" name="startDate"/>
                </div>
                <div>
                    <label for="endDate">Date de fin</label>
                    <input type="date" id="endDate" name="endDate"/>
                </div>
                <div>
                    <label for ="themes">Themes</label>
                    <input id="themes" list="themes">
                    <datalist id="themes">
                        <option value="Oui">
                        <option value="ça va ?">
                        <option value="Pas moi">
                        <option value="Envie de creuver">
                    </datalist>
                </div>
                    
            </div>

            <div id="create">
                <input type="submit" value="Créer"/>
            </div>          
        </form>
    </div>

</body>
</html>
