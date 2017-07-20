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

if (isset($_POST['child-name'])) {
    $childId = $_POST['childId'];
    $childName = $_POST['child-name'];
    $teacher = $_POST['child-teacher'];
    $pin = $_POST['pin'];
    $currentCarers = json_decode($_POST['currentCarers']);

    $carerNames = $_POST['carer-name'];
    $carerEmails = $_POST['carer-email'];
    $carerRelations = $_POST['carer-relation'];
    $carerIds = $_POST['carerId'];

    $carers = array();
    $newCarers = array();

    for ($x = 0; $x < sizeof($carerNames); $x++) {
        $carer;

        if (isset($carerIds[$x])) {
            $carer = array(
                'name' => $carerNames[$x],
                'email' => $carerEmails[$x],
                'relation' => $carerRelations[$x],
                'id' => $carerIds[$x]
            );
            array_push($carers, $carer);
        } else {
            $carer = array(
                'name' => $carerNames[$x],
                'email' => $carerEmails[$x],
                'relation' => $carerRelations[$x],
                'type' => 'carer'
            );
            array_push($newCarers, $carer);
        }
    }

    foreach ($carers as $carer) {
        $updateCarer = file_get_contents("http://$IPADDRESS/query.php?action=update_user&data=".urlencode(json_encode($carer)));

        // echo "http://$IPADDRESS/query.php?action=update_user&data=".urlencode(json_encode($carer));

        $updateCarerResponse = json_decode($updateCarer);

        if (!$updateCarerResponse->success) {
            die($updateCarerResponse->error);
        }
    }

    $newCarerIds = array();

    foreach ($newCarers as $carer) {
        $addCarer = file_get_contents("http://$IPADDRESS/query.php?action=new_user&data=".urlencode(json_encode($carer)));

        // echo "http://$IPADDRESS/query.php?action=new_user&data=".urlencode(json_encode($carer));

        $addCarerResponse = json_decode($addCarer);

        if (!$addCarerResponse->success) {
            die($addCarerResponse->error);
        }

        array_push($newCarerIds, $addCarerResponse->data->id);
    }

    $child = array(
        'id' => $childId,
        'name' => $childName,
        'pin' => $pin,
        'teacher' => $teacher,
        'carers' => json_encode(array_merge($carerIds, $newCarerIds))
    );
    // echo json_encode($carerIds);

    // echo "http://$IPADDRESS/query.php?action=update_user&data=".urlencode(json_encode($child));

    $addChild = file_get_contents("http://$IPADDRESS/query.php?action=update_user&data=".urlencode(json_encode($child)));

    $addChildResponse = json_decode($addChild);

    if (!$addChildResponse->success) {
        die($addChildResponse->error);
    }

    $targetFile = '../img/children/'.basename($childId.'.jpg');

    if (is_uploaded_file($_FILES['child-picture']['tmp_name'])) {
        if (file_exists($targetFile))
            unlink($targetFile);

        move_uploaded_file($_FILES['child-picture']['tmp_name'], $targetFile);
    }

    header('Location: manage_children.php');
}

if (!isset($_REQUEST['id'])) {
    die('Missing id parameter');
}

$id = $_REQUEST['id'];

$childInfoRequest = file_get_contents("http://$IPADDRESS/query.php?action=get_user_info&data=".urlencode("{\"id\": $id}"));

$childInfo = json_decode($childInfoRequest)->data;

$getTeacher1 = array(
    'id' => 2
);

$getTeacher2 = array(
    'id' => 3
);

$teacher1Name = json_decode(file_get_contents("http://$IPADDRESS/query.php?action=get_name&data=".urlencode(json_encode($getTeacher1))))->data->name;

$teacher2Name = json_decode(file_get_contents("http://$IPADDRESS/query.php?action=get_name&data=".urlencode(json_encode($getTeacher2))))->data->name;

$carersInfo = array();

