<?php

require('../ipconfig.php');

if (!isset($_REQUEST['id'])) {
    die('Missing id parameter');
}

$id = $_REQUEST['id'];

$nameRequest = file_get_contents("http://$IPADDRESS/query.php?action=get_name&data=".urlencode("{\"id\": $id}"));

$name = json_decode($nameRequest)->data->name;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Child Record</title>
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/admin/children.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/clockpicker.css'); ?>">
</head>
<body>
    <div id="child-name">
        <h1><?php echo $name; ?></h1>
        <div id="complete-button">
            <p>Complete</p>
        </div>
    </div>
    <div id="info-child-main" class="container-fluid">
        <div class="col-md-3 col-sm-1"></div>
        <div class="col-md-6 col-sm-10">
            <form method="POST" enctype="multipart/form-data" id="info-child">
                <div class="input-section">
                    <p>Bathroom</p>
                    <div class="one-info">
                        <label for="i-went">I went:</label>
                        <select name="i-went">
                            <option selected="selected" value="">&lt;select&gt;</option>
                            <option value="Number 1">Wet</option>
                            <option value="Number 2">Dry</option>
                            <option value="Number 2">Peed</option>
                            <option value="Both">Both</option>
                        </select><br />
                    </div>
                    <div class="one-info">
                        <label for="i-went-time">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input placeholder="00:00" type="text" name="i-went-time" class="form-control" />
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
                        <input type="text" name="breakfast" /><br />
                    </div>
                    <div class="one-info">
                        <label for="lunch">Lunch:</label>
                        <select name="lunch">
                            <option selected="selected" value="">&lt;select lunch&gt;</option>
                            <option value="Parent provided">Parent provided</option>
                            <option value="Option here">Option here</option>
                        </select><br />
                    </div>
                    <div class="one-info">
                        <label for="snack">Snack:</label>
                        <input type="text" name="snack" /><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Nap</p>
                    <div class="one-info">
                        <label for="nap-from">From:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="i-went-time" class="form-control" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="one-info">
                        <label for="nap-to">To:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="i-went-time" class="form-control" />
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
                        <select name="snack">
                            <option selected="selected" value="">&lt;select feeling&gt;</option>
                            <option value="Happy">Happy</option>
                            <option value="Sad">Sad</option>
                            <option value="Cool">Cool</option>
                            <option value="Excited">Excited</option>
                        </select><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Highlights / new discoveries</p>
                    <div class="one-info highlight-input">
                        <label for="highlight">Highlight:</label>
                        <input type="text" name="highlight" /><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Changed clothes</p>
                    <div class="one-info changed-clothes-detail">
                        <label for="changed-clothes-details[]">Details:</label>
                        <input type="text" name="changed-clothes-details[]" /><br />
                    </div>
                    <br /><div id="add-highlight-button" onclick="addDetails()">
                        <div>
                            <p>Add details</p>
                        </div>
                    </div><br /><br />
                </div>
                <div id="occurence" class="input-section">
                    <p>Occurence</p>
                    <div class="one-info">
                        <label for="occurence">Yes / no:</label>
                        <input type="checkbox" name="occurence" /><br />
                    </div>
                </div>
                <div class="input-section">
                    <p>Medicine</p>
                    <div class="one-info">
                        <label for="medicine-given-by">Given by:</label>
                        <input type="text" name="medicine-given-by" /><br />
                    </div>
                    <div class="one-info">
                        <label for="medicine-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="medicine-given-at" class="form-control" />
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
                        <input type="text" name="sunscreen-given-by" /><br />
                    </div>
                    <div class="one-info">
                        <label for="sunscreen-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="sunscreen-given-at" class="form-control" />
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
                        <input type="text" name="insect-repellent-given-by" /><br />
                    </div>
                    <div class="one-info">
                        <label for="insect-repellent-given-at">At:</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input type="text" name="insect-repellent-given-at" class="form-control" />
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
    </script>
</body>
</html>