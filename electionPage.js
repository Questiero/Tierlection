var itemTuple = {};
var votes = {};
var nbrVote = 0;

function loadJSON(idElection) {

	var req = new XMLHttpRequest();

	req.onreadystatechange = function() {

		if(req.readyState == 4 && req.status == 200) {
			var response = JSON.parse(req.responseText);
			init(response);
		}

	}

	req.open("GET", "getItems.php?idElection=" + idElection);
	req.send();

}

function init(items) {

	// Init itemTuple
	itemTuple = randomTuples(items);

	// Init votes
    items.forEach(item => {
        votes[item.name] = 0;
    });

    // Init eventListeners
    document.getElementById("card-left").addEventListener("click", function() {
    	var name = document.getElementById("card-name-left").innerHTML;
    	votes[name]++;
    	nbrVote++;
    	if(nbrVote >= itemTuple.length) {
    		updateVotes();
    	} else {
    		generateCards(itemTuple[nbrVote]);
    	}});
    document.getElementById("card-right").addEventListener("click", function() {
    	var name = document.getElementById("card-name-right").innerHTML;
    	votes[name]++;
    	nbrVote++;
    	if(nbrVote >= itemTuple.length) {
    		updateVotes();
    	} else {
    		generateCards(itemTuple[nbrVote]);
    	}});

	generateCards(itemTuple[0]);

}

function randomTuples(array) {

	var tuples = new Array();

	for(var i = 0; i < array.length; i++) {
		for(var j = i+1; j < array.length; j++) {
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

	var nameLeft = document.createElement("div");
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
	nameRight.innerHTML = tuple[1].name;
	cardRight.append(nameRight);

	cardRight.innerHTML += '<img id="img-right" src="' + tuple[1].icon + '">';

	var descriptionRight = document.createElement("p");
	descriptionRight.className = "card-description";
	descriptionRight.id = "card-description-right";
	descriptionRight.innerHTML = tuple[1].description;
	cardRight.append(descriptionRight);

}

function updateVotes() {

	var path = "updateVotes.php?idElection=" + idElection;

	Object.keys(votes).forEach(name => {
		var value = "&" + name + "=" + votes[name];
		path += value;
	})

	window.location.href = path;
}