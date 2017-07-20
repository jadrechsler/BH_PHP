<?php

require_once('log.php');

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

    if (!($id >= 1000)) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);

        $stmt->execute();
    } else {
        $stmt = $conn->prepare("SELECT carers FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);

        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($carers);

        while($stmt->fetch()) {
            foreach (json_decode($carers) as $carer) {
                $cid = (int) $carer;
                mysqli_query($conn, "DELETE FROM users WHERE id = $cid");
            }
        }

        $stmt->free_result();

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);

        $stmt->execute();
        echo "DELETE FROM users WHERE id = $id";
    }

    $stmt->close();
    $conn->close();

    logAppend('Deleting user id: ' . $id);

    respond(true, null);
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

    $stmt->close();
    $conn->close();

    logAppend('New child: ' . $name . ' ' . $id);

    respond(true, $data);
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

    $stmt->close();
    $conn->close();

    logAppend('New carer: ' . $name . ' ' . $id);

    respond(true, $data);
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

    logAppend('Report updated');

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

    mysqli_close($conn);

    logAppend('Report retrieved');

    respond(true, $data);
}

function getPastReport() {
    global $conn;
    global $data;

    try {
        $date = $data->date;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("SELECT reports FROM info WHERE date = ?");
    $stmt->bind_param('s', $date);

    $stmt->execute();

    $stmt->store_result();
    $stmt->bind_result($reports);

    $numRows = $stmt->num_rows;

    if ($numRows < 1) {
        respond(false, null);
    }

    $r;

    while ($stmt->fetch()) {
        $r = $reports;
    }

    $data = array(
        'reports' => json_decode($r)
    );

    $stmt->close();
    $conn->close();

    logAppend('Date Specific Report retrieved');

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

    logAppend('User ' . $id .' changed email to ' . $email);

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

    logAppend('User ' . $id . ' changed pin');

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

    logAppend('User ' . $id . ' changed name to ' . $name);

    respond(true, null);
}

function changeRelation() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
        $relation = $data->relation;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $stmt = $conn->prepare("UPDATE users SET relation = ? WHERE id = ?");
    $stmt->bind_param('si', $relation, $id);

    $stmt->execute();

    logAppend('User ' . $id . ' changed relation to ' . $relation);

    respond(true, null);
}

function changeTeacher() {
    global $conn;
    global $data;

    try {
        $id = $data->id;
        $teacher = $data->teacher;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $query = "UPDATE users SET teacher = $teacher WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    $aa = $stmt->execute();

    logAppend('User ' . $id . ' changed teacher to id ' . $teacher);

    respond(true, null);
}

function getChildren() {
    global $conn;
    global $data;

    $children = mysqli_query($conn, "SELECT id, name, present, carers, teacher FROM users WHERE id > 999");

    $list = array();

    foreach ($children as $c) {
        $child = array(
            'id' => $c['id'],
            'name' => $c['name'],
            'present' => $c['present'],
            'carers' => json_decode($c['carers']),
            'teacher' => $c['teacher']
        );

        array_push($list, $child);
    }

    $data = array(
        'children' => $list
    );

    mysqli_close($conn);

    logAppend('Children list retrieved');

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

    $presentStatus = $present == 1 ? 'present' : 'not present';

    logAppend('User ' . $id . ' is made ' . $presentStatus);

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

    if ($truePin === $pin) {
        // If pin is correct
        logAppend('Login attempt user ' . $id . ' correct');

        respond(true, null);
    } else {
        // If pin is incorrect
        logAppend('Login attempt user ' . $id . ' incorrect');

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

    logAppend('Info retrieved for user ' . $id);

    respond(true, $data);
} 

function updateUser() {
    global $conn;
    global $data;

    $newInfo = array();
    $id;

    if (isset($data->id)) {
        $id = $data->id;
    } else {
        respond(false, null, 'Missing Id Parameter');
    }

    if (isset($data->name)) {
        $info = array(
            'name' => $data->name
        );
        
        array_push($newInfo, $info);
    }

    if (isset($data->relation)) {
        $info = array(
            'relation' => $data->relation
        );
        
        array_push($newInfo, $info);
    }

    if (isset($data->email)) {
        $info = array(
            'email' => $data->email
        );
        
        array_push($newInfo, $info);
    }

    if (isset($data->pin)) {
        $info = array(
            'pin' => $data->pin
        );
        
        array_push($newInfo, $info);
    }

    if (isset($data->carers)) {
        $info = array(
            'carers' => $data->carers
        );
        
        array_push($newInfo, $info);
    }

    if (isset($data->teacher)) {
        $info = array(
            'teacher' => $data->teacher
        );
        
        array_push($newInfo, $info);
    }

    if (isset($data->present)) {
        $info = array(
            'present' => $data->present
        );
        
        array_push($newInfo, $info);
    }

    if (sizeof($newInfo) > 0) {
        foreach ($newInfo as $value) {
            foreach ($value as $key => $info) {
                $stmt = $conn->prepare("UPDATE users SET $key = ? WHERE id = ?");

                $format;
                if ($key == 'teacher' || $key == 'present') {
                    $format = 'ii';
                } else {
                    $format = 'si';
                }

                $stmt->bind_param($format, $info, $id);

                if (!$stmt->execute()) {
                    respond(false, null, "Error Updating $key");
                }
            }
        }
    } else {
        respond(false, null, 'Missing Update Data Parameters');
    }

    $stmt->close();
    $conn->close();

    logAppend('Updated info for user ' . $id);

    respond(true, null);
}

function deleteCarer() {
    global $conn;
    global $data;

    if (!isset($data->carerId) || !isset($data->childId)) {
        respond(false, null, 'Missing Data Parameters');
    }

    $carerId = $data->carerId;
    $childId = $data->childId;

    $stmt = $conn->prepare("SELECT carers FROM users WHERE id = ?");
    $stmt->bind_param('i', $childId);

    if (!$stmt->execute()) {
        respond(false, null, 'Error Deleting Carer');
    }

    $stmt->store_result();
    $stmt->bind_result($carers);

    $currentCarers;

    while($stmt->fetch()) {
        $currentCarers = json_decode($carers);
    }

    if (($key = array_search($carerId, $currentCarers)) !== false) {
        unset($currentCarers[$key]);
    }

    $newCarers = json_encode($currentCarers);

    $stmt = $conn->prepare("UPDATE users SET carers = '$newCarers' WHERE id = ?");

    $stmt->bind_param('i', $childId);

    if (!$stmt->execute()) {
        respond(false, null, 'Error Deleting Carer');
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $carerId);

    if (!$stmt->execute()) {
        respond(false, null, 'Error Deleting Carer');
    }

    $stmt->close();
    $conn->close();

    logAppend('Deleted all carers for user ' . $childId);

    respond(true, null);
}

function getLog() {
    global $data;

    try {
        $date = $data->date;
    } catch(Exception $e) {
        respond(false, null, 'Missing Data Parameters');
    }

    $log = getLogAsHTML($date);

    $data = array(
        'log' => $log
    );

    if ($log != '')
        respond(true, $data);

    respond(false, $data, 'Can\'t find log');
}

?>