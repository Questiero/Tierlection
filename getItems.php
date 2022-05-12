<?php 

	header('content-type: application/json; charset=utf-8');
	header("Access-Control-Allow-Origin: *");

	require 'base.php';

	$connection->exec("SET NAMES 'utf8'");

	$query = "SELECT DISTINCT i.name, i.description, i.icon FROM item i, belongs b, election e WHERE e.idElection = :idElection AND b.idSet = e.idSet AND i.idItem = b.idItem";

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

?>