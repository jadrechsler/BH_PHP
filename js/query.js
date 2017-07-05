function QueryDB(action, data, callback = function(value){}) {
    return $.ajax({
        url: 'http://'+IPADDRESS+'/query.php',
        type: 'POST',
        data: {action: action, data: data},
        success: function(value) {
            callback(value);
        }
    });
}