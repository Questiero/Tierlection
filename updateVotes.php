<?php 

	session_start();

    if(!isset($_SESSION['user']) || !isset($_GET["idElection"])) {
        header("Location: index.php");
    }

    require 'base.php';

	$connection->exec("SET NAMES 'utf8'");

	$query = "SELECT i.name, i.idItem FROM item i, election e WHERE e.idElection = :idElection AND i.idSet = e.idSet";

	$statement = $connection->prepare($query);

    // Bind value and execute query
    $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_STR);

    $statement->execute();

    $items = array();
	foreach($statement as $row) {
		if(isset($_GET[$row["name"]])) {
			increment($row["idItem"], $_GET[$row["name"]]);
		} else {
			header ("Location: index.php");
		}
  		array_push($items, ["name" => $row["name"]]);
	}

    participate();

    header("Location: index.php");

?>

<?php 

	function increment($idItem, $value) {

		require 'base.php';

        // Creation election
        $query = "UPDATE vote SET nbrVotes = nbrVotes + :value WHERE idItem = :idItem AND idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idItem", $idItem, PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->bindValue(":value", $value, PDO::PARAM_INT);

        $statement->execute();

	}

	function participate() {

        require 'base.php';

        $query = "INSERT INTO participate (idUser, idElection) VALUES (:idUser, :idElection)";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);

        $statement->execute();

	}

?>