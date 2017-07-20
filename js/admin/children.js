function searchStudents() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("search-input");
    filter = input.value.toUpperCase();
    ul = document.getElementById("student-list");
    li = ul.getElementsByClassName("student-item");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("p")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";

        }
    }
}

$(document).ready(function() {
    $('.student').click(function() {
        const id = $(this).attr('childId');

        window.location.href = 'report.php?id='+id;
    });

    $('#child-picture-input').change(function() {
        const filename = $(this).val().split("\\");
        $('#child-picture-button').html(filename[filename.length-1]);
    });

    $('.staff-info-form input').keyup(function(event) {
        const $input = $(this);
        const id = $input.closest('form').attr('staffId');
        const val = $input.val();
        const name = $input.attr('name');

        switch (name) {
            case 'name':
                const newName = {
                    id: id,
                    name: val
                };

                QueryDB('change_name', JSON.stringify(newName), function(r) {
                    if (r.success) {
                        $input.addClass('saved');
                        $input.closest('.staff-info-box').siblings('.staff-name-container').children('.staff-name-real').text(val);
                        $input.closest('.staff-info-box').siblings('.staff-name-container').children('.edit-staff').text('saved name');
                    } else {
                        $input.removeClass('saved');
                        $input.closest('.staff-info-box').siblings('.staff-name-container').children('.edit-staff').text('error');
                    }
                })

                break;
            case 'email':
                const newEmail = {
                    id: id,
                    email: val
                };

                QueryDB('change_email', JSON.stringify(newEmail), function(r) {
                    if (r.success) {
                        $input.addClass('saved');
                        $input.closest('.staff-info-box').siblings('.staff-name-container').children('.edit-staff').text('saved email');
                    } else {
                        $input.removeClass('saved');
                        $input.closest('.staff-info-box').siblings('.staff-name-container').children('.edit-staff').text('error');
                    }
                })

                break;
            case 'pin':
                if (val.length >= 4) {

                    const newPin = {
                        id: id,
                        pin: val
                    };

                    QueryDB('change_pin', JSON.stringify(newPin), function(r) {
                        if (r.success) {
                            $input.addClass('saved');
                            $input.closest('.staff-info-box').siblings('.staff-name-container').children('.edit-staff').text('saved pin');
                        } else {
                            $input.removeClass('saved');
                            $input.closest('.staff-info-box').siblings('.staff-name-container').children('.edit-staff').text('error');
                        }
                    })
                } else {
                    $input.removeClass('saved');
                    $input.closest('.staff-info-box').siblings('.staff-name-container').children('.edit-staff').text('minimum pin length is 4');
                }

                break;
        }
    });

    $('input[type="password"]').numeric();
});

function deleteChild() {
    const data = {id: childId};
    QueryDB('delete_user', JSON.stringify(data), function(r) {
        if (!r.success) {
            console.log(r.error);
        }
    });
}

function updateChild() {
    const form = $('#update-child *');

    var filled = 0;

    form.filter(':input').each(function() {
        const val = $(this).val();

        const attr = $(this).attr('type');

        if (attr == 'text' || $(this).is('select')) {
            if (val == "") {
                $(this).addClass('empty');
            } else {
                $(this).removeClass('empty');
                filled++;
            }
        } else if (attr == 'password') {
            if (val == "" || val.length < 4) {
                $(this).addClass('empty');
            } else {
                $(this).removeClass('empty');
                filled++;
            }
        }
    });

    if (filled >= form.filter(':input').length-(5 + $('input[name="carerId[]"]').length)) {
        console.log("aaa");
        $('#submit').click();
    } else {
        window.scrollTo(0, 0);
    }
}

function uploadPicture() {
    $('#child-picture-input').click();
}

function addCarer() {
    const inputs = '<div class="carer-solo"><div class="one-info"><label for="carer-name[]">Name:</label><input type="text" name="carer-name[]" placeholder="Sarah Leaf"><br /></div><div class="one-info"><label for="carer-email[]">Email:</label><input type="text" name="carer-email[]" placeholder="sarah.leaf@gmail.com" /></div><div class="one-info"><label for="carer-relation[]">Relation:</label><select name="carer-relation[]"><option selected="selected" value="">&lt;select relation&gt;</option><option value="Mom">Mom</option><option value="Dad">Dad</option><option value="Grandma">Grandma</option><option value="Grandpa">Grandpa</option><option value="Aunt">Aunt</option><option value="Uncle">Uncle</option><option value="Carer">Carer</option></select><br /></div></div>'; 

    $(inputs).insertAfter('.carer-solo:last');
}

