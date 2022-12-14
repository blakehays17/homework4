<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("bootstrap.php"); ?>
    <link rel="stylesheet" href="main.css">
    <title>Instructors</title>
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
                        $sqlAdd = "insert into instructor (instructor_firstname) value (?)";
                        $stmtAdd = $conn->prepare($sqlAdd);
                        $stmtAdd->bind_param("s", $_POST['iName']);
                        $stmtAdd->execute();
                        echo '<div class="alert alert-success" role="alert">New instructor added.</div>';
                        break;
                    case 'Edit':
                        $sqlEdit = "update instructor set instructor_firstname=? where instructor_id=?";
                        $stmtEdit = $conn->prepare($sqlEdit);
                        $stmtEdit->bind_param("si", $_POST['iName'], $_POST['iid']);
                        $stmtEdit->execute();
                        echo '<div class="alert alert-success" role="alert">Instructor edited.</div>';
                        break;
                    case 'Delete':
                        $sqlDelete = "delete from instructor where instructor_id=?";
                        $stmtDelete = $conn->prepare($sqlDelete);
                        $stmtDelete->bind_param("i", $_POST['iid']);
                        $stmtDelete->execute();
                        echo '<div class="alert alert-success" role="alert">Instructor deleted.</div>';
                        break;
                }
            }
        ?>
        <h1>Instructors</h1>
        <table class="table table-striped table-bordered table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT instructor_id, instructor_firstname from instructor";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?=$row["instructor_id"]?></td>
                    <td><?=$row["instructor_firstname"]?></td>
                    <td>
                        <button type="button" class="btn" data-bs-toggle="modal"
                            data-bs-target="#editInstructor<?=$row["instructor_id"]?>">
                            Edit
                        </button>
                        <div class="modal fade" id="editInstructor<?=$row["instructor_id"]?>" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="editInstructor<?=$row["instructor_id"]?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editInstructor<?=$row["instructor_id"]?>Label">
                                            Edit Instructor
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <div class="mb-3">
                                                <label for="editInstructor<?=$row["instructor_id"]?>Name"
                                                    class="form-label">
                                                    Name
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="editInstructor<?=$row["instructor_id"]?>Name"
                                                    aria-describedby="editInstructor<?=$row["instructor_id"]?>Help"
                                                    name="iName" value="<?=$row['instructor_firstname']?>">
                                                <div id="editInstructor<?=$row["instructor_id"]?>Help"
                                                    class="form-text">
                                                    Enter the instructor's name.
                                                </div>
                                            </div>
                                            <input type="hidden" name="iid" value="<?=$row['instructor_id']?>">
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
                            <input type="hidden" name="iid" value="<?=$row["instructor_id"]?>" />
                            <input type="hidden" name="saveType" value="Delete">
                            <input type="submit" class="btn" onclick="return confirm('Are you sure?')" value="Delete">
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
        <br />
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInstructor">
            Add New
        </button>
        <!-- Modal -->
        <div class="modal fade" id="addInstructor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="addInstructorLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addInstructorLabel">
                            Add Instructor
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="instructorName" class="form-label">
                                    Name
                                </label>
                                <input type="text" class="form-control" id="instructorName" aria-describedby="nameHelp"
                                    name="iName">
                                <div id="nameHelp" class="form-text">
                                    Enter the instructor's name.
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
