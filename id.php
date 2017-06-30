<?php

function nextChildId() {
    global $conn;

    $nextId = mysqli_query($conn, 'SELECT child FROM nextid');

    // Increment Id
    mysqli_query($conn, 'UPDATE nextid SET child = child + 1');

    $id = mysqli_fetch_assoc($nextId)['child'];

    return $id;
}

function nextCarerId() {
    global $conn;

    $nextId = mysqli_query($conn, 'SELECT carer FROM nextid');

    // Increment Id
    mysqli_query($conn, 'UPDATE nextid SET carer = carer + 1');

    return mysqli_fetch_assoc($nextId)['carer'];
}

function nextFloaterId() {
    global $conn;

    $nextId = mysqli_query($conn, 'SELECT floater FROM nextid');

    // Increment Id
    mysqli_query($conn, 'UPDATE nextid SET floater = floater + 1');

    return mysqli_fetch_assoc($nextId)['floater'];
}

function nextTeacherId() {
    global $conn;

    $first = mysql_num_rows(mysqli_query($conn, 'SELECT id FROM users WHERE id = 2'));
    $second = mysql_num_rows(mysqli_query($conn, 'SELECT id FROM users WHERE id = 3'));

    if ($first == 0) {
        return 2;
    } elseif ($second == 0) {
        return 3;
    } else {
        respond(false, null, 'Reached Maximum Teacher Capacity');
    }
}

function nextAdminId() {
    global $conn;

    $first = mysql_num_rows(mysqli_query($conn, 'SELECT id FROM users WHERE id = 1'));

    if ($first == 0) {
        return 1;
    } else {
        respond(false, null, 'Reached Maximum Admin Capacity');
    }
}

?>