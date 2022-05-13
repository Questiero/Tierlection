<?php 

    session_start();

    if(!userParticipated() && (!isset($_SESSION['user']) || !isset($_GET["idElection"]) || !userParticipated())) {
        header("Location: index.php");
    }

    $rankedItems = ["S" => array(),
        "A" => array(),
        "B" => array(),
        "C" => array(),
        "D" => array(),
        "E" => array()];

    $items = getItems();

    usort($items, "compareItems");

    $size = count($items);
    for($i = 0; $i < $size; $i++) {
        if($i <= ceil($size*0.05)) {
            array_push($rankedItems["S"], $items[$i]);
        } else if($i <= ceil($size*0.15)) {
            array_push($rankedItems["A"], $items[$i]);
        } else if($i <= ceil($size*0.30)) {
            array_push($rankedItems["B"], $items[$i]);
        } else if($i <= ceil($size*0.70)) {
            array_push($rankedItems["C"], $items[$i]);
        } else if($i <= ceil($size*0.85)) {
            array_push($rankedItems["D"], $items[$i]);
        } else if($i <= ceil($size*0.95)) {
            array_push($rankedItems["E"], $items[$i]);
        }
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
        <h2><?php echo getNameElection() . " (" . numberParticipants() . " participants)"; ?></h2>
        <img id="tierlection-logo" src="data/TierLection-Logo.png">
    </div>
    
    <div id="profile">
        <form action="profile.php">
            <button type="submit">Mon profil</button>  
        </form>
        <form action="disconnect.php">
            <button type="submit">Déconnexion</button>
        </form>
    </div>

    <table>
        
        <tr>
            <th>S</th>
            <td class="tierlist-td">
                
                <?php 

                    foreach($rankedItems["S"] as $item) {
                        echo "<img class=\"tierlist-img\" src=\"" . $item["icon"] . "\"></img>";
                    }

                ?>

            </td>
        </tr>
        <tr>
            <th>A</th>
            <td class="tierlist-td">
                
                <?php 

                    foreach($rankedItems["A"] as $item) {
                        echo "<img class=\"tierlist-img\" src=\"" . $item["icon"] . "\"></img>";
                    }

                ?>

            </td>
        </tr>
        <tr>
            <th>B</th>
            <td class="tierlist-td">
                
                <?php 

                    foreach($rankedItems["B"] as $item) {
                        echo "<img class=\"tierlist-img\" src=\"" . $item["icon"] . "\"></img>";
                    }

                ?>

            </td>
        </tr>
        <tr>
            <th>C</th>
            <td class="tierlist-td">
                
                <?php 

                    foreach($rankedItems["C"] as $item) {
                        echo "<img class=\"tierlist-img\" src=\"" . $item["icon"] . "\"></img>";
                    }

                ?>

            </td>
        </tr>
        <tr>
            <th>D</th>
            <td class="tierlist-td">
                
                <?php 

                    foreach($rankedItems["D"] as $item) {
                        echo "<img class=\"tierlist-img\" src=\"" . $item["icon"] . "\"></img>";
                    }

                ?>

            </td>
        </tr>
        <tr>
            <th>E</th>
            <td class="tierlist-td">
                
                <?php 

                    foreach($rankedItems["E"] as $item) {
                        echo "<img class=\"tierlist-img\" src=\"" . $item["icon"] . "\"></img>";
                    }

                ?>

            </td class="tierlist-td">
        </tr>

    </table>

    <script type="text/javascript" src="logoIndex.js"></script>

</body>
</html>

<?php 

    function getNameElection() {

        require 'base.php';

        $query = "SELECT name FROM election WHERE idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_STR);
        $statement->execute();

        foreach($statement as $row) {
            return $row["name"];
        }

    }

    function userParticipated() {

        require 'base.php';

        // Get election ID
        $query = "SELECT COUNT(*) FROM participate WHERE idUser = :idUser AND idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            return((bool) $row['COUNT(*)']);
        }

    }

    function userOrganized() {

        require 'base.php';

        // Get election ID
        $query = "SELECT COUNT(*) FROM election WHERE idOrganizator = :idUser AND idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            return((bool) $row['COUNT(*)']);
        }

    }

    function numberParticipants() {

        require 'base.php';

        // Get election ID
        $query = "SELECT COUNT(*) FROM participate WHERE idUser = :idUser AND idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            return($row['COUNT(*)']);
        }

    }

    function getItems() {

        require 'base.php';

        $connection->exec("SET NAMES 'utf8'");

        $query = "SELECT i.name, i.description, i.icon, v.nbrVotes FROM item i, election e, vote v WHERE e.idElection = :idElection AND i.idSet = e.idSet AND e.idElection = v.idElection AND i.idItem = v.idItem";

        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_STR);

        $statement->execute();

        $items = array();
        foreach($statement as $row) {
            $item = ["name" => $row["name"],
            "description" => $row["description"],
            "icon" => $row["icon"],
            "nbrVotes" => $row["nbrVotes"]];
            array_push($items, $item);
        }

        return $items;

    }

    function compareItems($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a["nbrVotes"] < $b["nbrVotes"]) ? -1 : 1;
    }

?>