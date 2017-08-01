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

if (!is_numeric($id)) {
    die('Invalid id Parameter');
}

$nameRequest = file_get_contents("http://$IPADDRESS/query.php?action=get_name&data=".urlencode("{\"id\": $id}"));

$name = json_decode($nameRequest)->data->name;

$reportRequest = json_decode(file_get_contents("http://$IPADDRESS/query.php?action=get_report&data=".urlencode("{}")), true);

if (!$reportRequest['success']) {
    die('Error fetching current report');
}

$reports = $reportRequest['data']['reports'];

$reportSet = isset($reports["$id"]);

if ($reportSet) {
    $report = $reports["$id"];
}

function GetSaved($spec) {
    global $reportSet;
    if ($reportSet) {
        global $report;

        $exec = '
            if (isset($report'.$spec.')) {
                return $report'.$spec.';
            }
            return "";
        ';

        return eval($exec);
    }

    return '';
}

$bathroomData = GetSaved('[\'bathroom\']');
$bathroomData = $bathroomData == '' || $bathroomData == null ?
                array(
                    array(
                        'iWent' => '',
                        'at' => ''
                    )
                ) :
                $bathroomData;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Child Report</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/children.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/clockpicker.css'); ?>">
</head>
<body>
    <div id="child-name">
        <a id="back-button" class="back-dark" href="./manage_children.php">
            <span>&larr;</span>
        </a>
        <h1><?php echo $name; ?></h1>
        <div id="complete-button" onclick="saveReport()">
            <p>Save</p>
        </div>
    </div>
    <div id="info-child-main" class="container-fluid">
        <div class="col-md-3 col-sm-1"></div>
        <div class="col-md-6 col-sm-10">
            <form method="POST" enctype="multipart/form-data" id="child-report">
                <div id="bathroom" class="input-section">
                    <p>Bathroom</p>
                    <?php $count = 0; foreach ($bathroomData as $value):?>
                    <div class="group">
                        <div class="one-info">
                            <?php if ($count > 0): ?>
                            <br />
                            <?php endif; ?>
                            <label for="i-went[]">I went:</label>
                            <select name="i-went[]">
                                <option <?php if ($value['iWent'] == '') {echo 'selected="selected"';} ?> value="">&lt;select&gt;</option>
                                <option <?php if ($value['iWent'] == 'Wet') {echo 'selected="selected"';} ?> value="Wet">Wet</option>
                                <option <?php if ($value['iWent'] == 'Dry') {echo 'selected="selected"';} ?> value="Dry">Dry</option>
                                <option <?php if ($value['iWent'] == 'Peed') {echo 'selected="selected"';} ?> value="Peed">Peed</option>
                                <option <?php if ($value['iWent'] == 'BM') {echo 'selected="selected"';} ?> value="BM">BM</option>
                                <option <?php if ($value['iWent'] == 'LS') {echo 'selected="selected"';} ?> value="LS">LS</option>
                            </select>
                            <br />
                        </div>
                        <div class="one-info">
                            <label for="i-went-time[]">At:</label>
                            <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                <input placeholder="00:00" type="text" name="i-went-time[]" class="form-control" value="<?php echo $value['at']; ?>" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php $count++; endforeach; ?>
                    <br id="bathroom-append" /><div class="add-details-button" onclick="addBathroom()">
                        <div>
                            <p>Add</p>
                        </div>
                    </div><br />
                    <br /><div class="remove-details-button" onclick="removeBathroom()">
                        <div>
                            <p>Remove</p>
                        </div>
                    </div><br /><br />
                </div>
                <div class="input-section">
                    <p>Meals</p>
                    <div class="one-info">
                        <label for="breakfast">Breakfast:</label>
                        <input placeholder="cereals" type="text" name="breakfast" value="<?php echo GetSaved('[\'meals\'][\'breakfast\']'); ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="breakfast-amount">Amount:</label>
                        <select name="breakfast-amount">
                            <option <?php if (GetSaved('[\'meals\'][\'breakfast-amount\']') == '') {echo 'selected="selected"';} ?> value="">&lt;select&gt;</option>
                            <option <?php if (GetSaved('[\'meals\'][\'breakfast-amount\']') == 'None') {echo 'selected="selected"';} ?> value="None">None</option>
                            <option <?php if (GetSaved('[\'meals\'][\'breakfast-amount\']') == 'Some') {echo 'selected="selected"';} ?> value="Some">Some</option>
                            <option <?php if (GetSaved('[\'meals\'][\'breakfast-amount\']') == 'Most') {echo 'selected="selected"';} ?> value="Most">Most</option>
                            <option <?php if (GetSaved('[\'meals\'][\'breakfast-amount\']') == 'All') {echo 'selected="selected"';} ?> value="All">All</option>
                        </select><br /><br />
                    </div>
                    <div class="one-info">
                        <label for="lunch">Lunch:</label>
                        <?php { $value = GetSaved('[\'meals\'][\'lunch\']'); ?>
                        <select name="lunch">
                            <option <?php if ($value == '') {echo 'selected="selected"';} ?> selected="selected" value="">&lt;select lunch&gt;</option>
                            <option <?php if ($value == 'Parent provided') {echo 'selected="selected"';} ?> value="Parent provided">Parent provided</option>
                            <option <?php if ($value == 'Option here') {echo 'selected="selected"';} ?> value="Option here">Option here</option>
                        </select>
                        <?php } ?><br />
                    </div>
                    <div class="one-info">
                        <label for="lunch-amount">Amount:</label>
                        <select name="lunch-amount">
                            <option <?php if (GetSaved('[\'meals\'][\'lunch-amount\']') == '') {echo 'selected="selected"';} ?> value="">&lt;select&gt;</option>
                            <option <?php if (GetSaved('[\'meals\'][\'lunch-amount\']') == 'None') {echo 'selected="selected"';} ?> value="None">None</option>
                            <option <?php if (GetSaved('[\'meals\'][\'lunch-amount\']') == 'Some') {echo 'selected="selected"';} ?> value="Some">Some</option>
                            <option <?php if (GetSaved('[\'meals\'][\'lunch-amount\']') == 'Most') {echo 'selected="selected"';} ?> value="Most">Most</option>
                            <option <?php if (GetSaved('[\'meals\'][\'lunch-amount\']') == 'All') {echo 'selected="selected"';} ?> value="All">All</option>
                        </select><br /><br />
                    </div>
                    <div class="one-info">
                        <label for="snack">Snack:</label>
                        <input placeholder="banana" type="text" name="snack" value="<?php echo GetSaved('[\'meals\'][\'snack\']'); ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="snack-amount">Amount:</label>
                        <select name="snack-amount">
                            <option <?php if (GetSaved('[\'meals\'][\'snack-amount\']') == '') {echo 'selected="selected"';} ?> value="">&lt;select&gt;</option>
                            <option <?php if (GetSaved('[\'meals\'][\'snack-amount\']') == 'None') {echo 'selected="selected"';} ?> value="None">None</option>
                            <option <?php if (GetSaved('[\'meals\'][\'snack-amount\']') == 'Some') {echo 'selected="selected"';} ?> value="Some">Some</option>
                            <option <?php if (GetSaved('[\'meals\'][\'snack-amount\']') == 'Most') {echo 'selected="selected"';} ?> value="Most">Most</option>
                            <option <?php if (GetSaved('[\'meals\'][\'snack-amount\']') == 'All') {echo 'selected="selected"';} ?> value="All">All</option>
                        </select><br /><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Nap</p>
                    <div class="one-info">
                        <label for="nap-from">From:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input placeholder="00:00" type="text" name="nap-from" class="form-control" value="<?php echo GetSaved('[\'nap\'][\'from\']') ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="one-info">
                        <label for="nap-to">To:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input placeholder="00:00" type="text" name="nap-to" class="form-control" value="<?php echo GetSaved('[\'nap\'][\'to\']') ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="input-section">
                    <p>Feeling</p>
                    <div class="one-info">
                        <label for="feeling-i-was">I was:</label>
                        <?php { $value = GetSaved('[\'feeling\'][\'iWas\']'); ?>
                        <select name="feeling-i-was">
                            <option <?php if ($value == '') {echo 'selected="selected"';} ?> selected="selected" value="">&lt;select feeling&gt;</option>
                            <option <?php if ($value == 'Happy') {echo 'selected="selected"';} ?> value="Happy">Happy</option>
                            <option <?php if ($value == 'Sad') {echo 'selected="selected"';} ?> value="Sad">Sad</option>
                            <option <?php if ($value == 'Cool') {echo 'selected="selected"';} ?> value="Cool">Cool</option>
                            <option <?php if ($value == 'Excited') {echo 'selected="selected"';} ?> value="Excited">Excited</option>
                        </select>
                        <?php } ?><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Highlights / new discoveries</p>
                    <div class="one-info highlight-input">
                        <label for="highlight">Highlight:</label>
                        <input placeholder="new friend" type="text" name="highlight" value="<?php echo GetSaved('[\'highlights\']') ?>" /><br />
                    </div>
                </div>
                <div class="input-section" id="changed-clothes-section">
                    <p>Changed clothes</p>
                    <?php $values = GetSaved('[\'changedClothes\']'); if (sizeof($values) == 0 || $values == ''): ?>
                    <div class="group">
                        <div class="one-info">
                            <label for="changed-clothes-details[]">Details:</label>
                            <input placeholder="changed shirt" type="text" name="changed-clothes-details[]" /><br />
                        </div>
                    </div>
                    <?php endif; if (sizeof($values) > 0 && $values != ''): ?>
                        <?php foreach ($values as $value): ?>
                        <div class="group">
                            <div class="one-info">
                                <label for="changed-clothes-details[]">Details:</label>
                                <input placeholder="changed shirt" type="text" name="changed-clothes-details[]" value="<?php echo $value; ?>" /><br />
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <br id="changed-clothes-append" /><div class="add-details-button" onclick="addDetails()">
                        <div>
                            <p>Add</p>
                        </div>
                    </div><br />
                    <br /><div class="remove-details-button" onclick="removeDetails()">
                        <div>
                            <p>Remove</p>
                        </div>
                    </div><br /><br />
                </div>
                <div id="occurence" class="input-section">
                    <p>Occurence</p>
                    <div class="one-info">
                        <label for="occurence">Yes / no:</label>
                        <input type="checkbox" name="occurence" <?php if (GetSaved('[\'occurence\']') == true) {echo 'checked="checked"';}; ?> /><br />
                    </div>
                </div>
                <div id="needs" class="input-section">
                    <p>Needs</p>
                    <div class="one-info">
                        <label for="diapers">Diapers:</label>
                        <input type="checkbox" name="diapers" <?php if (GetSaved('[\'needs\'][\'diapers\']') == true) {echo 'checked="checked"';}; ?> /><br />
                    </div>
                    <div class="one-info">
                        <label for="wipes">Wipes:</label>
                        <input type="checkbox" name="wipes" <?php if (GetSaved('[\'needs\'][\'wipes\']') == true) {echo 'checked="checked"';}; ?> /><br />
                    </div>
                    <div class="one-info">
                        <label for="shirt">Shirt:</label>
                        <input type="checkbox" name="shirt" <?php if (GetSaved('[\'needs\'][\'shirt\']') == true) {echo 'checked="checked"';}; ?> /><br />
                    </div>
                    <div class="one-info">
                        <label for="pants">Pants:</label>
                        <input type="checkbox" name="pants" <?php if (GetSaved('[\'needs\'][\'pants\']') == true) {echo 'checked="checked"';}; ?> /><br />
                    </div>
                    <div class="one-info">
                        <label for="underwear">Underwear:</label>
                        <input type="checkbox" name="underwear" <?php if (GetSaved('[\'needs\'][\'underwear\']') == true) {echo 'checked="checked"';}; ?> /><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Medicine</p>
                    <div class="one-info">
                        <label for="medicine-given-by">Given by:</label>
                        <input placeholder="Sarah" type="text" name="medicine-given-by" value="<?php echo GetSaved('[\'medicine\'][\'givenBy\']') ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="medicine-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input placeholder="00:00" type="text" name="medicine-given-at" class="form-control" value="<?php echo GetSaved('[\'medicine\'][\'givenAt\']') ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="input-section">
                    <p>Sunscreen</p>
                    <div class="one-info">
                        <label for="sunscreen-given-by">Given by:</label>
                        <input placeholder="Sarah" type="text" name="sunscreen-given-by" value="<?php echo GetSaved('[\'sunscreen\'][\'givenBy\']') ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="sunscreen-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input placeholder="00:00" type="text" name="sunscreen-given-at" class="form-control" value="<?php echo GetSaved('[\'sunscreen\'][\'givenAt\']') ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="input-section">
                    <p>Insect repellent</p>
                    <div class="one-info">
                        <label for="insect-repellent-given-by">Given by:</label>
                        <input placeholder="Sarah" type="text" name="insect-repellent-given-by" value="<?php echo GetSaved('[\'insectRepellent\'][\'givenBy\']') ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="insect-repellent-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input placeholder="00:00" type="text" name="insect-repellent-given-at" class="form-control" value="<?php echo GetSaved('[\'insectRepellent\'][\'givenAt\']') ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3 col-sm-1"></div>
    </div>
    <script>
        const IPADDRESS = "<?php echo $IPADDRESS ?>";
        const CHILD_ID = <?php echo $id ?>;
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/clockpicker.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/admin/children.js'); ?>"></script>
    <script type="text/javascript">
        $('.clockpicker').clockpicker();

        $(':input').click(function(event) {
            event.stopPropagation();
        });

        $(document).on('click', '#bathroom.input-section .group', function() {
            if ($(this).hasClass('remSelect')) {
                $(this).removeClass('remSelect');
            } else {
                if (!($('#bathroom.input-section .group.remSelect').length >= $('#bathroom.input-section .group').length - 1))
                    $(this).addClass('remSelect');
            }
        });

        $(document).on('click', '#changed-clothes-section .group', function() {
            if ($(this).hasClass('remSelect')) {
                $(this).removeClass('remSelect');
            } else {
                if (!($('#changed-clothes-section .group.remSelect').length >= $('#changed-clothes-section .group').length - 1))
                    $(this).addClass('remSelect');
            }
        });
    </script>
</body>
</html>