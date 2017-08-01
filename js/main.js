var currentChild;
var currentReport;
var currentName;

function isPresent(id) {
	var present = false;

	console.log(children);

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

	$('#change-pin p').text('Change pin');
	$('#change-pin').removeClass('success');
	$('#change-pin').removeClass('failed');

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
	$emailBtn.removeClass('disabledEmpty');
	$emailBtn.text('EMAIL');

	QueryDB('get_report', '{}', function(r) {
		if (!r.success) {
			console.log(r.error);
		} else {
			const childReport = r.data.reports[currentChild];

			if (childReport != undefined && childReport != null) {
				currentReport = childReport;
				loadReport(childReport);
			} else {
				$emailBtn.addClass('disabledEmpty');
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
		var sectionsComplete = '';
		sections.forEach(function(value) {
			sectionsComplete += '[\''+value+'\']';
		});

		const exec = '(currentReport'+sectionsComplete+');';

		return eval(exec);
	}

	return '';
}

function loadReport(report) {
	$('#i-ate ul').text('');
	$('#i-went p').text('');

	$('#i-was p').text(loadText('feeling.iWas'));

	const napFrom = loadText('nap.from');
	const napTo = loadText('nap.to');
	if (napFrom != '' && napTo != '')
		$('#i-slept p').text(napFrom + ' - ' + napTo);

	const bathroomData = loadText('bathroom');

	bathroomData.forEach(function(value) {
		const iWent = value['iWent'];
		const at = value['at'];

		if (iWent != '')
			$('#i-went p').append('<p>' + iWent + ' at ' + at + '</p>');
	});
	

	const breakfast = loadText('meals.breakfast');
	$('#i-ate ul').append('<li>Breakfast: ' + breakfast + '</li>');

	const breakfastAmount = loadText('meals.breakfast-amount');
	$('#i-ate ul').append('<li>Amount: ' + breakfastAmount + '</li><br />');

	const lunch = loadText('meals.lunch');
	$('#i-ate ul').append('<li>Lunch: ' + lunch + '</li>');

	const lunchAmount = loadText('meals.lunch-amount');
	$('#i-ate ul').append('<li>Amount: ' + lunchAmount + '</li><br />');

	const snack = loadText('meals.snack');
	$('#i-ate ul').append('<li>Snack: ' + snack + '</li>');

	const snackAmount = loadText('meals.snack-amount');
	$('#i-ate ul').append('<li>Amount: ' + snackAmount + '</li><br />');

	const iNeedOptions = ['diapers', 'wipes', 'shirt', 'pants', 'underwear'];
	const iNeed = loadText('needs');
	var iNeedText = '<ul style="list-style-type: none; text-align: left;">';

	iNeedOptions.forEach(function(need) {
		if (iNeed[need]) {
			iNeedText += '<li>' + need + ' &#x2714; </li>'; // Append check mark
		} else if (!iNeed[need]) {
			iNeedText += '<li>' + need + '</li>';
		}
	});

	$('#i-need p').html(iNeedText + '</ul>');

	const occurence = loadText('occurence');
	if (occurence != '') {
		if (!occurence) {
			// Hide occurence box
			$('#occurence').hide();
			$('#report .right .top').css('position', 'absolute');
			$('#report .right .middle').removeClass('occurence-show');
		} else {
			// Show occurence box
			$('#occurence').show();
			$('#report .right .top').css('position', 'relative');
			$('#report .right .middle').addClass('occurence-show');
		}
	} else {
		// Hide occurence box
		$('#occurence').hide();
		$('#report .right .top').css('position', 'absolute');
		$('#report .right .middle').removeClass('occurence-show');
	}

	$('#highlights p').text(loadText('highlights'));

	const changedClothes = loadText('changedClothes');
	var changedClothesText = '';

	if (changedClothes.length > 0) {
		changedClothes.forEach(function(details, key) {
			if (key < changedClothes.length-1) {
				changedClothesText += details + ', ';
			} else {
				changedClothesText += details;
			}
		});
	}

	if (changedClothesText != '')
		$('#changed-clothes p').text(changedClothesText);
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

// autoDimDisplay();

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
	$('#email button').addClass('disabled');

	const date = new Date();
	const day = date.getDate();
	const month = date.getMonth()+1;
	const year = date.getFullYear();

	const formattedDate = month + '/' + day + '/' + year;

	var html = '\
		<html>\
		<head>\
		<meta charset="utf-8">\
		</head>\
		<body>\
		<h1>Daily Report</h1>\
		<h3>'+currentName+' : '+formattedDate+'</h3>\
	';

	console.log(html);

	if (loadText('occurence') != '') {
		html += '<li><span style="color: #F3625C;">Occurence:</span> Please see the teacher</li><br />';
	}

	const iWas = loadText('feeling.iWas');
	if (iWas != '') {
		html += '<h2 style="padding-top: 5px;">I was</h2><p>' + iWas + '</p>';
	}

	const napFrom = loadText('nap.from');
	const napTo = loadText('nap.to');
	if (napFrom != '' && napTo != '')
		html += '<h2 style="padding-top: 5px;">I slept</h2><p>' + napFrom + ' - ' + napTo + '</p>';

	const bathroomData = loadText('bathroom');

	var iWentDone = false;

	function iWentExists() {
		if (!iWentDone) {
			html += '<h2 style="padding-top: 5px;">I went</h2>';
			iWentDone = true;
		}
	}

	bathroomData.forEach(function(value) {
		const iWent = value['iWent'];
		const at = value['at'];

		if (iWent != '') {
			iWentExists();
			html += '<p>' + iWent + ' at ' + at + '</p>';
		}
	});
	

	const breakfast = loadText('meals.breakfast');
	const breakfastAmount = loadText('meals.breakfast-amount');

	const lunch = loadText('meals.lunch');
	const lunchAmount = loadText('meals.lunch-amount');

	const snack = loadText('meals.snack');
	const snackAmount = loadText('meals.snack');

	if (breakfast != '' || lunch != '' || snack != '')
		html += '<h2 style="padding-top: 5px;">Meals</h2>'

	if (breakfast != '')
		html += '<p>Breakfast: ' + breakfast + '</p>';
		html += '<p>Amount: ' + breakfastAmount + '</p><br />';

	if (lunch != '')
		html += '<p>Lunch: ' + lunch + '</p>';
		html += '<p>Amount: ' + lunchAmount + '</p><br />';

	if (snack != '')
		html += '<p>Snack: ' + snack + '</p>';
		html += '<p>Amount: ' + snackAmount + '</p><br />';

	const iNeedOptions = ['diapers', 'wipes', 'shirt', 'pants', 'underwear'];
	const iNeed = loadText('needs');
	var iNeedText = '<h2 style="padding-top: 5px;">I need</h2>';

	iNeedOptions.forEach(function(need) {
		if (iNeed[need]) {
			iNeedText += '<p>' + need + ' &#x2714; </p>'; // Append check mark
		} else if (!iNeed[need]) {
			iNeedText += '<p>' + need + '</p>';
		}
	});

	html += iNeedText;

	const highlights = loadText('highlights');
	if (highlights != '')
		html += '<h2 style="padding-top: 5px;">Highlights/ new discoveries</h2><p>' + highlights + '</p>';

	const changedClothes = loadText('changedClothes');
	var changedClothesText = '';

	if (changedClothes.length > 0) {
		changedClothes.forEach(function(details) {
			changedClothesText += '<p>' + details + '</p>';
		});
	}

	if (changedClothesText != '')
		html += '<h2 style="padding-top: 5px;">Changed clothes</h2>' + changedClothesText;

	html += '</body></html>';

	console.log(html);

	const id = currentChild;

	var carers;


	// Get list of childs carers
	QueryDB('get_user_info', JSON.stringify({id: id}), function(r) {
		if (!r.success) {
			console.log(r.error);
		} else {
			carers = JSON.parse(r.data.carers);
		}
	}, false);

	var to = [];


	// Get email addresses associated with the carers
	carers.forEach(function(id) {
		QueryDB('get_user_info', JSON.stringify({id: id}), function(r) {
			if (!r.success) {
				console.log(r.error);
			} else {
				to.push(r.data.email);
			}
		}, false);
	});

	const data = {
		to: to,
		subject: 'Report: ' + currentName,
		body: html
	};

	SendMail(data, function(r) {
		if (r.success) {
			$('#email button').text('SENT');
			$('#email button').addClass('sent');
		} else {
			$('#email button').text('FAILED');
			$('#email button').addClass('failed');
		}
	});
}

function changePin(newPin) {
	if (String(newPin).length < 4) {
		return false;
	}

	const data = {
		id: currentChild,
		pin: newPin
	};

	QueryDB('change_pin', JSON.stringify(data), function(r) {
		if (!r.success) {
			console.log(r.error);
			$('#change-pin p').text('Failed');
			$('#change-pin').addClass('failed');
		} else {
			$('#change-pin p').text('Success');
			$('#change-pin').addClass('success');
		}
	})

	return true;
}