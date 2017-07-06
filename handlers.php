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

    $id = mysqli_fetch_assoc($nextId)['carer'];

    // Increment Id
    if ($id == 975) {
        mysqli_query($conn, 'UPDATE nextid SET carer = 500');
    } else {
        mysqli_query($conn, 'UPDATE nextid SET carer = carer + 1');
    }

    return $id;
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
        mysqli_query($conn, "INSERT INTO info(date, reports) VALUES('$date', '{\"0\": {}}')");
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
    global $data;

    $id = nextChildId();

    try {
        $teacher = $data->teacher;
        $carers = json_encode($data->carers);
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("INSERT INTO users (id, name, pin, teacher, carers) VALUES ($id, ?, ?, ?, ?)");
    $stmt->bind_param('ssis', $name, $pin, $teacher, $carers);

    $stmt->execute();

    $data = array(
        'id' => $id
    );

    respond(true, $data);

    $stmt->close();
    $conn->close();
}

function newCarer($name, $relation, $email) {
    global $conn;

    $id = nextCarerId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, relation, email) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('sss', $name, $relation, $email);

    $stmt->execute();

    $data = array(
        'id' => $id
    );

    respond(true, $data);

    $stmt->close();
    $conn->close();
}

function newFloater($name, $email, $pin) {
    global $conn;

    $id = newFloaterId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, email, pin) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('sss', $name, $email, $pin);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

function newTeacher($name, $email, $pin) {
    global $conn;

    $id = newTeacherrId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, email, pin) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('sss', $name, $email, $pin);

    $stmt->execute();

    respond(true, null);

    $stmt->close();
    $conn->close();
}

function newAdmin($name, $email, $pin) {
    global $conn;

    $id = newAdminId();

    $stmt = $conn->prepare("INSERT INTO users (id, name, email, pin) VALUES ($id, ?, ?, ?)");
    $stmt->bind_param('sss', $name, $email, $pin);

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

    $stmt->close();
    $conn->close();

    respond(true, null);
}

function getReport() {
    global $conn;
    global $data;
    global $today;

    ensureInfoDateExists($today);

    $reports = mysqli_query($conn, "SELECT reports FROM info WHERE date = '$today'");

    $data = array(
        'reports' => json_decode(mysqli_fetch_row($reports)[0])
    );

    respond(true, $data);
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

    $stmt->close();
    $conn->close();

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
    $stmt->bind_param('si', $pin, $id);

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

    respond(true, $data);
}

function changePresence() {
    global $conn;
    global $data;

    try {
        $present = $data->presence;
        $id = $data->id;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("UPDATE users SET present = ? WHERE id = ?");
    $stmt->bind_param('ii', $present, $id);

    $stmt->execute();

    $stmt->close();
    $conn->close();

    respond(true, null);
}

function checkPin() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
        $pin = $data->pin;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("SELECT pin FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);

    $stmt->execute();

    $truePin = $stmt->get_result()->fetch_array()['pin'];

    $stmt->close();
    $conn->close();

    if ($truePin == $pin) {
        // If pin is correct
        respond(true, null);
    } else {
        // If pin is incorrect
        respond(false, null);
    }
}

function getName() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);

    $stmt->execute();

    $name = $stmt->get_result()->fetch_array()['name'];

    $stmt->close();
    $conn->close();

    $data = array(
        'name' => $name
    );

    respond(true, $data);
}

function getUserInfo() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);

    $stmt->execute();

    $info = $stmt->get_result()->fetch_array();

    $stmt->close();
    $conn->close();

    $data = array(
        'id' => $id,
        'name' => $info['name'],
        'relation' => $info['relation'],
        'email' => $info['email'],
        'pin' => $info['pin'],
        'carers' => $info['carers'],
        'teacher' => $info['teacher'],
        'present' => $info['present']
    );

    respond(true, $data);
} 

?>