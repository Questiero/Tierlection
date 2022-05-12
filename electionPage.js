var votes = {};
var vote = 0;

function loadJSON(idElection) {

	var req = new XMLHttpRequest();

	req.onreadystatechange = function() {

		if(req.readyState == 4 && req.status == 200) {
			var response = JSON.parse(req.responseText);
			process(response);
		}

	}

	req.open("GET", "getItems.php?idElection=" + idElection);
	req.send();

}

function process(items) {

    items.forEach(item => {
        votes[item.name] = 0;
    });

	var itemTuple = randomTuples(items);

	generateCards(itemTuple[vote]);

	vote++;

}

function randomTuples(array) {

	var tuples = new Array();

	for(var i = 0; i < array.length - 1; i++) {
		for(var j = i+1; j < array.length - 1; j++) {
			tuple = [array[i], array[j]];
			shuffle(tuple);
			tuples.push(tuple);
		}
	}

	shuffle(tuples);

	return tuples;

}

function shuffle(array) {

	for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }

}

function generateCards(tuple) {

	// update left card
	var cardLeft = document.getElementById("card-left");
	cardLeft.innerHTML = "";

	var nameLeft = document.createElement("p");
	nameLeft.className = "card-name";
	nameLeft.id = "card-name-left";
	nameLeft.innerHTML = tuple[0].name;
	cardLeft.append(nameLeft);

	cardLeft.innerHTML += '<img id="img-left" src="' + tuple[0].icon + '">';

	var descriptionLeft = document.createElement("p");
	descriptionLeft.className = "card-description";
	descriptionLeft.id = "card-description-left";
	descriptionLeft.innerHTML = tuple[0].description;
	cardLeft.append(descriptionLeft);

	// update right card
	var cardRight = document.getElementById("card-right")
	cardRight.innerHTML = "";

	var nameRight = document.createElement("p");
	nameRight.className = "card-name";
	nameRight.id = "card-name-right";
	nameRight.innerHTML = tuple[0].name;
	cardRight.append(nameRight);

	cardRight.innerHTML += '<img id="img-right" src="' + tuple[1].icon + '">';

	var descriptionRight = document.createElement("p");
	descriptionRight.className = "card-description";
	descriptionRight.id = "card-description-right";
	descriptionRight.innerHTML = tuple[1].description;
	cardRight.append(descriptionRight);

}