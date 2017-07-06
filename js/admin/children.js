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
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            $(this).addClass('selected');
        }
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
                    console.log(r.success);
                    if (r.success) {
                        $input.addClass('saved');
                    } else {
                        $input.removeClass('saved');
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
                    } else {
                        $input.removeClass('saved');
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
                    } else {
                        $input.removeClass('saved');
                    }
                })

                break;
        }
    });

    $('input[type="password"]').numeric();
});

function selectedChildren() {
    var selected = [];
    $('.list-item').each(function() {
        if ($(this).hasClass('selected')) {
            selected.push($(this).attr('childId'));
        }
    })

    return selected;
}

function removeSelectedChildren() {
    const selected = selectedChildren();

    selected.forEach(function(child) {
        const data = {id: child};
        QueryDB('delete_user', JSON.stringify(data));
        $('.list-item').each(function() {
            if ($(this).hasClass('selected')) {
                $(this).remove();
            }
        })
    })
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
                report.highlight = val;
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
            }
        })
    });
}