foreach (json_decode($childInfo->carers) as $carer) {
    $data = array(
        'id' => $carer
    );

    $carerInfo = json_decode(file_get_contents("http://$IPADDRESS/query.php?action=get_user_info&data=".urlencode(json_encode($data))))->data;

    array_push($carersInfo, $carerInfo);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit child info</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/children.css'); ?>">
</head>
<body>
    <a id="back-button" href="/admin/manage_children.php">
        <span>&larr;</span>
    </a>
    <h1>Edit child info</h1>
    <div id="update-child-main" class="container-fluid">
        <div class="col-md-3 col-sm-1"></div>
        <div class="col-md-6 col-sm-10">
            <form method="POST" enctype="multipart/form-data" id="update-child">
                <div id="child-info" class="input-section">
                    <p>Child</p>
                    <div class="one-info">
                        <label for="child-name">Name:</label>
                        <input type="text" name="child-name" placeholder="Jim Leaf" value="<?php echo $childInfo->name; ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="child-teacher">Teacher:</label>
                        <select name="child-teacher">
                            <option <?php if($childInfo->teacher != 2 && $childInfo->teacher != 4) echo 'selected="selected"'; ?> value="">&lt;select teacher&gt;</option>
                            <option <?php if($childInfo->teacher == 2) echo 'selected="selected"'; ?> value="2"><?php echo $teacher1Name; ?></option>
                            <option <?php if($childInfo->teacher == 3) echo 'selected="selected"'; ?> value="3"><?php echo $teacher2Name; ?></option>
                            <option <?php if($childInfo->teacher == 0) echo 'selected="selected"'; ?> value="0">Inactive</option>
                        </select><br />
                    </div>
                    <div class="one-info">
                        <label for="pin">Pin:</label>
                        <input value="<?php echo $childInfo->pin; ?>" type="password" name="pin" id="pin" placeholder="123456" maxlength="6" pattern="\d*" /><br />
                    </div>
                    <div class="one-info">
                        <label id="child-picture-label" for="child-image">Picture:</label>
                        <button type="button" id="child-picture-button" onclick="uploadPicture()">Upload</button>
                        <input id="child-picture-input" type="file" name="child-picture" accept="image/jpeg, image/jpg" /><br />
                    </div>
                </div>
                <div id="carers-info" class="input-section">
                    <p>Carers</p>
                    <?php foreach ($carersInfo as $carerInfo): ?>
                    <div carerId="<?php echo $carerInfo->id; ?>" class="carer-solo">
                        <input style="visibility: hidden; position: fixed;" type="number" name="carerId[]"  value="<?php echo $carerInfo->id; ?>" />
                        <div class="one-info">
                            <label for="carer-name[]">Name:</label>
                            <input value="<?php echo $carerInfo->name; ?>" type="text" name="carer-name[]" placeholder="Sarah Leaf"><br />
                        </div>
                        <div class="one-info">
                            <label for="carer-email[]">Email:</label>
                            <input value="<?php echo $carerInfo->email; ?>" type="text" name="carer-email[]" placeholder="sarah.leaf@gmail.com" />
                        </div>
                        <div class="one-info">
                            <label for="carer-relation[]">Relation:</label>
                            <select name="carer-relation[]">
                                <option selected="selected" value="">&lt;select relation&gt;</option>
                                <option <?php if ($carerInfo->relation == 'Mom') echo 'selected="selected"'; ?> value="Mom">Mom</option>
                                <option <?php if ($carerInfo->relation == 'Dad') echo 'selected="selected"'; ?> value="Dad">Dad</option>
                                <option <?php if ($carerInfo->relation == 'Grandma') echo 'selected="selected"'; ?> value="Grandma">Grandma</option>
                                <option <?php if ($carerInfo->relation == 'Grandpa') echo 'selected="selected"'; ?> value="Grandpa">Grandpa</option>
                                <option <?php if ($carerInfo->relation == 'Aunt') echo 'selected="selected"'; ?> value="Aunt">Aunt</option>
                                <option <?php if ($carerInfo->relation == 'Uncle') echo 'selected="selected"'; ?> value="Uncle">Uncle</option>
                                <option <?php if ($carerInfo->relation == 'Carer') echo 'selected="selected"'; ?> value="Carer">Carer</option>
                            </select><br />
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div id="add-carer-button" onclick="addCarer()">
                        <div>
                            <p>Add</p>
                        </div>
                    </div><br />
                    <br /><div class="remove-details-button" onclick="updateRemoveCarer()">
                        <div>
                            <p>Remove</p>
                        </div>
                    </div><br /><br />
                </div>
                <input style="visibility: hidden; position: fixed;" type="number" name="childId"  value="<?php echo $id; ?>" />
                <input style="visibility: hidden; position: fixed;" type="number" name="currentCarers"  value='<?php echo $childInfo->carers; ?>' />
                <button style="visibility: hidden; position: fixed;" id="submit" type="submit"></button>
            </form>
            <div class="container-fluid update-options">
                <div id="edit-button" class="col-md-6 col-sm-6 update-option" onclick="updateChild()">
                    <div>
                        <p>Update</p>
                    </div>
                </div>
                <div id="remove-button-edit" class="col-md-6 col-sm-6 update-option" onclick="deleteChild(); window.location.href='manage_children.php'">
                    <div>
                        <p>Remove</p>
                    </div>
                </div>             
            </div>
        </div>
        <div class="col-md-3 col-sm-1"></div>
    </div>
    <script>
        const IPADDRESS = "<?php echo $IPADDRESS ?>";
        const childId = <?php echo $id ?>;
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/admin/children.js'); ?>"></script>
</body>
</html>