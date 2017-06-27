const overlay = document.getElementById("overlay");

function overlay_show(id, name) {
	document.getElementById("name").innerHTML = name;
	overlay.style.visibility = "visible";
}

function overlay_hide() {
	overlay.style.visibility = "hidden";
}