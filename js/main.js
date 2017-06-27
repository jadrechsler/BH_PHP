const overlay = document.getElementById("overlay");
const check_in_out = document.getElementById("overlay-check-in-out")

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

function check_in_out_show() {
	show(check_in_out);
}

function check_in_out_hide() {
	hide(check_in_out);
}

function overlay_show(id, name) {
	document.getElementById("name").innerHTML = name;
	show(overlay);
}

function overlay_hide() {
	document.getElementById("name").innerHTML = "";
	hide(overlay, check_in_out);
}