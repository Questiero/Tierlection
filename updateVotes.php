<?php 

	session_start();

    if(!isset($_SESSION['user']) || !isset($_GET["idElection"])) {
        header("Location: index.php");
    } else if (userParticipated()) {
        header("Location: resultats.php?idElection=" . $_GET["idElection"]);
    }

    require 'base.php';

	$connection->exec("SET NAMES 'utf8'");

	$query = "SELECT i.name, i.idItem FROM item i, election e WHERE e.idElection = :idElection AND i.idSet = e.idSet";

	$statement = $connection->prepare($query);

    // Bind value and execute query
    $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_STR);

    $statement->execute();

    // Pour chaque item, incrémentation du nombre de votes
	foreach($statement as $row) {
		if(isset($_GET[str_replace(" ", "", $row["name"])])) {
			increment($row["idItem"], $_GET[str_replace(" ", "", $row["name"])]);
		} else {
			header ("Location: index.php");
		}
	}

    participate();

    header("Location: index.php");

?>

<?php 

    // Incrémente les votes pour l'item $idItem de $value
	function increment($idItem, $value) {

		require 'base.php';

        $query = "UPDATE vote SET nbrVotes = nbrVotes + :value WHERE idItem = :idItem AND idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idItem", $idItem, PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->bindValue(":value", $value, PDO::PARAM_INT);

        $statement->execute();

	}

    // Ajoute l'utilisateur dans la liste de ceux ayant participé à cette élection
	function participate() {

        require 'base.php';

        $query = "INSERT INTO participate (idUser, idElection) VALUES (:idUser, :idElection)";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);

        $statement->execute();

	}

    // Vérifie si l'utilisateur a participé à l'éleciton
    function userParticipated() {

        require 'base.php';

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

?>