function addDetails() {
    const inputs = '<div class="one-info changed-clothes-detail"><label for="changed-clothes-details[]">Details:</label><input placeholder="changed shirt" type="text" name="changed-clothes-details[]" /><br /></div>'; 

    $(inputs).insertBefore('#changed-clothes-append');
}

function removeDetails() {
    $('.changed-clothes-detail:last').remove();
}

function addBathroom() {
    const inputs = '<div class="bathroom-detail"><div class="one-info"><br /><label for="i-went[]">I went:</label><select name="i-went[]"><option value="">&lt;select&gt;</option><option value="Wet">Wet</option><option value="Dry">Dry</option><option value="Peed">Peed</option><option value="BM">BM</option><option value="LS">LS</option></select><br /></div><div class="one-info"><label for="i-went-time[]">At:</label><div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true"><input placeholder="00:00" type="text" name="i-went-time[]" class="form-control" /><span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span></div></div></div>'; 

    $(inputs).insertBefore('#bathroom-append');
    $('.clockpicker').clockpicker();
}

function removeBathroom() {
    $('.bathroom-detail:last').remove();
}

function addChild() {
    const form = $('#add-child *');

    var filled = 0;

    form.filter(':input').each(function() {
        const val = $(this).val();

        const attr = $(this).attr('type');

        if (attr == 'text' || $(this).is('select')) {
            if (val == "") {
                $(this).addClass('empty');
            } else {
                $(this).removeClass('empty');
                filled++;
            }
        } else if (attr == 'password') {
            if (val == "" || val.length < 4) {
                $(this).addClass('empty');
            } else {
                $(this).removeClass('empty');
                filled++;
            }
        } else if (attr == 'file') {
            if (val == "") {
                $('#child-picture-button').addClass('empty');
            } else {
                $('#child-picture-button').removeClass('empty');
                filled++;
            }
        }
    })

    console.log(filled);
    console.log(form.filter(':input').length-2);

    if (filled >= form.filter(':input').length-2) {
        console.log("aaa");
        $('#submit').click();
    } else {
        window.scrollTo(0, 0);
    }
}

