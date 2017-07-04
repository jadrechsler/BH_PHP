<?php

    $fetchChildren = file_get_contents('http://localhost/query.php?action=get_children&data={}');

    $children = json_decode($fetchChildren)->data->children;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Children</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin/students.css">
</head>
<body>
    <h1>Manage Children</h1>
    <div class="container-fluid student-item">
        <div class="col-md-3 col-sm-3"></div>
        <div id="options" class="col-md-6 col-sm-6">
            <div class="container-fluid options-container">
                <div class="col-md-6 col-sm-6">
                    <input type="text" id="search-input" onkeyup="searchStudents()" placeholder="Search for a name">
                </div>
                <div class="col-md-3 col-sm-3 option-button-container">
                    <div id="remove-button" class="option-button" onclick="removeSelectedChildren()">
                        <p>Remove</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 option-button-container">
                    <div id="add-button" class="option-button" onclick="window.location('/admin/add_child')">
                        <p>Add</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div id="main">
        <div id="student-list">
            <?php foreach ($children as $child): ?>
            <div class="container-fluid student-item">
                <div class="col-md-3 col-sm-3"></div>
                <div id="children-list-container" class="col-md-6 col-sm-6">
                    <div childId="<?php echo $child->id; ?>" class="container-fluid list-item">
                        <div class="col-md-4 col-sm-4 img-container">
                            <img src="/img/face.jpg" height="50px" width="50px" />
                        </div>                    
                        <div class="col-md-7 col-sm-7 p-container">
                            <p><?php echo $child->name; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/query.js"></script>
    <script src="/js/admin/students.js"></script>
</body>
</html>