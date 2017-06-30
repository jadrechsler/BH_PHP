<?php

require('db.php');
require('id.php');

function respond($success, $data, $error = null) {
    header('Content-Type: application/json');

    $payload = array(
        'success' => $success,
        'data' => $data,
        'error' => $error
    );

    echo json_encode($payload);

    exit();
}

// Available Actions
const NEW_USER = 'new_user';

// Types of Users
const ADMIN = 'admin';
const TEACHER = 'teacher';
const FLOATER = 'floater';
const CARER = 'carer';
const CHILD = 'child';

if (empty($_REQUEST['action'])) {
    respond(false, null, 'Missing Action Parameter');
} elseif (empty($_REQUEST['data'])) {
    respond(false, null, 'Missing Data Parameter');
}

$action = strtolower($_REQUEST['action']);
$data = json_decode($_REQUEST['data']);

// Route the requested action
switch ($action) {
    case NEW_USER:
        newUser();
        break;
    default:
        respond(false, null, 'Invalid Action');
        break;
}

function newUser() {
    global $data;

    $pin = $data->pin;

    switch ($data->type) {
        case CHILD:
            try {
                $name = strtolower($data->name);
                $pin = strtolower($data->pin);
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newChild($name, $pin);
            break;
        case CARER:
            try {
                $name = strtolower($data->name);
                $relation = strtolower($data->relation);
                $email = strtolower($data->email);
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newCarer($name, $relation, $email);
            break;
        case FLOATER:
            try {
                $name = strtolower($data->name);
                $email = strtolower($data->email);
                $pin = $data->pin;
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newFloater($name, $email, $pin);
            break;
        case TEACHER:
            try {
                $name = strtolower($data->name);
                $email = strtolower($data->email);
                $pin = $data->pin;
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newTeacher($name, $email, $pin);
            break;
        case ADMIN:
            try {
                $name = strtolower($data->name);
                $email = strtolower($data->email);
                $pin = $data->pin;
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newAdmin($name, $email, $pin);
            break;
        default:
            respond(false, null, 'Invalid New User Type');
            break;
    }

}

function newChild($name, $pin) {
    global $conn;

    $id = nextChildId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, pin) VALUES ($id, ?, ?)");
    $stmt->bind_param('si', $name, $pin);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

function newCarer($name, $relation, $email) {
    global $conn;

    $id = newCarerId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, relation, email) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('sss', $name, $relation, $email);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

function newFloater($name, $email, $pin) {
    global $conn;

    $id = newFloaterId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, email, pin) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('ssi', $name, $email, $pin);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

function newTeacher($name, $email, $pin) {
    global $conn;

    $id = newTeacherrId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, email, pin) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('ssi', $name, $email, $pin);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

function newAdmin($name, $email, $pin) {
    global $conn;

    $id = newAdminId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, email, pin) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('ssi', $name, $email, $pin);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

?>