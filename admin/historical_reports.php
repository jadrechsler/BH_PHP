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

if (!isset($_REQUEST['report'])) {
    die('Missing report parameter');
}

if (!isset($_REQUEST['date'])) {
    die('Missing date parameter');
}

$report = json_decode($_REQUEST['report'], true);
$date = $_REQUEST['date'];

$nameRequest = file_get_contents("http://$IPADDRESS/query.php?action=get_name&data=".urlencode("{\"id\": $id}"));

$name = json_decode($nameRequest)->data->name;

function GetSaved($spec) {
    global $report;

    $exec = '
        if (isset($report'.$spec.')) {
            return $report'.$spec.';
        }
        return "";
    ';

    return eval($exec);
}

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
        <a id="back-button" class="back-dark" href="/admin/manage_children.php">
            <span>&larr;</span>
        </a>
        <h1><?php echo $name; ?></h1>
        <div id="complete-button" onclick="emailHistoricalReport()">
            <p>Email</p>
        </div>
    </div>
    <div id="info-child-main" class="container-fluid">
        <div class="col-md-3 col-sm-1"></div>
        <div class="col-md-6 col-sm-10">
            <form method="POST" enctype="multipart/form-data" id="child-report">
                <div class="input-section">
                    <p>Bathroom</p>
                    <div class="one-info">
                        <label for="i-went">I went:</label>
                        <?php { $value = GetSaved('[\'bathroom\'][\'iWent\']'); ?>
                        <select name="i-went">
                            <option <?php if ($value == '') {echo 'selected="selected"';} ?> value="">&lt;select&gt;</option>
                            <option <?php if ($value == 'Wet') {echo 'selected="selected"';} ?> value="Wet">Wet</option>
                            <option <?php if ($value == 'Dry') {echo 'selected="selected"';} ?> value="Dry">Dry</option>
                            <option <?php if ($value == 'Peed') {echo 'selected="selected"';} ?> value="Peed">Peed</option>
                            <option <?php if ($value == 'BM') {echo 'selected="selected"';} ?> value="BM">BM</option>
                            <option <?php if ($value == 'LS') {echo 'selected="selected"';} ?> value="LS">LS</option>
                        </select>
                        <?php } ?>
                        <br />
                    </div>
                    <div class="one-info">
                        <label for="i-went-time">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="i-went-time" class="form-control" value="<?php echo GetSaved('[\'bathroom\'][\'at\']'); ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="input-section">
                    <p>Meals</p>
                    <div class="one-info">
                        <label for="breakfast">Breakfast:</label>
                        <input type="text" name="breakfast" value="<?php echo GetSaved('[\'meals\'][\'breakfast\']'); ?>" /><br />
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
                        <label for="snack">Snack:</label>
                        <input type="text" name="snack" value="<?php echo GetSaved('[\'meals\'][\'snack\']'); ?>" /><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Nap</p>
                    <div class="one-info">
                        <label for="nap-from">From:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="nap-from" class="form-control" value="<?php echo GetSaved('[\'nap\'][\'from\']') ?>" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="one-info">
                        <label for="nap-to">To:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="nap-to" class="form-control" value="<?php echo GetSaved('[\'nap\'][\'to\']') ?>" />
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
                        <input type="text" name="highlight" value="<?php echo GetSaved('[\'highlights\']') ?>" /><br />
                    </div>
                </div>
                <div class="input-section" id="changed-clothes-section">
                    <p>Changed clothes</p>
                    <?php $values = GetSaved('[\'changedClothes\']'); if (sizeof($values) == 0 || $values == ''): ?>
                    <div class="one-info">
                        <label for="changed-clothes-details[]">Details:</label>
                        <input type="text" name="changed-clothes-details[]" /><br />
                    </div>
                    <?php endif; if (sizeof($values) > 0 && $values != ''): ?>
                        <?php foreach ($values as $value): ?>
                        <div class="one-info">
                            <label for="changed-clothes-details[]">Details:</label>
                            <input type="text" name="changed-clothes-details[]" value="<?php echo $value; ?>" /><br />
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
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
                        <input type="text" name="medicine-given-by" value="<?php echo GetSaved('[\'medicine\'][\'givenBy\']') ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="medicine-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="medicine-given-at" class="form-control" value="<?php echo GetSaved('[\'medicine\'][\'givenAt\']') ?>" />
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
                        <input type="text" name="sunscreen-given-by" value="<?php echo GetSaved('[\'sunscreen\'][\'givenBy\']') ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="sunscreen-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="sunscreen-given-at" class="form-control" value="<?php echo GetSaved('[\'sunscreen\'][\'givenAt\']') ?>" />
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
                        <input type="text" name="insect-repellent-given-by" value="<?php echo GetSaved('[\'insectRepellent\'][\'givenBy\']') ?>" /><br />
                    </div>
                    <div class="one-info">
                        <label for="insect-repellent-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="insect-repellent-given-at" class="form-control" value="<?php echo GetSaved('[\'insectRepellent\'][\'givenAt\']') ?>" />
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
        const currentReport = JSON.parse('<?php echo $_REQUEST['report']; ?>');
        const currentName = '<?php echo $name; ?>';
        const pastDate = '<?php echo $date ?>';
    </script>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/clockpicker.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/admin/children.js'); ?>"></script>
    <script type="text/javascript">
        $(':input').prop('disabled', true);
    </script>
</body>
</html>