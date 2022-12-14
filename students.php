<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("bootstrap.php"); ?>
    <link rel="stylesheet" href="main.css">
    <title>Students</title>
</head>

<body>
    <?php include("header.php"); ?>
    <div class="content container">
        <?php
            $servername = "localhost";
            $username = "blakehay_s";
            $password = "wiZ#HZ^1]CAV";
            $dbname = "blakehay_sdb";

             // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
             // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                switch ($_POST['saveType']) {
                    case 'Add':
                        $sqlAdd = "insert into student (student_name) value (?)";
                        $stmtAdd = $conn->prepare($sqlAdd);
                        $stmtAdd->bind_param("s", $_POST['sName']);
                        $stmtAdd->execute();
                        echo '<div class="alert alert-success" role="alert">New student added.</div>';
                        break;
                    case 'Edit':
                        $sqlEdit = "update student set student_name=? where student_id=?";
                        $stmtEdit = $conn->prepare($sqlEdit);
                        $stmtEdit->bind_param("si", $_POST['sName'], $_POST['sid']);
                        $stmtEdit->execute();
                        echo '<div class="alert alert-success" role="alert">Student edited.</div>';
                        break;
                    case 'Delete':
                        $sqlDelete = "delete from student where student_id=?";
                        $stmtDelete = $conn->prepare($sqlDelete);
                        $stmtDelete->bind_param("i", $_POST['sid']);
                        $stmtDelete->execute();
                        echo '<div class="alert alert-success" role="alert">Student deleted.</div>';
                        break;
                }
            }
        ?>
        <h1>Students</h1>
        <table class="table table-striped table-bordered table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT student_id, student_name from student";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?=$row["student_id"]?></td>
                    <td><?=$row["student_name"]?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editStudent<?=$row["student_id"]?>">
                            Edit
                        </button>
                        <div class="modal fade" id="editStudent<?=$row["student_id"]?>" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="editStudent<?=$row["student_id"]?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editStudent<?=$row["student_id"]?>Label">
                                            Edit Student
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <div class="mb-3">
                                                <label for="editStudent<?=$row["student_id"]?>Name" class="form-label">
                                                    Name
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="editStudent<?=$row["student_id"]?>Name"
                                                    aria-describedby="editStudent<?=$row["student_id"]?>Help"
                                                    name="sName" value="<?=$row['student_name']?>">
                                                <div id="editStudent<?=$row["student_id"]?>Help" class="form-text">
                                                    Enter the student's name.
                                                </div>
                                            </div>
                                            <input type="hidden" name="sid" value="<?=$row['student_id']?>">
                                            <input type="hidden" name="saveType" value="Edit">
                                            <input type="submit" class="btn btn-primary" value="Submit">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="sid" value="<?=$row["student_id"]?>" />
                            <input type="hidden" name="saveType" value="Delete">
                            <input type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"
                                value="Delete">
                        </form>
                    </td>
                </tr>
                <?php
                        }
                    } 
                    else {
                        echo "0 results";
                    }
                    $conn->close();
                ?>
            </tbody>
        </table>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudent">
            Add New
        </button>
        <!-- Modal -->
        <div class="modal fade" id="addStudent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="addStudentLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addStudentLabel">
                            Add Student
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="studentName" class="form-label">
                                    Name
                                </label>
                                <input type="text" class="form-control" id="studentName" aria-describedby="nameHelp"
                                    name="sName">
                                <div id="nameHelp" class="form-text">
                                    Enter the student's name.
                                </div>
                            </div>
                            <input type="hidden" name="saveType" value="Add">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("footer.php"); ?>
</body>

</html>
