<?php

session_start();

if (isset($_SESSION['id'])) {
    if (!($_SESSION['id'] <= 3)) {
        die('Invalid Authorisation');
    }
} else {
    header('Location: ../login.php');
}

require('../ipconfig.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Logs</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/children.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/jquery-ui.css'); ?>">
</head>
<body>
    <a id="back-button" href="./">
        <span>&larr;</span>
    </a>
    <h1>Logs</h1>
    <div id="logs-main" class="container-fluid">
        <div class="col-md-3 col-sm-1"></div>
        <div class="col-md-6 col-sm-10">
            <div id="date-selector-container" class="container-fluid col-md-12 col-sm-12">
                <input id="date-selector" type="text" placeholder="Select date" />
                <textarea id="log-container" readonly="readonly"></textarea>
            </div>
        </div>
    </div>
    <script>
        const IPADDRESS = "<?php echo $IPADDRESS ?>";
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/jquery-ui.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/admin/children.js'); ?>"></script>
    <script>
        $('#date-selector').datepicker({
            onClose: function() {
                const $dateInput = $(this);
                const val = $('#date-selector').val().replace(/\s+/g, '_');

                console.log(val);

                const data = {
                    date: val
                };

                QueryDB('get_log', JSON.stringify(data), function(r) {
                    console.log(r);
                    if (r.success) {
                        $('#log-container').text(r.data.log);
                    } else {
                        $('#log-container').text('not found');
                    }
                });
            },
            dateFormat: 'dd m yy'
        });
    </script>
</body>
</html>