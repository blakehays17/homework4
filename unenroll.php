<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("bootstrap.php"); ?>
    <link rel="stylesheet" href="main.css">
    <title>Unenroll</title>
</head>

<body>
    <?php include("header.php"); ?>
    <div class="content container">
        <h1>Unenroll</h1>
        <table class="table table-striped table-bordered table-hover table-responsive">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course Title</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
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
                            case 'Edit':
                                $sqlEdit = "update enroll set student_id=? where course_id=?";
                                $stmtEdit = $conn->prepare($sqlEdit);
                                $stmtEdit->bind_param("ii", $_POST['eHistory'], $_POST['cTitle']);
                                $stmtEdit->execute();
                                echo '<div class="alert alert-success" role="alert">Enrollment changed.</div>';
                                break;
                            }
                        }

                    $sql = "SELECT s.student_id, s.student_name, c.course_title, c.course_id, e.enroll_id from course c join enroll e on c.course_id = e.course_id join student s on e.student_id = s.student_id";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?=$row["student_name"]?></td>
                    <td><?=$row["course_title"]?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#edit<?=$row["enroll_id"]?>">
                            Edit
                        </button>
                        <div class="modal fade" id="edit<?=$row["enroll_id"]?>" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editLabel">Edit the enrollment.</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <div class="mb-3">
                                                <label for="eHistory<?=$row["enroll_id"]?>" class="form-label">
                                                    Select a student.
                                                </label>
                                                <select class="form-select" aria-label="Select a student"
                                                    id="eHistory<?=$row["enroll_id"]?>" name="eHistory">
                                                    <?php
                                                        $studentSql = "SELECT * from student order by student_id";
                                                        $studentResult = $conn->query($studentSql);
                                                        while($studentRow = $studentResult->fetch_assoc()) {
                                                            if ($studentRow['student_id'] == $row['student_id']) {
                                                                $selText = " selected";
                                                            } else {
                                                                $selText = "";
                                                            }
                                                    ?>
                                                    <option value="<?=$studentRow['student_id']?>" <?=$selText?>>
                                                        <?=$studentRow['student_name']?>
                                                    </option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="cTitle" class="form-label">Choose a Course</label>
                                                <select class="form-select" aria-label="Select a course" id="cTitle"
                                                    name="cTitle">
                                                    <?php
                                                        $courseSql = "select * from course order by course_title";
                                                        $courseResult = $conn->query($courseSql);
                                                        while($courseRow = $courseResult->fetch_assoc()) {
                                                            if ($courseRow['course_id'] == $row['course_id']) {
                                                                $selText = " selected";
                                                            } else {
                                                            $selText = "";
                                                            }
                                                    ?>
                                                    <option value="<?=$courseRow['course_id']?>" <?=$selText?>>
                                                        <?=$courseRow['course_title']?>
                                                    </option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="saveType" value="Edit">
                                            <button type="submit" class="btn btn-primary">Save changes.</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
                        }
                    } else {
                        echo "0 results";
                    }
                    $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <?php include("footer.php"); ?>
</body>

</html>
