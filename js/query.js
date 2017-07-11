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

function uploadImage(imageData, callback = function(value){}) {
    return $.post('http://'+IPADDRESS+'/upload_image.php', imageData);
}