function isPresent(name) {
	var present = false;
	children.forEach(function(child) {
		if (child.name == name && child.present)
			present = true;
	})
	return present;
}

function changePresence(name, presence) {
	children.forEach(function(child) {
		if (child.name == name){
			child.present = presence;
		}
	})
}

const overlay = document.getElementById("overlay");

// Passed in elements made visible
function show(elements) {
	Array.prototype.slice.call(arguments).forEach(function(element, index) {
		element.style.visibility = "visible";
	});
}

// Passed in elements made hidden
function hide(elements) {
	Array.prototype.slice.call(arguments).forEach(function(element, index) {
		element.style.visibility = "hidden";
	});
}



const check = document.getElementById("check-in-out");

function check_in(name) {
	overlay_hide();
	changePresence(name, 1);
}

function check_out(name) {
	overlay_hide();
	changePresence(name, 0);
}

function overlay_show(id, name) {
	// First name displayed
	document.getElementById("name").innerHTML = name.split(' ')[0];

	if (isPresent(name)) {
		console.log(name);
		check.querySelector("p").innerHTML = "Check out";
		check.style.backgroundColor = "#F3625C";
		check.setAttribute("onclick", "check_out('"+name+"')");
	} else {
		check.querySelector("p").innerHTML = "Check in";
		check.style.backgroundColor = "#00874A";
		check.setAttribute("onclick", "check_in('"+name+"')");
	}

	show(overlay);
}

function overlay_hide() {
	document.getElementById("name").innerHTML = "";
	hide(overlay);
}