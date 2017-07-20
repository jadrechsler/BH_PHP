<?php

require('db.php');
require('handlers.php');

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
const DELETE_USER = 'delete_user';
const MAKE_REPORT = 'make_report';
const GET_REPORT = 'get_report';
const CHANGE_EMAIL = 'change_email';
const CHANGE_PIN = 'change_pin';
const CHANGE_NAME = 'change_name';
const CHANGE_RELATION = 'change_relation'; // Not documented
const CHANGE_TEACHER = 'change_teacher'; // Not documented
const CHECK_PIN = 'check_pin';
const GET_CHILDREN = 'get_children';
const CHANGE_PRESENCE = 'change_presence';
const GET_NAME = 'get_name'; // Not documented
const INSERT_REPORT = 'insert_report'; // Not documented
const GET_USER_INFO = 'get_user_info'; // Not documented
const UPDATE_USER = 'update_user'; // Not documented
const DELETE_CARER = 'delete_carer'; // Not documented
const GET_PAST_REPORT = 'get_past_report'; // Not documented
const GET_LOG = 'get_log'; // Not documented

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

$today = date('d n Y');

// Route the requested action
switch ($action) {
    case NEW_USER:
        newUser();
        break;
    case DELETE_USER:
        deleteUser();
        break;
    case MAKE_REPORT:
        makeReport();
        break;
    case GET_REPORT:
        getReport();
        break;
    case CHANGE_EMAIL:
        changeEmail();
        break;
    case CHANGE_PIN:
        changePin();
        break;
    case CHANGE_NAME:
        changeName();
        break;
    case CHANGE_RELATION:
        changeRelation();
        break;
    case CHANGE_TEACHER:
        changeTeacher();
        break;
    case CHECK_PIN:
        checkPin();
        break;
    case GET_CHILDREN:
        getChildren();
        break;
    case CHANGE_PRESENCE:
        changePresence();
        break;
    case GET_NAME:
        getName();
        break;
    case INSERT_REPORT:
        insertReport();
        break;
    case GET_USER_INFO:
        getUserInfo();
        break;
    case UPDATE_USER:
        updateUser();
        break;
    case DELETE_CARER:
        deleteCarer();
        break;
    case GET_PAST_REPORT:
        getPastReport();
        break;
    case GET_LOG:
        getLog();
        break;
    default:
        respond(false, null, 'Invalid Action');
        break;
}

function newUser() {
    global $data;

    switch (strtolower($data->type)) {
        case CHILD:
            try {
                $name = $data->name;
                $pin = $data->pin;
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newChild($name, $pin);
            break;
        case CARER:
            try {
                $name = $data->name;
                $relation = $data->relation;
                $email = strtolower($data->email);
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newCarer($name, $relation, $email);
            break;
        case FLOATER:
            try {
                $name = $data->name;
                $email = strtolower($data->email);
                $pin = $data->pin;
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newFloater($name, $email, $pin);
            break;
        case TEACHER:
            try {
                $name = $data->name;
                $email = strtolower($data->email);
                $pin = $data->pin;
            } catch(Exception $e) {
                respond(false, null, 'Missing Data Parameters');
            }
            newTeacher($name, $email, $pin);
            break;
        case ADMIN:
            try {
                $name = $data->name;
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

?>