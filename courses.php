<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("bootstrap.php"); ?>
    <link rel="stylesheet" href="main.css">
    <title>Courses</title>
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
                        $sqlAdd = "insert into course (course_title) value (?)";
                        $stmtAdd = $conn->prepare($sqlAdd);
                        $stmtAdd->bind_param("s", $_POST['cTitle']);
                        $stmtAdd->execute();
                        echo '<div class="alert alert-success" role="alert">New course added.</div>';
                        break;
                    case 'Edit':
                        $sqlEdit = "update course set course_title=? where course_id=?";
                        $stmtEdit = $conn->prepare($sqlEdit);
                        $stmtEdit->bind_param("si", $_POST['cTitle'], $_POST['cid']);
                        $stmtEdit->execute();
                        echo '<div class="alert alert-success" role="alert">Course edited.</div>';
                        break;
                    case 'Delete':
                        $sqlDelete = "delete from course where course_id=?";
                        $stmtDelete = $conn->prepare($sqlDelete);
                        $stmtDelete->bind_param("i", $_POST['cid']);
                        $stmtDelete->execute();
                        echo '<div class="alert alert-success" role="alert">Course deleted.</div>';
                        break;
                }
            }
        ?>
        <h1>Courses</h1>
        <table class="table table-striped table-bordered table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Title</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT course_id, course_title from course";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?=$row["course_id"]?></td>
                    <td><?=$row["course_title"]?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editCourse<?=$row["course_id"]?>">
                            Edit
                        </button>
                        <div class="modal fade" id="editCourse<?=$row["course_id"]?>" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="editCourse<?=$row["course_id"]?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editCourse<?=$row["course_id"]?>Label">
                                            Edit Course
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <div class="mb-3">
                                                <label for="editCourse<?=$row["course_id"]?>Title" class="form-label">
                                                    Title
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="editCourse<?=$row["course_id"]?>Title"
                                                    aria-describedby="editCourse<?=$row["course_id"]?>Help"
                                                    name="cTitle" value="<?=$row['course_title']?>">
                                                <div id="editCourse<?=$row["course_id"]?>Help" class="form-text">
                                                    Enter the course's title.
                                                </div>
                                            </div>
                                            <input type="hidden" name="cid" value="<?=$row['course_id']?>">
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
                            <input type="hidden" name="cid" value="<?=$row["course_id"]?>" />
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourse">
            Add New
        </button>
        <!-- Modal -->
        <div class="modal fade" id="addCourse" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="addCourseLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCourseLabel">
                            Add Course
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="courseTitle" class="form-label">
                                    Title
                                </label>
                                <input type="text" class="form-control" id="courseTitle" aria-describedby="titleHelp"
                                    name="cTitle">
                                <div id="titleHelp" class="form-text">
                                    Enter the course's title.
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
