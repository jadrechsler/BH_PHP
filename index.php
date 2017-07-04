<?php
    $fetchChildren = file_get_contents('http://localhost/query.php?action=get_children&data={}');

    $children = json_decode($fetchChildren)->data->children;

    function getChildrenArray() {
        global $children;

        return json_encode($children);
    }

    function makeRowItem($child) {
        $name = $child->name;

        $html = '<div class="col-md-3 row-item">';

        $html .= '<img src="img/face.jpg" onclick="overlay_show('.$child->id.', \''.$name.'\')">';

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
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
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
				<p id="name">Bobby Name</p>
				<div id="view-report" class="option" onclick="report_show()">		
					<p>View report</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="exit"><span onclick="overlay_hide()">&#x2573</span></div>
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
                                        <p>Happy</p>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid bottom">
                                <div id="i-slept" class="col-md-12 report-container">
                                    <div class="content">
                                        <h1>I slept</h1>
                                        <p>10:30 - 12:30</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="i-went" class="col-md-6 report-container">
                            <div class="content">
                                <h1>I went</h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid bottom">
                        <div id="i-ate" class="col-md-12 report-container">
                            <div class="content">
                                <h1>I ate</h1>
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
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid bottom">
                        <div class="col-md-6 report-container">
                            <div id="i-need" class="content">
                                <h1>I need</h1>
                            </div>
                        </div>
                        <div id="changed-clothes" class="col-md-6 report-container">
                            <div class="content">
                                <h1>Changed clothes</h1>
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
                        <button>EMAIL</button>
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
	<script type="text/javascript">
		var children = JSON.parse('<?php echo getChildrenArray(); ?>');
	</script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/query.js"></script>
	<script src="js/main.js"></script>
</html>