function saveReport() {
    var report = {
        'occurence': false,
        'needs': {
            'diapers': false,
            'wipes': false,
            'shirt': false,
            'pants': false,
            'underwear': false
        }
    };

    const form = $('#child-report *');

    function ensurePropExists(propName, val) {
        if (report[propName] === undefined) {
            report[propName] = val;
        }
    }

    function isChecked(checkbox) {
        return checkbox.is(':checked');
    }

    var iWentArr = [];
    var iWentTimeArr = [];

    form.filter(':input').each(function() {
        const val = $(this).val();

        const attr = $(this).attr('type');
        const name = $(this).attr('name');
        const classes = $(this).attr('class');

        if (name == 'i-went[]')
            iWentArr.push(val);
        if (name == 'i-went-time[]')
            iWentTimeArr.push(val);

        if (val != '') {
            switch (name) {
                case 'breakfast':
                    ensurePropExists('meals', {});
                    report['meals']['breakfast'] = val;
                    break;
                case 'lunch':
                    ensurePropExists('meals', {});
                    report['meals']['lunch'] = val;
                    break;
                case 'snack':
                    ensurePropExists('meals', {});
                    report['meals']['snack'] = val;
                    break;
                case 'nap-from':
                    ensurePropExists('nap', {});
                    report['nap']['from'] = val;
                    break;
                case 'nap-to':
                    ensurePropExists('nap', {});
                    report['nap']['to'] = val;
                    break;
                case 'feeling-i-was':
                    ensurePropExists('feeling', {});
                    report['feeling']['iWas'] = val;
                    break;
                case 'highlight':
                    report['highlights'] = val;
                    break;
                case 'changed-clothes-details[]':
                    ensurePropExists('changedClothes', []);
                    if (val != "") {
                        report['changedClothes'].push(val);
                    }
                    break;
                case 'occurence':
                    report['occurence'] = isChecked($(this));
                    break;
                case 'diapers':
                    ensurePropExists('needs', {});
                    report['needs']['diapers'] = isChecked($(this));
                    break;
                case 'wipes':
                    ensurePropExists('needs', {});
                    report['needs']['wipes'] = isChecked($(this));
                    break;
                case 'shirt':
                    ensurePropExists('needs', {});
                    report['needs']['shirt'] = isChecked($(this));
                    break;
                case 'pants':
                    ensurePropExists('needs', {});
                    report['needs']['pants'] = isChecked($(this));
                    break;
                case 'underwear':
                    ensurePropExists('needs', {});
                    report['needs']['underwear'] = isChecked($(this));
                    break;
                case 'medicine-given-by':
                    ensurePropExists('medicine', {});
                    report['medicine']['givenBy'] = val;
                    break;
                case 'medicine-given-at':
                    ensurePropExists('medicine', {});
                    report['medicine']['givenAt'] = val;
                    break;
                case 'sunscreen-given-by':
                    ensurePropExists('sunscreen', {});
                    report['sunscreen']['givenBy'] = val;
                    break;
                case 'sunscreen-given-at':
                    ensurePropExists('sunscreen', {});
                    report['sunscreen']['givenAt'] = val;
                    break;
                case 'insect-repellent-given-by':
                    ensurePropExists('insectRepellent', {});
                    report['insectRepellent']['givenBy'] = val;
                    break;
                case 'insect-repellent-given-at':
                    ensurePropExists('insectRepellent', {});
                    report['insectRepellent']['givenAt'] = val;
                    break;
            }
        }
    })

    if (iWentArr.length > 0 || iWentTimeArr.length > 0) {
        ensurePropExists('bathroom', []);

        for (var x = 0; x < iWentArr.length; x++) {
            const iWent = iWentArr[x];
            const at = iWentTimeArr[x];

            if (iWent != '' || at != '') {
                report['bathroom'].push({
                    'iWent': iWentArr[x],
                    'at': iWentTimeArr[x]
                });
            }
        }
    }

    QueryDB('get_report', '{}', function(r) {
        var reports = r.data.reports;

        //console.log(reports);

        reports[CHILD_ID.toString()] = report;

        const data = {
            reports: JSON.stringify(reports)
        }

        //console.log(JSON.stringify(data));

        QueryDB('make_report', JSON.stringify(data), function(rr) {
            if (!rr.success) {
                console.log(rr.error);
            } else {
                window.location.href = 'manage_children.php';
            }
        })
    });
}

function updateRemoveCarer() {
    if ($('.carer-solo').length > 1) {
        const $lastCarer = $('.carer-solo:last');
        const carerId = $lastCarer.attr('carerId');

        const data = {
            carerId: carerId,
            childId: childId
        }

        console.log(JSON.stringify(data));

        QueryDB('delete_carer', JSON.stringify(data), function(r) {
            if (!r.success) {
                console.log(r.error);
            }
        });

        $('.carer-solo:last').remove();
    }
}

function getChildrenList() {
    var childrenList = [];

    QueryDB('get_children', '{}', function(r) {
        if (!r.success) {
            console.log(r.error);
        } else {
            const children = r.data.children;

            children.forEach(function(child) {
                childrenList.push(child.name);
            })
        }
    }, false);

    console.log(childrenList);

    return childrenList;
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

function emailHistoricalReport() {
    $('#complete-button').addClass('disabled');

    const formattedDate = pastDate.replace(/\s+/g, '/');

    console.log(formattedDate);

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
    const lunch = loadText('meals.lunch');
    const snack = loadText('meals.snack');

    if (breakfast != '' || lunch != '' || snack != '')
        html += '<h2 style="padding-top: 5px;">Meals</h2>'

    if (breakfast != '')
        html += '<p>Breakfast: ' + breakfast + '</p>';

    if (lunch != '')
        html += '<p>Lunch: ' + lunch + '</p>';

    if (snack != '')
        html += '<p>Snack: ' + snack + '</p>';

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

    const id = CHILD_ID;

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
            $('#complete-button p').text('SENT');
            $('#complete-button').addClass('sent');
        } else {
            $('#complete-button p').text('FAILED');
            $('#complete-button').addClass('failed');
        }
    });
}