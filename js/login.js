function login() {
    var filledInput = 0;

    const id = $('#user').val();
    const pinValue = $('#pin').val();

    if (id == "") {
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
        const data = {id: id, pin: pinValue.toString()};

        console.log(data);

        QueryDB('check_pin', JSON.stringify(data), function(value) {
            console.log(value);
            if (!value.success) {
                empty('#pin');
                $('#error').text("incorrect pin");
            } else {
                filled('#pin');
                $('#error').text("");
                $('#login-submit').click();
            }
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

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    $('input, select').keydown(function(event){
        if(event.keyCode == 13) {
            login();
        }
    });
});