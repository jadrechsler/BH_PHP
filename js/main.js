var currentChild;
var currentReport;
var currentName;

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
	currentName = name;

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
	const $emailBtn = $('#email button');

	$emailBtn.removeClass('sent');
	$emailBtn.removeClass('failed');
	$emailBtn.removeClass('disabled');
	$emailBtn.text('EMAIL');

	QueryDB('get_report', '{}', function(r) {
		if (!r.success) {
			console.log(r.error);
		} else {
			const childReport = r.data.reports[currentChild];

			currentReport = childReport;

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

// Used for reports
function loadText(text) {
	const sections = text.split('.');


	// Check if property exists in report
	var exists = true;
	for (var x = 0; x < sections.length; x++) {
		if (x == 0) {
			if (!currentReport.hasOwnProperty(sections[x])) {
				exists = false;
				break;
			}
		} else if (x == 1) {
			const secondExists = '(currentReport.'+sections[0]+'.hasOwnProperty(\''+sections[x]+'\'))';

			if (!eval(secondExists)) {
				exists = false;
				break;
			}
		}
	}

	if (exists) {
		const exec = '(currentReport.'+text+');';

		return eval(exec);
	}

	return '';
}

function loadReport(report) {
	$('#i-ate ul').text('');

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

function emailReport() {
	console.log("emailing");

	$('#email button').addClass('disabled');

	var html = '\
		<html>\
		<head>\
		<meta charset="utf-8">\
		</head>\
		<body>\
		<h1>Report</h1>\
		<h3>'+currentName+'</h3>\
		<ul style="list-style-type: none">\
	';

	$('#i-was p').text(loadText('feeling.iWas'));

	const napFrom = loadText('nap.from');
	const napTo = loadText('nap.to');
	if (napFrom != '' && napTo != '')
		html += '<li>I slept: ' + napFrom + ' - ' + napTo + '</li>';

	const bathroomIWent = loadText('bathroom.iWent');
	const bathroomAt = loadText('bathroom.at');
	if (bathroomIWent != '' && bathroomAt != '')
		html += '<li>I went: ' + bathroomIWent + ' at ' + bathroomAt + '</li>';
	

	const breakfast = loadText('meals.breakfast');
	if (breakfast != '')
		html += '<li>Breakfast: ' + breakfast + '</li>';

	const lunch = loadText('meals.lunch');
	if (lunch != '')
		html += '<li>Lunch: ' + lunch + '</li>';

	const snack = loadText('meals.snack');
	if (snack != '')
		html += '<li>Snack: ' + snack + '</li>';

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

	html += '<li>I need: ' + iNeedText + '</li>';

	if (loadText('occurence')) {
		html += '<li>Occurence: Please see teacher</li>';
	}

	const highlights = loadText('highlights');
	if (highlights != '')
		html += '<li>Highlights/ new discoveries: ' + highlights + '</li>';

	html += '</ul></body></html>';

	console.log(html);

	const id = currentChild;

	var carers;

	QueryDB('get_user_info', JSON.stringify({id: id}), function(r) {
		if (!r.success) {
			console.log(r.error);
		} else {
			carers = JSON.parse(r.data.carers);
		}
	}, false);

	var to = [];

	carers.forEach(function(id) {
		QueryDB('get_user_info', JSON.stringify({id: id}), function(r) {
			if (!r.success) {
				console.log(r.error);
			} else {
				to.push(r.data.email);
			}
		}, false);
	});

	const content = html;

	const data = {
		to: to,
		subject: 'JS Test',
		body: content
	};

	SendMail(data, function(r) {
		if (r.success) {
			$('#email button').text('DONE');
			$('#email button').addClass('sent');
		} else {
			$('#email button').text('FAILED');
			$('#email button').addClass('failed');
		}
	});
}