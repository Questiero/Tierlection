<?php 

	header('content-type: application/json; charset=utf-8');
	header("Access-Control-Allow-Origin: *");

	if (isset($_GET["idElection"])) {

		require 'base.php';

		$connection->exec("SET NAMES 'utf8'");

		$query = "SELECT i.name, i.description, i.icon FROM item i, election e WHERE e.idElection = :idElection AND i.idSet = e.idSet";

		$statement = $connection->prepare($query);

	    // Bind value and execute query
	    $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_STR);

	    $statement->execute();

	    $items = array();
		foreach($statement as $row) {
	  		$item = ["name" => $row["name"],
	  		"description" => $row["description"],
	  		"icon" => $row["icon"]];
	  		array_push($items, $item);
		}

		// Convert to JSON  
		echo json_encode($items, JSON_UNESCAPED_SLASHES);

	} else {

	    // Redirection vers la page d'accueil
	    header("Location: index.php")

	}

?>