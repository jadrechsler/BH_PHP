<?php
    if (isset($_POST['pin']) && isset($_POST['id'])) {
        $action = 'check_pin';

        $data = array(
            'id' => $_POST['id'],
            'pin' => $_POST['pin']
        );

        $check = file_get_contents('http://localhost:/query.php?action='.$action.'&data='.json_encode($data));

        if (json_decode($check)->success) {
            // TODO: set sessions
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <form style="visibility: hidden;" method="POST" action="/login.php">
        <input type="text" >
    </form>
    <div class="container-fluid">
        <div class="col-md-3 col-sm-2"></div>
        <div class="col-md-6 col-sm-8 panel-container">
            <div id="panel">
                <div>
                <h1>Login</h1>
                    <form>
                        <select name="user" id="user">
                            <option selected="selected" value="">&lt;select user&gt;</option>
                            <option value="admin">Admin</option>
                            <option value="teacher_1">teacher_1</option>
                            <option value="teacher_2">teacher_2</option>
                            <option value="floater">floater</option>
                        </select><br />
                        <input type="password" name="pin" id="pin" placeholder="enter pin" pattern="[0-9]{6}" maxlength="6" />
                    </form>
                    <button id="login-button" onclick="login()">Sign in</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-2"></div>
    </div>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/query.js"></script>
    <script src="js/login.js"></script>
</body>
</html>