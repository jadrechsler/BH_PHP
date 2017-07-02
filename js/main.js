function isPresent(id) {
	var present = false;
	children.forEach(function(child) {
		if (child.id == id.toString() && child.present == "1")
			present = true;
	})
	return present;
}

function changePresence(id, presence) {
	children.forEach(function(child) {
		if (child.id == id.toString()){
			child.present = presence;
		}
		console.log(child);
	})

	const data = {id: id, presence: presence};	
	QueryDB('change_presence', JSON.stringify(data));
	console.log(JSON.stringify(data));
}

const overlay = document.getElementById("overlay");
const report = document.getElementById("report");

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

function check_in(id) {
	overlay_hide();
	changePresence(id, 1);
}

function check_out(id) {
	overlay_hide();
	changePresence(id, 0);
}

function overlay_show(id, name) {
	// First name displayed
	document.getElementById("name").innerHTML = name.split(' ')[0];

	if (isPresent(id)) {
		console.log(name);
		check.querySelector("p").innerHTML = "Check out";
		check.style.backgroundColor = "#F3625C";
		check.setAttribute("onclick", "check_out('"+id+"')");
	} else {
		check.querySelector("p").innerHTML = "Check in";
		check.style.backgroundColor = "#00874A";
		check.setAttribute("onclick", "check_in('"+id+"')");
	}

	show(overlay);
}

function overlay_hide() {
	document.getElementById("name").innerHTML = "";
	hide(overlay);
}

function report_show() {
	show(report);
}

function report_hide() {
	hide(report);
}