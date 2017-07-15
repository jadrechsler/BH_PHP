var currentChild;

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
	})

	const data = {id: id, presence: presence};	
	QueryDB('change_presence', JSON.stringify(data));
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
	currentChild = id;

	if (isPresent(id)) {
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
	QueryDB('get_report', '{}', function(r) {
		if (!r.success) {
			console.log(r.error);
		} else {
			const childReport = r.data.reports[currentChild];

			if (childReport != undefined) {
				loadReport(childReport);
			}

			show(report);
		}
	});
}

function report_hide() {
	hide(report);
}

function loadReport(report) {
	$('#i-ate ul').text('');

	function loadText(text) {
		const sections = text.split('.');


		// Check if property exists in report
		var exists = true;
		for (var x = 0; x < sections.length; x++) {
			if (x == 0) {
				if (!report.hasOwnProperty(sections[x])) {
					exists = false;
					break;
				}
			} else if (x == 1) {
				const secondExists = '(report.'+sections[0]+'.hasOwnProperty(\''+sections[x]+'\'))';

				if (!eval(secondExists)) {
					exists = false;
					break;
				}
			}
		}

		if (exists) {
			const exec = '(report.'+text+');';

			return eval(exec);
		}

		return '';
	}

	$('#i-was p').text(loadText('feeling.iWas'));

	const napFrom = loadText('nap.from');
	const napTo = loadText('nap.to');
	if (napFrom != '' && napTo != '')
		$('#i-slept p').text(napFrom + ' - ' + napTo);

	const bathroomIWent = loadText('bathroom.iWent');
	const bathroomAt = loadText('bathroom.at');
	if (bathroomIWent != '' && bathroomAt != '')
		$('#i-went p').text(bathroomIWent + ' at ' + bathroomAt);
	

	const breakfast = loadText('meals.breakfast');
	if (breakfast != '')
		$('#i-ate ul').append('<li>Breakfast: ' + breakfast + '</li>');

	const lunch = loadText('meals.lunch');
	if (lunch != '')
		$('#i-ate ul').append('<li>Lunch: ' + lunch + '</li>');

	const snack = loadText('meals.snack');
	if (snack != '')
		$('#i-ate ul').append('<li>Snack: ' + snack + '</li>');

	const iNeedOptions = ['diapers', 'wipes', 'shirt', 'pants', 'underwear'];
	const iNeed = loadText('needs');
	var iNeedText = '';

	iNeedOptions.forEach(function(need, index) {
		if (index < iNeedOptions.length-1) {
			if (iNeed[need]) {
				iNeedText += need + ' &#x2714;, '; // Append check mark
			} else if (!iNeed[need]) {
				iNeedText += need + ', ';
			}
		} else {
			if (iNeed[need]) {
				iNeedText += need + ' yes';
			} else if (!iNeed[need]) {
				iNeedText += need;
			}
		}
	});

	$('#i-need p').html(iNeedText);

	if (!loadText('occurence')) {
		// Hide occurence box
		$('#occurence').hide();
		$('#report .right .top').css('position', 'absolute');
		$('#report .right .middle').css('height', '80%');
	} else {
		// Show occurence box
		$('#occurence').show();
		$('#report .right .top').css('position', 'relative');
		$('#report .right .middle').css('height', '70%');
	}

	$('#highlights p').text(loadText('highlights'));
}

function autoDimDisplay() {
	const startTime = 13; // 13 - 1: PM 24 hour format
	// Dim will last for 1.9999999 hours
	const endTime = 14; // 14 - 2.999999: PM 24 hour format

	setInterval(function() {
		const now = new Date();

		if (now.getHours() >= startTime && now.getHours() <= endTime) {
			$('#brightness').show(); 
		} else {
			$('#brightness').hide();
		}

	}, 2000); // Every 2 seconds
}

autoDimDisplay();

function checkPin(pin, id) {
	const data = {
		id: id,
		pin: pin
	};

	var validation;

	QueryDB('check_pin', JSON.stringify(data), function(r) {
		validation = r.success;
	}, false);

	return validation;
}