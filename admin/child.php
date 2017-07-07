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

$first1 = explode(' ', $teacher1Name)[0];
$first2 = explode(' ', $teacher2Name)[0];

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
    <h1>Edit child info</h1>
    <div id="add-child-main" class="container-fluid">
        <div class="col-md-3 col-sm-1"></div>
        <div class="col-md-6 col-sm-10">
            <form method="POST" enctype="multipart/form-data" id="add-child">
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
                            <option <?php if($childInfo->teacher == 2) echo 'selected="selected"'; ?> value="2"><?php echo $first1; ?></option>
                            <option <?php if($childInfo->teacher == 3) echo 'selected="selected"'; ?> value="3"><?php echo $first2; ?></option>
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
                    <div class="carer-solo">
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
                            <p>Add a carer</p>
                        </div>
                    </div><br /><br />
                </div>
                <button style="visibility: hidden;" id="submit" type="submit"></button>
            </form>
            <div class="container-fluid update-options">
                <div id="edit-button" class="col-md-4 col-sm-6" onclick="">
                    <div>
                        <p>Update</p>
                    </div>
                </div>
                <div id="remove-button-edit" class="col-md-4 col-sm-6">
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
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/admin/children.js'); ?>"></script>
</body>
</html>