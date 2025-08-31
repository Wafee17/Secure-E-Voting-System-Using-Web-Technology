<?php
 if(isset($_GET['success'])) {
    ?>
    <div class="alert alert-success my-3" role="alert">
    Candidate details have been updated successfully!
</div>
<?php
}else if(isset($_GET['failed'])){
    ?>
    <div class="alert alert-danger my-3" role="alert">
    Error updating Candidate details, please try again!
</div>
    <?php
}

// Fetch candidate data based on the passed ID (if editing)
$candidate_id = $_GET['editCandidate'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'onlinevotingsystem');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch candidate data
$sql = "SELECT * FROM candidate_details WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if data exists for the candidate
if ($result->num_rows > 0) {
    $candidate = $result->fetch_assoc(); // Fetch the candidate data
} else {
    die("Candidate not found!");
}
$stmt->close();
?>

<div class="col-4 my-3">
    <h3>Edit Candidate</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>" />
        
        <div class="form-group">
            <label for="election_id">Select Election:</label>
            <select name="election_id" class="form-control" required>
                <option value="">Select Election</option>
                <?php
                $fetchingElections = $conn->query("SELECT * FROM elections");
                while ($row = $fetchingElections->fetch_assoc()) {
                    $election_id = $row['id'];
                    $election_name = $row['election_topic'];
                    $selected = ($election_id == $candidate['election_id']) ? 'selected' : '';
                    echo "<option value='$election_id' $selected>$election_name</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="candidate_name">Candidate Name:</label>
            <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control" value="<?php echo $candidate['candidate_name']; ?>" required />
        </div>
        
        <div class="form-group">
            <label for="candidate_photo">Candidate Photo:</label>
            <?php if (!empty($candidate['candidate_photo'])): ?>
                <img src="<?php echo $candidate['candidate_photo']; ?>" alt="Current Photo" style="width: 100px; height: auto; margin-bottom: 10px;">
            <?php endif; ?>
            <input type="file" name="candidate_photo" class="form-control" />
        </div>
        
        <div class="form-group">
            <label for="party_details">Party Details:</label>
            <input type="text" name="party_details" placeholder="Party Details" class="form-control" value="<?php echo $candidate['party_details']; ?>" required />
        </div>
        
        <input type="submit" value="Edit Candidate" name="editCandidateBtn" class="btn btn-success" />
    </form>
</div>

<?php
if (isset($_POST['editCandidateBtn'])) {
    $election_id = $conn->real_escape_string($_POST['election_id']);
    $candidate_name = $conn->real_escape_string($_POST['candidate_name']);
    $party_details = $conn->real_escape_string($_POST['party_details']);
    $candidate_id = $conn->real_escape_string($_POST['candidate_id']);
    $photo_query = '';

    // Handle photo upload if a new photo is selected
    if (!empty($_FILES['candidate_photo']['name'])) {
        $targetted_folder = "../assets/images/candidate_photos/";
        $candidate_photo = $targetted_folder . uniqid() . "_" . basename($_FILES['candidate_photo']['name']);
        $photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
        $photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
        $image_size = $_FILES['candidate_photo']['size'];
        $allowed_types = array("jpg", "png", "jpeg");

        if ($image_size < 2000000 && in_array($photo_type, $allowed_types)) {
            if (move_uploaded_file($photo_tmp_name, $candidate_photo)) {
                $photo_query = ", candidate_photo = '$candidate_photo'";
            } else {
                echo "<div class='alert alert-danger my-3'>Failed to upload photo, please try again.</div>";
            }
        } else {
            echo "<div class='alert alert-danger my-3'>Invalid photo! Ensure it is .jpg, .jpeg, or .png and under 2MB.</div>";
        }
    }

    $sql = "UPDATE candidate_details SET election_id = ?, candidate_name = ?, party_details = ? $photo_query WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $election_id, $candidate_name, $party_details, $candidate_id);

   
    if ($stmt->execute()) {
        echo "<script>location.assign('index.php?editCandidate=$candidate_id&success=1')</script>";
        
      } else {
      echo "<script>location.assign('index.php?editCandidate=$candidate_id&failed=1')</script>";
      
  }

    $stmt->close();
}
$conn->close();
?>
