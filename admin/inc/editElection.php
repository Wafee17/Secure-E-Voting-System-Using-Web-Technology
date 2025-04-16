<?php
        if(isset($_GET['success'])) {
            ?>
            <div class="alert alert-success my-3" role="alert">
          Election has been Edited successfully!
        </div>
        <?php
        }else if(isset($_GET['failed'])){
            ?>
            <div class="alert alert-danger my-3" role="alert">
          Election couldn't be Edited, please try again!
        </div>
            <?php
        }




// Database connection
$conn = new mysqli('localhost', 'root', '', 'onlinevotingsystem');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch election data based on the passed ID
$election_id = $_GET['edit'];

$sql = "SELECT * FROM elections WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $election_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if data exists for the election
if ($result->num_rows > 0) {
    $election = $result->fetch_assoc(); // Fetch the election data
} else {
    echo "Election not found!";
}
$stmt->close();
$conn->close();
?>

<div class="col-4 my-3">
    <h3>Edit Election</h3>
    <form method="POST">
        <input type="hidden" name="election_id" value="<?php echo $election_id; ?>" />
        <div class="form-group">
        <label for="election_topic">Election Name:</label> <!-- Text before input -->
            <input type="text" name="election_topic" placeholder="Election Name" class="form-control" value="<?php echo $election['election_topic']; ?>" required />
        </div>
        <div class="form-group">
        <label for="number_of_candidates">Number of Candidates:</label>
            <input type="number" name="number_of_candidates" placeholder="Number of Candidates" class="form-control" value="<?php echo $election['no_of_candidates']; ?>" required />
        </div>
        <div class="form-group">
        <label for="starting_date">Starting Date:</label>
            <input type="text" onfocus="this.type='date'" name="starting_date" placeholder="Starting Date" class="form-control" value="<?php echo $election['starting_date']; ?>" required />
        </div>
        <div class="form-group">
            <label for="ending_date">Ending Date:</label>
            <input type="text" onfocus="this.type='date'" name="ending_date" placeholder="Ending Date" class="form-control" value="<?php echo $election['ending_date']; ?>" required />
        </div>
        <input type="submit" value="Edit Election" name="saveElectionBtn" class="btn btn-success" />
    </form>
</div>

<?php
// Check if form is submitted
if (isset($_POST['saveElectionBtn'])) {
    // Retrieve form data
    $election_id = $_POST['election_id'];
    $election_topic = $_POST['election_topic'];
    $number_of_candidates = $_POST['number_of_candidates'];
    $starting_date = $_POST['starting_date'];
    $ending_date = $_POST['ending_date'];
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    // Calculate status based on date difference
    $date1 = date_create($inserted_on);
    $date2 = date_create($starting_date);
    $diff = date_diff($date1, $date2);

    if ((int)$diff->format("%R%a") > 0) {
        $status = "InActive";
    } else {
        $status = "Active";
    }

    // Update the database with new values
    $conn = new mysqli('localhost', 'root', '', 'onlinevotingsystem');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE elections SET election_topic = ?, no_of_candidates = ?, starting_date = ?, ending_date = ?, status = ?, inserted_by = ?, inserted_on = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind all 7 variables now
    $stmt->bind_param("sisssssi", $election_topic, $number_of_candidates, $starting_date, $ending_date, $status, $inserted_by, $inserted_on, $election_id);
    
    
    if ($stmt->execute()) {
          echo "<script>location.assign('index.php?edit=1&success=1')</script>";
          
        } else {
        echo "<script>location.assign('index.php?edit=1&failed=1')</script>";
        
    }

    $stmt->close();
    $conn->close();
}


?>
