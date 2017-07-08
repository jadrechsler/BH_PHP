<?php

session_start();

if (isset($_SESSION['id'])) {
    if (!($_SESSION['id'] <= 4)) {
        die('Invalid Authorisation');
    }
} else {
    header('Location: ../login.php');
}

require('../ipconfig.php');

$action = 'get_user_info';

$id = $_SESSION['id'];

$data = array(
    'id' => $id
);

$infoRequest = json_decode(file_get_contents("http://$IPADDRESS/query.php?action=$action&data=".urlencode(json_encode($data))));

if (!$infoRequest->success) {
    die($infoRequest->error);
}

$info = $infoRequest->data;

$isAdmin = $id == 1 ? true : false;
$isTeacher = $id == 2 || $id == 3 ? true : false;
$isFloater = $id == 4 ? true : false;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/dashboard.css'); ?>">
</head>
<body>
    <h1><?php echo $info->name; ?></h1>
    <div id="main">
        <?php if ($isAdmin): ?>
        <div class="container-fluid">
            <div onclick="window.location.href='manage_children.php'" class="col-md-4 col-sm-4 option-container">
                <div class="option-box">
                    <p>Manage Children</p>
                </div>                
            </div>
            <div onclick="window.location.href='manage_staff.php'" class="col-md-4 col-sm-4 option-container">
                <div class="option-box">
                    <p>Manage Staff</p>
                </div>                
            </div>
            <div onclick="window.location.href='my_account.php'" class="col-md-4 col-sm-4 option-container">
                <div class="option-box">
                    <p>My Account</p>
                </div>                
            </div>
        </div>
        <?php endif; if ($isTeacher): ?>
        <div class="container-fluid">
            <div class="col-md-2 col-sm-2"></div>
            <div onclick="window.location.href='manage_children.php'" class="col-md-4 col-sm-4 option-container">
                <div class="option-box">
                    <p>Manage Children</p>
                </div>                
            </div>
            <div onclick="window.location.href='my_account.php'" class="col-md-4 col-sm-4 option-container">
                <div class="option-box">
                    <p>My Account</p>
                </div>                
            </div>
            <div class="col-md-2 col-sm-2"></div>
        </div>
        <?php endif; if ($isFloater): ?>
        <div class="container-fluid">
            <div class="col-md-2 col-sm-2"></div>
            <div onclick="window.location.href='manage_children.php'" class="col-md-8 col-sm-8 option-container">
                <div class="option-box">
                    <p>Manage Children</p>
                </div>                
            </div>
            <div class="col-md-2 col-sm-2"></div>
        </div>
        <?php endif; ?>
    </div>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
</body>
</html>