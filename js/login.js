function login() {
    var filledInput = 0;

    const userValue = $('#user').val();
    const pinValue = $('#pin').val();

    if (userValue == "") {
        empty('#user');
    } else {
        filled('#user');
        filledInput++;
    }
    if (pinValue == "") {
        empty('#pin');
    } else {
        filled('#pin');
        filledInput++;
    }

    if (filledInput == 2) {
        var id;

        switch(userValue) {
            case 'admin':
                id = 1;
                break;
            case 'teacher_1':
                id = 2;
                break;
            case 'teacher_2':
                id = 3;
                break;
            case 'floater':
                id = 4;
                break;
            default:
                console.log('Something wen\'t wrong');
                break;
        }

        const data = {id: id, pin: pinValue};

        QueryDB('check_pin', JSON.stringify(data), function(value) {
            console.log(value.success);
        });
    }
}

function filled(input) {
    $(input).css('border', '1px solid grey');
}

function empty(input) {
    $(input).css('border', '1px solid red');
}

$(document).ready(function() {
    $('input[type="password"]').numeric();
});