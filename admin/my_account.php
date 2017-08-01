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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My account</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/children.css'); ?>">
</head>
<body>
    <a id="back-button" href="./">
        <span>&larr;</span>
    </a>
    <h1>My account</h1>
    <div id="main">
        <div class="container-fluid staff-item">
            <div class="col-md-3 col-sm-1"></div>
            <div id="children-list-container" class="col-md-6 col-sm-10">
                <div class="container-fluid list-item staff">
                    <div class="col-md-7 col-sm-7 p-container staff-name-container">
                        <p class="staff-name-real"><?php echo $info->name; ?></p>
                        <p class="edit-staff">edit</p>
                    </div>
                    <div class="staff-info-box">
                        <form staffId="<?php echo $info->id; ?>" class="staff-info-form">
                            <div class="one-info">
                                <label for="name">Pin:</label>
                                <input type="password" name="pin" value="<?php echo $info->pin; ?>" maxlength="6" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
    <script>
        const IPADDRESS = "<?php echo $IPADDRESS ?>";
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/admin/children.js'); ?>"></script>
</body>
</html>