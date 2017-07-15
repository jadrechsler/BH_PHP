function QueryDB(action, data, callback = function(value){}, async = true) {
    return $.ajax({
        url: 'http://'+IPADDRESS+'/query.php',
        type: 'POST',
        data: {action: action, data: data},
        async: async,
        success: function(value) {
            callback(value);
        }
    });
}

function SendMail(data, callback = function(value){}, async = true) {
    return $.ajax({
        url: 'http://'+IPADDRESS+'/mail.php',
        type: 'POST',
        data: data,
        async: async,
        success: function(value) {
            callback(value);
        }
    });
}