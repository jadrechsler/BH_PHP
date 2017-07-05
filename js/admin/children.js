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
    $('.list-item').click(function() {
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
    const inputs = '<div class="one-info changed-clothes-detail"><label for="changed-clothes-details[]">Details:</label><input type="text" name="changed-clothes-details[]" /><br /></div>'; 

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

// function saveReport() {

// }