<?php

session_start();

$exitPage = 'Location: admin/dashboard.php';

if (isset($_SESSION['id'])) {
    header($exitPage);
}

require('ipconfig.php');

if (isset($_REQUEST['submit'])) {
    $action = 'check_pin';

    $data = array(
        'id' => $_REQUEST['id'],
        'pin' => $_REQUEST['pin']
    );

    $check = file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($data));

    if (json_decode($check)->success) {
        $action = 'get_name';

        $data = array(
            'id' => $_REQUEST['id']
        );

        $name = json_decode(file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($data)))->data->name;


        $_SESSION['id'] = $_REQUEST['id'];
        $_SESSION['name'] = $name;

        header($exitPage);
    } else {
        echo 'Something wen\'t wrong validating you';
    }
}

$action = 'get_name';

$getTeacher1 = array(
    'id' => 2
);

$getTeacher2 = array(
    'id' => 3
);

$getFloater = array(
    'id' => 4
);

$teacher1Name = json_decode(file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($getTeacher1)))->data->name;

$teacher2Name = json_decode(file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($getTeacher2)))->data->name;

$floaterName = json_decode(file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($getFloater)))->data->name;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/login.css'); ?>">
</head>
<body>
    <form style="visibility: hidden;" method="POST" action="/login.php">
        <input type="text" >
    </form>
    <div class="container-fluid">
        <div class="col-md-3 col-sm-1"></div>
        <div class="col-md-6 col-sm-10 panel-container">
            <div id="panel">
                <div>
                <h1>Login</h1>
                    <form method="POST">
                        <select name="id" id="user">
                            <option selected="selected" value="">&lt;select user&gt;</option>
                            <option value="1">Admin</option>
                            <option value="2"><?php echo $teacher1Name; ?></option>
                            <option value="3"><?php echo $teacher2Name; ?></option>
                            <option value="4"><?php echo $floaterName; ?></option>
                        </select><br />
                        <input type="password" name="pin" id="pin" placeholder="enter pin" maxlength="6" pattern="[0-9]*" inputmode="numeric" />
                        <input name="submit" style="visibility: hidden; position: fixed;" id="login-submit" type="submit" />
                    </form>
                    <button id="login-button" onclick="login()">Sign in</button>
                    <p id="error"></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-1"></div>
    </div>
    <script>
        const IPADDRESS = "<?php echo $IPADDRESS ?>";
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/login.js'); ?>"></script>
</body>
</html>