<?php

require_once('ipconfig.php');

$fetchChildren = file_get_contents('http://'.$IPADDRESS.'/query.php?action=get_children&data={}');

$children = json_decode($fetchChildren)->data->children;

for ($x = 0; $x < sizeof($children); $x++) {
    if ($children[$x]->teacher == 0) {
        unset($children[$x]);
    }
}

function getChildrenArray() {
    global $children;

    return json_encode($children);
}

function makeRowItem($child) {
    $name = $child->name;

    $html = '<div childId="'.$child->id.'" childName="'.$child->name.'" class="col-md-3 row-item">';

    $html .= '<img src="'.AddrLink("img/children/$child->id.jpg").'" onclick="//overlay_show('.$child->id.', \''.$name.'\')" />';

    $html .= '<figcaption>'.$name.'</figcaption>';

    $html .= '</div>';

    return $html;
}

function makeRow($list) {
    $html = '<div class="container-fluid row">';

    foreach ($list as $child) {
        $html .= makeRowItem($child);
    }

    $html .= '</div>';

    return $html;
}

function makeRowSet($list) {
    $html = '';

    foreach (array_chunk($list, 5) as $row) {
        $html .= makeRow($row);
    }

    return $html;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Class 1</title>
	<link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/bootstrap-theme.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo AddrLink('css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo AddrLink('css/jquery.numpad.css'); ?>">
</head>
<body>
    <div id="brightness"></div>
	<div id="overlay">
		<div class="container-fluid options">
			<div class="col-md-4">
				<div id="check-in-out" class="option">
					<p>Check in/out</p>
				</div>
			</div>
			<div class="col-md-4">
				<p id="name"></p>
				<div id="view-report" class="option" onclick="report_show();">		
					<p>View report</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="exit"><span onclick="overlay_hide();">&#x2573</span></div>
				<div id="change-pin" class="option">
					<p>Change pin</p>
				</div>
			</div>
		</div>
	</div>

<div id="report">
        <div class="report-main">
            <div class="container-fluid">
                <div class="col-md-6 side left">
                    <div class="container-fluid top">
                        <div class="col-md-6 double">
                            <div class="container-fluid top">
                                <div id="i-was" class="col-md-12 report-container">
                                    <div class="content">
                                        <h1>I was</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid bottom">
                                <div id="i-slept" class="col-md-12 report-container">
                                    <div class="content">
                                        <h1>I slept</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="i-went" class="col-md-6 report-container">
                            <div class="content">
                                <h1>I went</h1>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid bottom">
                        <div id="i-ate" class="col-md-12 report-container">
                            <div class="content">
                                <h1>I ate</h1>
                                <ul></ul>
                            </div>
                        </div>
                    </div>
                </div>            
                <div class="col-md-6 side right">
                    <div class="container-fluid top">
                        <div id="occurence" class="col-md-12 report-container">
                            <div class="content">
                                <h1>Please see the Teacher</h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid middle">
                        <div id="highlights" class="col-md-12 report-container">
                            <div class="content">
                                <h1>Highlights/ new discoveries</h1>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid bottom">
                        <div class="col-md-6 report-container">
                            <div id="i-need" class="content">
                                <h1>I need</h1>
                                <p></p>
                            </div>
                        </div>
                        <div id="changed-clothes" class="col-md-6 report-container">
                            <div class="content">
                                <h1>Changed clothes</h1>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="report-bottom">
            <div class="container-fluid">
                <div class="col-md-3">
                	<div id="exit-report">
                		<button onclick="report_hide()">EXIT</button>
                	</div>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-3">
                    <div id="email">
                        <button onclick="emailReport();">EMAIL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div id="content">
		<div id="top" class="container-fluid">
			<div id="test" class="col-md-4">
				<img id="logo" src="img/logo.png" alt="logo" height="35%" width="35%">
			</div>
			<div class="col-md-4">
				<h1 id="room-name">Room 1</h1>
			</div>
			<div class="col-md-4"></div>
		</div>
		<div id="main" class="container-fluid">
			<?php echo makeRowSet($children) ?>
		</div>
	</div>
    <script src="<?php echo AddrLink('js/jquery-3.2.1.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo AddrLink('js/jquery.numpad.js'); ?>"></script>
	<script type="text/javascript">
		var children = JSON.parse('<?php echo getChildrenArray(); ?>');

        const IPADDRESS = "<?php echo $IPADDRESS ?>";

        $.fn.numpad.defaults.gridTpl = '<table class="table modal-content"></table>';
        $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in"></div>';
        $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" />';
        $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-default"></button>';
        $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn keypad-action-button" style="width: 100%;"></button>';
        $.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.done').addClass('keypad-done-button');};


        $(document).ready(function() {
            $('.row-item').each(function() {
                $(this).numpad({
                    displayTpl: '<input class="form-control" type="password" />',
                    hidePlusMinusButton: true,
                    hideDecimalButton: true,
                    textDone: 'Enter',
                    childId: $(this).attr('childId'),
                    childName: $(this).attr('childName')
                });
            });

            $('#change-pin').each(function() {
                $(this).numpad({
                    displayTpl: '<input placeholder="new pin min length of 4" class="form-control" type="password" />',
                    hidePlusMinusButton: true,
                    hideDecimalButton: true,
                    textDone: 'Change',
                    changePin: true
                });
            });
        });
	</script>
    <script src="<?php echo AddrLink('js/query.js'); ?>"></script>
	<script src="<?php echo AddrLink('js/main.js'); ?>"></script>
</html>