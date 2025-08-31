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

// Initialize election variable
$election = null;
$election_id = null;

// Fetch election data based on the passed ID
if (isset($_GET['edit'])) {
    $election_id = $_GET['edit'];
    
    $sql = "SELECT * FROM elections WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $election_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data exists for the election
    if ($result->num_rows > 0) {
        $election = $result->fetch_assoc(); // Fetch the election data
    } else {
        echo "<div class='alert alert-danger my-3' role='alert'>Election not found!</div>";
    }
    $stmt->close();
}

// Only show the form if election data exists
if ($election) {
?>

<div class="col-4 my-3">
    <h3>Edit Election</h3>
    <form method="POST">
        <input type="hidden" name="election_id" value="<?php echo $election_id; ?>" />
        <div class="form-group">
        <label for="election_topic">Election Name:</label> <!-- Text before input -->
            <input type="text" name="election_topic" placeholder="Election Name" class="form-control" value="<?php echo htmlspecialchars($election['election_topic']); ?>" required />
        </div>
        <div class="form-group">
        <label for="number_of_candidates">Number of Candidates:</label>
            <input type="number" name="number_of_candidates" placeholder="Number of Candidates" class="form-control" value="<?php echo htmlspecialchars($election['no_of_candidates']); ?>" required />
        </div>
        <div class="form-group">
        <label for="starting_date">Starting Date:</label>
            <input type="text" onfocus="this.type='date'" name="starting_date" placeholder="Starting Date" class="form-control" value="<?php echo htmlspecialchars($election['starting_date']); ?>" required />
        </div>
        <div class="form-group">
            <label for="ending_date">Ending Date:</label>
            <input type="text" onfocus="this.type='date'" name="ending_date" placeholder="Ending Date" class="form-control" value="<?php echo htmlspecialchars($election['ending_date']); ?>" required />
        </div>
        <input type="submit" value="Edit Election" name="saveElectionBtn" class="btn btn-success" />
    </form>
</div>

<?php
} else {
    // Show a message and a link to go back if election is not found
    echo "<div class='col-12 my-3'>";
    echo "<div class='alert alert-warning' role='alert'>";
    echo "Election not found or invalid election ID. ";
    echo "<a href='index.php' class='alert-link'>Go back to homepage</a>";
    echo "</div>";
    echo "</div>";
}

// Check if form is submitted
if (isset($_POST['saveElectionBtn']) && $election) {
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
    $sql = "UPDATE elections SET election_topic = ?, no_of_candidates = ?, starting_date = ?, ending_date = ?, status = ?, inserted_by = ?, inserted_on = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    
    // Bind all 7 variables now
    $stmt->bind_param("sisssssi", $election_topic, $number_of_candidates, $starting_date, $ending_date, $status, $inserted_by, $inserted_on, $election_id);
    
    
    if ($stmt->execute()) {
          // Redirect to homepage with success message instead of staying on edit page
          echo "<script>location.assign('index.php?homepage=1&success=1')</script>";
          
        } else {
        // Redirect to homepage with failed message
        echo "<script>location.assign('index.php?homepage=1&failed=1')</script>";
        
    }

    $stmt->close();
} else if (isset($_POST['saveElectionBtn']) && !$election) {
    // Show error if form is submitted but no election data
    echo "<div class='alert alert-danger my-3' role='alert'>Cannot process form: Election data not found!</div>";
}


?>
