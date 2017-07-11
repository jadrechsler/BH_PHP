<?php

if (!(isset($_REQUEST['id']))) {
    die('Missing id');
} else if (!(isset($_REQUEST['image']))) {
    die('Missing image');
}

$id = $_REQUEST['id'];
$image = $_FILES['child-picture']['tmp_name'];

$targetFile = 'img/children/'.basename($id.'.jpg');

move_uploaded_file($image, $targetFile);

echo "returns";

?>