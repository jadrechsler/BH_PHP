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

    $(inputs).insertBefore($('#add-details-button').prev());
}

function removeDetails() {
    $('.changed-clothes-detail:last').remove();
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

    form.filter(':input').each(function() {
        const val = $(this).val();

        const attr = $(this).attr('type');
        const name = $(this).attr('name');
        const classes = $(this).attr('class');

        if (val != '') {
            switch (name) {
                case 'i-went':
                    ensurePropExists('bathroom', {});
                    report['bathroom']['iWent'] = val;
                    break;
                case 'i-went-time':
                    ensurePropExists('bathroom', {});
                    report['bathroom']['at'] = val;
                    break;
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