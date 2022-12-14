<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("bootstrap.php"); ?>
    <link rel="stylesheet" href="main.css">
    <title>Enrollment</title>
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
                        $sqlAdd = "insert into enroll (enroll_semester) value (?)";
                        $stmtAdd = $conn->prepare($sqlAdd);
                        $stmtAdd->bind_param("s", $_POST['eSemester']);
                        $stmtAdd->execute();
                        echo '<div class="alert alert-success" role="alert">New enrollment added.</div>';
                        break;
                    case 'Edit':
                        $sqlEdit = "update enroll set enroll_semester=? where enroll_id=?";
                        $stmtEdit = $conn->prepare($sqlEdit);
                        $stmtEdit->bind_param("si", $_POST['eSemester'], $_POST['eid']);
                        $stmtEdit->execute();
                        echo '<div class="alert alert-success" role="alert">Enrollment edited.</div>';
                        break;
                    case 'Delete':
                        $sqlDelete = "delete from enroll where enroll_id=?";
                        $stmtDelete = $conn->prepare($sqlDelete);
                        $stmtDelete->bind_param("i", $_POST['eid']);
                        $stmtDelete->execute();
                        echo '<div class="alert alert-success" role="alert">Enrollment deleted.</div>';
                        break;
                }
            }
        ?>
        <h1>Enrollment</h1>
        <table class="table table-striped table-bordered table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Enrollment Semester</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT enroll_id, enroll_semester from enroll";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?=$row["enroll_id"]?></td>
                    <td><?=$row["enroll_semester"]?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editEnrollment<?=$row["enroll_id"]?>">
                            Edit
                        </button>
                        <div class="modal fade" id="editEnrollment<?=$row["enroll_id"]?>" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="editEnrollment<?=$row["enroll_id"]?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editEnrollment<?=$row["enroll_id"]?>Label">
                                            Edit Enrollment
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <div class="mb-3">
                                                <label for="editEnrollment<?=$row["enroll_id"]?>Semester"
                                                    class="form-label">
                                                    Semester
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="editEnrollment<?=$row["enroll_id"]?>Semester"
                                                    aria-describedby="editEnrollment<?=$row["enroll_id"]?>Help"
                                                    name="eSemester" value="<?=$row['enroll_semester']?>">
                                                <div id="editEnrollment<?=$row["enroll_id"]?>Help" class="form-text">
                                                    Enter the enrollment semester.
                                                </div>
                                            </div>
                                            <input type="hidden" name="eid" value="<?=$row['enroll_id']?>">
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
                            <input type="hidden" name="eid" value="<?=$row["enroll_id"]?>" />
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEnrollment">
            Add New
        </button>
        <!-- Modal -->
        <div class="modal fade" id="addEnrollment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="addEnrollmentLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addEnrollmentLabel">
                            Add Enrollment
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="enrollmentSemester" class="form-label">
                                    Semester
                                </label>
                                <input type="text" class="form-control" id="enrollmentSemester"
                                    aria-describedby="semesterHelp" name="eSemester">
                                <div id="semesterHelp" class="form-text">
                                    Enter the enrollment semester.
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
