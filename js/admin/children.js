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
    const form = $('#update-child-main *');

    form.filter(':input').each(function() {
        const val = $(this).val();
        const carerId = $(this).parent().parent().attr('carerId');
        const attr = $(this).attr('type');
        const name = $(this).attr('name');

        if (attr != 'button' && val != undefined && attr != 'file') {
            switch (name) {
                case 'child-name':
                    const changeChildName = {
                        id: childId,
                        name: val
                    };

                    QueryDB('change_name', JSON.stringify(changeChildName), function(r) {
                        if (!r.success) {
                            console.log(r.error);
                        }
                    })
                    break;
                case 'child-teacher':
                    const changeTeacher = {
                        id: childId,
                        teacher: val
                    }

                    QueryDB('change_teacher', JSON.stringify(changeTeacher), function(r) {
                        if (!r.success) {
                            console.log(r.error);
                        }
                    })
                    break;
                case 'pin':
                    const changePin = {
                        id: childId,
                        pin: val
                    }

                    QueryDB('change_teacher', JSON.stringify(changePin), function(r) {
                        if (!r.success) {
                            console.log(r.error);
                        }
                    })
                    break;
                case 'carer-name[]':
                    const changeCarerName = {
                        id: carerId,
                        name: val
                    }

                    QueryDB('change_name', JSON.stringify(changeCarerName), function(r) {
                        if (!r.success) {
                            console.log(r.error);
                        }
                    })
                    break;
                case 'carer-email[]':
                    const changeCarerEmail = {
                        id: carerId,
                        email: val
                    }

                    QueryDB('change_email', JSON.stringify(changeCarerEmail), function(r) {
                        if (!r.success) {
                            console.log(r.error);
                        }
                    })
                    break;
                case 'carer-relation[]':
                    const changeRelation = {
                        id: carerId,
                        relation: val
                    }

                    QueryDB('change_relation', JSON.stringify(changeRelation), function(r) {
                        if (!r.success) {
                            console.log(r.error);
                        }
                    })
                    break;
            }
        } else if (attr == 'file' && name == 'child-picture') {
            var data = new FormData();
            data.append('file', val);

            uploadImage(data, function(r) {
                console.log(r);
            });
        }
    });
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

    $(inputs).insertAfter('.changed-clothes-detail:last');
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
    }
}

function saveReport() {
    var report = {
        bathroom: {
            iWent: "",
            at: ""
        },
        meals: {
            breakfast: "",
            lunch: "",
            snack: ""
        },
        nap: {
            from: "",
            to: ""
        },
        feeling: {
            iWas: ""
        },
        highlights: "",
        changedClothes: [],
        occurence: false,
        medicine: {
            givenBy: "",
            at: ""
        },
        sunscreen: {
            givenBy: "",
            at: ""
        },
        insectRepellent: {
            givenBy: "",
            at: ""
        }
    };

    const form = $('#child-report *');

    form.filter(':input').each(function() {
        const val = $(this).val();

        const attr = $(this).attr('type');
        const name = $(this).attr('name');
        const classes = $(this).attr('class');

        switch (name) {
            case 'i-went':
                report.bathroom.iWent = val;
                break;
            case 'i-went-time':
                report.bathroom.at = val;
                break;
            case 'breakfast':
                report.meals.breakfast = val;
                break;
            case 'lunch':
                report.meals.lunch = val;
                break;
            case 'snack':
                report.meals.snack = val;
                break;
            case 'nap-from':
                report.nap.from = val;
                break;
            case 'nap-to':
                report.nap.to = val;
                break;
            case 'feeling-i-was':
                report.feeling.iWas = val;
                break;
            case 'highlight':
                report.highlights = val;
                break;
            case 'changed-clothes-details[]':
                if (val != "") {
                    report.changedClothes.push(val);
                }
                break;
            case 'occurence':
                report.occurence = $(this).is(':checked') ? true : false;
                break;
            case 'medicine-given-by':
                report.medicine.givenBy = val;
                break;
            case 'medicine-given-at':
                report.medicine.givenAt = val;
                break;
            case 'sunscreen-given-by':
                report.sunscreen.givenBy = val == "on";
                break;
            case 'sunscreen-given-at':
                report.sunscreen.givenAt = val;
                break;
            case 'insect-repellent-given-by':
                report.insectRepellent.givenBy = val;
                break;
            case 'insect-repellent-given-at':
                report.insectRepellent.givenAt = val;
                break;
        }
    })

    QueryDB('get_report', '{}', function(r) {
        var reports = r.data.reports;

        console.log(reports);

        reports[CHILD_ID.toString()] = report;

        const data = {
            reports: JSON.stringify(reports)
        }

        console.log(JSON.stringify(data));

        QueryDB('make_report', JSON.stringify(data), function(rr) {
            if (!rr.success) {
                console.log(rr.error);
            } else {
                window.location.href = 'manage_children.php';
            }
        })
    });
}