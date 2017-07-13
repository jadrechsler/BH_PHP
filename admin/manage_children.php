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

$id = $_SESSION['id'];

$fetchChildren = file_get_contents('http://'.$IPADDRESS.'/query.php?action=get_children&data={}');

$children = json_decode($fetchChildren)->data->children;

$displayChildren = array();
$inactiveChildren = array();

if ($id == 2 || $id == 3) {
    foreach ($children as $child) {
        if ($child->teacher == $id) {
            array_push($displayChildren, $child);
        }
    }
} elseif ($id == 4) {
    foreach ($children as $child) {
        if (($child->teacher == 2 || $child->teacher == 3) && $child->present) {
            array_push($displayChildren, $child);
        }
    }
} else {
    foreach ($children as $child) {
        if ($child->present) {
            array_push($displayChildren, $child);
        } elseif (!$child->present) {
            array_push($inactiveChildren, $child);
        }
    }
}

$isAdmin = $id == 1 ? true : false;
$isTeacher = $id == 2 || $id == 3 ? true : false;
$isFloater = $id == 4 ? true : false;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Children</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/children.css'); ?>">
</head>
<body>
    <a id="back-button" href="/admin">
        <span>&larr;</span>
    </a>
    <h1>Manage Children</h1>
    <?php if ($isAdmin || $isFloater): ?>
    <div class="container-fluid student-item">
        <div class="col-md-3 col-sm-1"></div>
        <div id="options" class="col-md-6 col-sm-10">
            <div class="container-fluid options-container">
                <div class="col-md-12 col-sm-12">
                    <input type="text" id="search-input" onkeyup="searchStudents()" placeholder="Search for a name">
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <?php endif; ?>
    <div id="main">
        <div id="student-list">
            <?php foreach ($displayChildren as $child): ?>
            <div refChildId="<?php echo $child->id; ?>" class="container-fluid student-item">
                <div class="col-md-3 col-sm-1"></div>
                <div id="children-list-container" class="col-md-6 col-sm-10">
                    <div>
                        <div childId="<?php echo $child->id; ?>" class="container-fluid col-sm-11 col-md-11 list-item student">
                            <div class="col-md-4 col-sm-4 img-container">
                                <img class="round-img" src="<?php echo AddrLink("img/children/$child->id.jpg"); ?>" height="50px" width="50px" />
                            </div>                    
                            <div class="col-md-7 col-sm-7 p-container">
                                <p><?php echo $child->name; ?></p>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 report-edit-button-container" onclick="window.location.href='child.php?id='+<?php echo $child->id; ?>">
                            <div class="report-edit-button">
                                <p>&#x370;</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <?php endforeach; ?>
            <?php if (sizeof($inactiveChildren) > 0): ?>
            <h2>Inactive</h2>
            <?php foreach ($inactiveChildren as $child): ?>
            <div refChildId="<?php echo $child->id; ?>" class="container-fluid student-item">
                <div class="col-md-3 col-sm-1"></div>
                <div id="children-list-container" class="col-md-6 col-sm-10">
                    <div>
                        <div childId="<?php echo $child->id; ?>" class="container-fluid col-sm-11 col-md-11 list-item student">
                            <div class="col-md-4 col-sm-4 img-container">
                                <img class="round-img" src="<?php echo AddrLink("img/children/$child->id.jpg"); ?>" height="50px" width="50px" />
                            </div>                    
                            <div class="col-md-7 col-sm-7 p-container">
                                <p><?php echo $child->name; ?></p>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 report-edit-button-container" onclick="window.location.href='child.php?id='+<?php echo $child->id; ?>">
                            <div class="report-edit-button">
                                <p>&#x370;</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php if (!$isFloater): ?>
    <div class="container-fluid student-item">
        <div class="col-md-3 col-sm-1"></div>
        <div id="options" class="col-md-6 col-sm-10">
            <div class="container-fluid options-container">
                <div class="col-md-12 col-sm-12 option-button-container">
                    <div id="add-button" class="option-button" onclick="window.location.href = '/admin/add_child.php'">
                        <p>Add</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <?php endif; ?>
    <script>
        const IPADDRESS = "<?php echo $IPADDRESS ?>";
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/admin/children.js'); ?>"></script>
</body>
</html>