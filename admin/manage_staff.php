<?php

session_start();

if (isset($_SESSION['id'])) {
    if (!($_SESSION['id'] == 1)) {
        die('Invalid Authorisation');
    }
} else {
    header('Location: ../login.php');
}

require('../ipconfig.php');

$action = 'get_user_info';

$getTeacher1 = array(
    'id' => 2
);

$getTeacher2 = array(
    'id' => 3
);

$getFloater = array(
    'id' => 4
);

$teacher1 = json_decode(file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($getTeacher1)))->data;

$teacher2 = json_decode(file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($getTeacher2)))->data;

$floater = json_decode(file_get_contents('http://'.$IPADDRESS.'/query.php?action='.$action.'&data='.json_encode($getFloater)))->data;

$staffList = array(
    array(
        'name' => $teacher1->name,
        'email' => $teacher1->email,
        'pin' => $teacher1->pin,
        'id' => 2
    ),
    array(
        'name' => $teacher2->name,
        'email' => $teacher2->email,
        'pin' => $teacher2->pin,
        'id' => 3
    ),
    array(
        'name' => $floater->name,
        'email' => $floater->email,
        'pin' => $floater->pin,
        'id' => 4
    )
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Staff</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/children.css'); ?>">
</head>
<body>
    <a id="back-button" href="./">
        <span>&larr;</span>
    </a>
    <h1>Manage Staff</h1>
    <div id="main">
        <div id="staff-list">
            <?php foreach ($staffList as $staff): ?>
            <div class="container-fluid staff-item">
                <div class="col-md-3 col-sm-1"></div>
                <div id="children-list-container" class="col-md-6 col-sm-10">
                    <div class="container-fluid list-item staff">
                        <div class="col-md-7 col-sm-7 p-container staff-name-container">
                            <p class="staff-name-real"><?php echo $staff['name']; ?></p>
                            <p class="edit-staff">edit</p>
                        </div>
                        <div class="staff-info-box">
                            <form staffId="<?php echo $staff['id']; ?>" class="staff-info-form">
                                <?php if ($staff['id'] != 4): ?>
                                <div class="one-info">
                                    <label for="name">Name:</label>
                                    <input type="text" name="name" value="<?php echo $staff['name']; ?>" />
                                </div>
                                <?php endif; ?>
                                <div class="one-info">
                                    <label for="name">Pin:</label>
                                    <input type="password" name="pin" value="<?php echo $staff['pin']; ?>" maxlength="6" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <?php endforeach; ?>
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