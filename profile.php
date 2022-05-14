<?php 

    session_start();

    if(!isset($_SESSION['user'])) {
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
        <h2>Profil</h2>
        <div id="profile">
                    <form action="profile.php">
                        <button type="submit">Mon profil</button>  
                    </form>
                    <form action="disconnect.php">
                        <button type="submit">Déconnexion</button>
                    </form>

    </div>

    <table>
        <p class="profileTxt" id="profileTxt1">Elections que vous avez organisées</p>
        <tr>
            <th>Nom</th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Ouvrir</th>
        </tr>
        
        <?php 

            // Affichage de chaque élection organisée dans le tableau et création d'un bouton permettant d'y accéder
            foreach(getOrganizedElections() as $election) {

                echo "<tr>";
                echo "<td>" . $election["name"] . "</td>";
                echo "<td>" . $election["startDate"] . "</td>";
                echo "<td>" . $election["endDate"] . "</td>";
                echo "<td><form action='electionPage.php' method='get'><input type='hidden' name='idElection' value = '" . $election["idElection"] . "'></input><button type='submit'>Ouvrir</button></form></td>";
                echo "</tr>";

            }

        ?>

    </table>

    <table>
        <p class="profileTxt">Elections auxquelles vous avez participé</p>
        <tr>
            <th>Nom</th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Organisateur</th>
            <th>Ouvrir</th>
        </tr>
        
        <?php 

            // Affichage de chaque élection participée dans le tableau et création d'un bouton permettant d'y accéder
            foreach(getParticipatedElections() as $election) {

                echo "<tr>";
                echo "<td>" . $election["name"] . "</td>";
                echo "<td>" . $election["startDate"] . "</td>";
                echo "<td>" . $election["endDate"] . "</td>";
                echo "<td>" . $election["organizator"] . "</td>";
                echo "<td><form action='electionPage.php' method='get'><input type='hidden' name='idElection' value = '" . $election["idElection"] . "'></input><button type='submit'>Ouvrir</button></form></td>";
                echo "</tr>";

            }

        ?>

    </table>

    <script type="text/javascript" src="logoIndex.js"></script>

</body>
</html>

<?php 

    // Récupération d'une liste des élections organisées par l'utilisateur
    function getOrganizedElections() {

        $datas = array();

        require 'base.php';

        $query = "SELECT name, startDate, endDate, idElection FROM election WHERE idOrganizator = :idUser";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $temp = [
                "name" => $row['name'], 
                "startDate" => $row['startDate'],
                "endDate" => $row['endDate'],
                "idElection" => $row['idElection']];
            array_push($datas, $temp);
        }

        return $datas;

    }

    // Récupération d'une liste des élections auxquelles l'utilisateur a participé
    function getParticipatedElections() {

        require 'base.php';

        $datas = array();

        $query = "SELECT e.name, e.startDate, e.endDate, u.username, e.idElection FROM election e, user u, participate p WHERE u.idUser = e.idOrganizator AND p.idUser = :idUser AND p.idElection = e.idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $temp = [
                "name" => $row['name'], 
                "startDate" => $row['startDate'],
                "endDate" => $row['endDate'],
                "organizator" => $row['username'],
                "idElection" => $row['idElection']];
            array_push($datas, $temp);
        }

        return $datas;

    }

?>