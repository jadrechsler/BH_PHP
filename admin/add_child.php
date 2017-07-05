<?php

require('../ipconfig.php');

if (isset($_POST['child-name'])) {
    $childName = $_POST['child-name'];
    $teacher = $_POST['child-teacher'];
    $pin = $_POST['pin'];

    $carerNames = $_POST['carer-name'];
    $carerEmails = $_POST['carer-email'];
    $carerRelations = $_POST['carer-relation'];

    $carers = array();

    for ($x = 0; $x < sizeof($carerNames); $x++) {
        $carer = array(
            'name' => $carerNames[$x],
            'email' => $carerEmails[$x],
            'relation' => $carerRelations[$x],
            'type' => 'carer'
        );

        array_push($carers, $carer);
    }

    $child = array(
        'name' => $childName,
        'pin' => $pin,
        'teacher' => $teacher,
        'type' => 'child'
    );

    echo "http://'.$IPADDRESS.'/query.php?action=new_user&data=".urlencode(json_encode($child));

    $addChild = file_get_contents("http://$IPADDRESS/query.php?action=new_user&data=".urlencode(json_encode($child)));

    $addChildResponse = json_decode($addChild);

    if (!$addChildResponse->success) {
        die($addChildResponse->error);
    }

    foreach ($carers as $carer) {
        $addCarer = file_get_contents("http://$IPADDRESS/query.php?action=new_user&data=".urlencode(json_encode($carer)));

        $addCarerResponse = json_decode($addCarer);

        if (!$addCarerResponse->success) {
            die($addCarerResponse->error);
        }
    }

    $targetFile = '../img/children/'.basename($addChildResponse->data->id.'.jpg');

    move_uploaded_file($_FILES['child-picture']['tmp_name'], $targetFile);

    header('Location: '.$IPADDRESS.'/admin/manage_children.php');

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add child</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin/children.css">
</head>
<body>
    <h1>Add child</h1>
    <div id="add-child-main" class="container-fluid">
        <div class="col-md-3 col-sm-3"></div>
        <div class="col-md-6 col-sm-6">
            <form method="POST" enctype="multipart/form-data" id="add-child">
                <div id="child-info" class="input-section">
                    <p>Child</p>
                    <div class="one-info">
                        <label for="child-name">Name:</label>
                        <input type="text" name="child-name" placeholder="Jim Leaf" /><br />
                    </div>
                    <div class="one-info">
                        <label for="child-teacher">Teacher:</label>
                        <select name="child-teacher">
                            <option selected="selected" value="">&lt;select teacher&gt;</option>
                            <option value="teacher_1">Rory</option>
                            <option value="teacher_2">Sandra</option>
                        </select><br />
                    </div>
                    <div class="one-info">
                        <label for="pin">Pin:</label>
                        <input type="password" name="pin" id="pin" placeholder="123456" maxlength="6" /><br />
                    </div>
                    <div class="one-info">
                        <label id="child-picture-label" for="child-image">Picture:</label>
                        <button type="button" id="child-picture-button" onclick="uploadPicture()">Upload</button>
                        <input id="child-picture-input" type="file" name="child-picture" accept="image/jpeg" /><br />
                    </div>
                </div>
                <div id="carers-info" class="input-section">
                    <p>Carers</p>
                    <div class="carer-solo">
                        <div class="one-info">
                            <label for="carer-name[]">Name:</label>
                            <input type="text" name="carer-name[]" placeholder="Sarah Leaf"><br />
                        </div>
                        <div class="one-info">
                            <label for="carer-email[]">Email:</label>
                            <input type="text" name="carer-email[]" placeholder="sarah.leaf@gmail.com" />
                        </div>
                        <div class="one-info">
                            <label for="carer-relation[]">Relation:</label>
                            <select name="carer-relation[]">
                                <option selected="selected" value="">&lt;select relation&gt;</option>
                                <option value="Mom">Mom</option>
                                <option value="Dad">Dad</option>
                                <option value="Grandma">Grandma</option>
                                <option value="Grandpa">Grandpa</option>
                                <option value="Aunt">Aunt</option>
                                <option value="Uncle">Uncle</option>
                                <option value="Carer">Carer</option>
                            </select><br />
                        </div>
                    </div>
                    <div id="add-carer-button" onclick="addCarer()">
                        <div>
                            <p>Add a carer</p>
                        </div>
                    </div><br /><br />
                </div>
                <button style="visibility: hidden;" id="submit" type="submit"></button>
            </form>
            <div id="complete-button" onclick="addChild()">
                <p>Complete</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3"></div>
    </div>
    <script>
        const IPADDRESS = "<?php echo $IPADDRESS ?>";
    </script>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/query.js"></script>
    <script src="/js/admin/children.js"></script>
</body>
</html>