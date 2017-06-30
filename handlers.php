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

    $first = mysqli_num_rows(mysqli_query($conn, 'SELECT id FROM users WHERE id = 2'));
    $second = mysqli_num_rows(mysqli_query($conn, 'SELECT id FROM users WHERE id = 3'));

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

    $first = mysqli_num_rows(mysqli_query($conn, 'SELECT id FROM users WHERE id = 1'));

    if ($first == 0) {
        return 1;
    } else {
        respond(false, null, 'Reached Maximum Admin Capacity');
    }
}

function ensureInfoDateExists($date) {
    global $conn;

    $today = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM info WHERE date = '$date'"));

    if ($today < 1) {
        mysqli_query($conn, "INSERT INTO info(date, reports) VALUES('$date', '{}')");
    }
}

function deleteUser() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters: id');
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
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

function makeReport() {
    global $conn;
    global $data;
    global $today;

    try {
        $reports = $data->reports;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameter: reports');
    }

    ensureInfoDateExists($today);

    $stmt = $conn->prepare("UPDATE info SET reports = ? WHERE date = '$today'");
    $stmt->bind_param('s', $reports);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

function getReport() {
    global $conn;
    global $data;
    global $today;

    ensureInfoDateExists($today);

    $reports = mysqli_query($conn, "SELECT reports FROM info WHERE date = '$today'");

    $data = array(
        'reports' => mysqli_fetch_row($reports)
    );

    respond(true, json_encode($data));
}

function changeEmail() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
        $email = $data->email;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param('si', $email, $id);

    $stmt->execute();

    respond(true, null);
}

function changePin() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
        $pin = $data->pin;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("UPDATE users SET pin = ? WHERE id = ?");
    $stmt->bind_param('ii', $pin, $id);

    $stmt->execute();

    respond(true, null);
}

function changeName() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
        $name = $data->name;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt->bind_param('si', $name, $id);

    $stmt->execute();

    respond(true, null);
}

function getChildren() {
    global $conn;
    global $data;

    $children = mysqli_query($conn, "SELECT id, name, present FROM users WHERE id > 999");

    $list = array();

    foreach ($children as $c) {
        $child = array(
            'id' => $c['id'],
            'name' => $c['name'],
            'present' => $c['present']
        );

        array_push($list, $child);
    }

    $data = array(
        'children' => $list
    );

    respond(true, json_encode($data));
}

?>