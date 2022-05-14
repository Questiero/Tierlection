<?php 

	if(isset($_POST["name"]) && isset($_POST["nbrItems"])) {

		// Vérification que toutes les valeurs soient bien set
		for($i = 1; $i <= $_POST["nbrItems"]; $i++) {

			if(!isset($_POST["item".$i."-name"]) || !isset($_POST["item".$i."-description"]) || !isset($_FILES["item".$i."-file"])) {
				header("Location: resultats.php?done=0");
			}

		}

		// Upload des images et des items/sets dans la base de données
		$idSet = getIdSet();
		$target_dir = "./data/sets/" . $idSet . "/";
		mkdir($target_dir, 0700, true);

		createSet($_POST["name"]);

		for($i = 1; $i <= $_POST["nbrItems"]; $i++) {

			if(move_uploaded_file($_FILES["item".$i."-file"]["tmp_name"], $target_dir . basename($_FILES["item".$i."-file"]["name"]))) {
				createItem($_POST["item".$i."-name"], $_POST["item".$i."-description"], $target_dir . basename($_FILES["item".$i."-file"]["name"]), $idSet);
			} else {
				header("Location: resultats.php?done=0");
			}

		}

		header("Location: resultats.php?done=1");

	} else {
		header("Location: resultats.php?done=0");
	}

?>

<?php 

	function getIdSet() {

		require("base.php");

		// Get set ID
        $query = "SELECT COUNT(*) FROM itemSet";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $idElection = $row['COUNT(*)'];
        }

        return $idElection + 1;

	}

	function createSet($name) {

		require 'base.php';

        // Creation election
        $query = "INSERT INTO itemSet (name) VALUES (:name)";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":name", $name, PDO::PARAM_STR);

        $statement->execute();

	}

	function createItem($name, $description, $icon, $idSet) {

		require 'base.php';

        // Creation election
        $query = "INSERT INTO item (name, description, icon, idSet) VALUES (:name, :description, :icon, :idSet)";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":name", $name, PDO::PARAM_STR);
        $statement->bindValue(":description", $description, PDO::PARAM_STR);
        $statement->bindValue(":icon", $icon, PDO::PARAM_STR);
        $statement->bindValue("idSet", $idSet, PDO::PARAM_INT);

        $statement->execute();

	}

?>