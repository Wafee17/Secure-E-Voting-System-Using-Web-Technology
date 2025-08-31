<?php

if (isset($_GET['added'])) {
?>
    <div class="alert alert-success my-3" role="alert">
        Candidate has been added successfully!
    </div>
<?php

} 
elseif (isset($_GET['largeFile'])) {
?>
    <div class="alert alert-danger my-3" role="alert">
        Candidate Image is too large! (File size should be less than 2MB.)
    </div>
<?php
} elseif (isset($_GET['invalidFile'])) {
?>
    <div class="alert alert-danger my-3" role="alert">
        Invalid Image type! (Only .png or .jpg or .jpeg allowed.)
    </div>
<?php
} elseif (isset($_GET['failed'])) {
?>
    <div class="alert alert-danger my-3" role="alert">
        Image failed to upload!, please try again.
    </div>
<?php
}elseif(isset($_GET['delete_id'])){
    $d_id =$_GET['delete_id'];
    mysqli_query($db,"DELETE FROM candidate_details WHERE id = '".$d_id."'")or die(mysqli_error($db));
    ?>
        <div class="alert alert-danger my-3" role="alert">
        Candidate has been deleted successfully!
    </div>


<?php
}
?>



<div class="row my-3 ml-2" >
    <div class="col-4">
        <h3>Add New Candidates</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="  form-group">
                <select name="election_id" class="form-control" required>
                    <option value="">Select Election</option>
                    <?php
                    $fetchingElections = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                    $isAnyElectionAdded = mysqli_num_rows($fetchingElections);
                    if ($isAnyElectionAdded > 0) {

                        while ($row = mysqli_fetch_assoc($fetchingElections)) {

                            $election_id = $row['id'];
                            $election_name = $row['election_topic'];
                            $allowed_candidates = $row['no_of_candidates'];

                            //now checking how many candidates are added in this election
                            $fetchingCandidate = mysqli_query($db, "SELECT* FROM candidate_details WHERE election_id='" . $election_id . "'")
                                or die(mysqli_error($db));
                            $added_candidates = mysqli_num_rows($fetchingCandidate);

                            if ($added_candidates < $allowed_candidates) {

                    ?>
                                <option value="<?php echo $election_id; ?>"><?php echo $election_name; ?></option>
                        <?php

                            }
                        }
                    } else {
                        ?>
                        <option value="">Please Add any Election first!</option>
                    <?php
                    }


                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="file" name="candidate_photo" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="text" name="party_details" placeholder="Party Details" class="form-control" required />
            </div>
            <input type="submit" value="Add Candidate" name="addCandidateBtn" class="btn btn-success" />

        </form>
    </div>
    <div class="col-8">
        <h3>Candidates Details</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S No.</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Party</th>
                    <th scope="col">Election</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($db, "SELECT * FROM candidate_details") or die(mysqli_error($db));
                $isAnyCandidateAdded = mysqli_num_rows($fetchingData);

                if ($isAnyCandidateAdded > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {

                                $candidate_id = $row['id'];

                        $election_id = $row['election_id'];
                        $fetchingElectionName = mysqli_query($db, "SELECT * FROM  elections WHERE id = '" . $election_id . "'") or die(mysqli_error($db));
                        $execFetchingElectionNameQuery = mysqli_fetch_assoc($fetchingElectionName);
                        $election_name = $execFetchingElectionNameQuery['election_topic'];
                        $candidate_photo = $row['candidate_photo'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><img src="<?php echo $candidate_photo; ?>" alt="image" class="candidate_photo"></td>
                            <td><?php echo $row['candidate_name']; ?></td>
                            <td><?php echo $row['party_details']; ?></td>
                            <td><?php echo $election_name; ?></td>
                            <td>

                            <a href="index.php?editCandidate=<?php echo $candidate_id;  ?>" class="btn btn-sm btn-warning">Edit</a>

                                <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $candidate_id;  ?>)">Delete</button>
                            </td>
                        </tr>

                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7">No Candidate found</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteData = (c_id) =>
    {
        let c =confirm("Do you really want to Delete it?");

        if (c == true)
    {
        
         location.assign("index.php?addCandidatePage=1&delete_id=" + c_id);
    }
    }
</script>


<?php
if (isset($_POST['addCandidateBtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
    $party_details = mysqli_real_escape_string($db, $_POST['party_details']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("y-m-d");


    //Photograph Logic starts
    $targetted_folder = "../assets/images/candidate_photos/";
    $candidate_photo = $targetted_folder . rand(1111111111, 9999999999) . "_" . rand(1111111111, 9999999999) .
        $_FILES['candidate_photo']['name'];
    $candidate_photo_tmp_name =  $_FILES['candidate_photo']['tmp_name'];
    $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "png", "jpeg");
    $image_size =  $_FILES['candidate_photo']['size'];

    //image size should be under 2MB
    if ($image_size < 2000000)  //2000000 = 2MB
    {
        if (in_array($candidate_photo_type, $allowed_types)) {

            if (move_uploaded_file($candidate_photo_tmp_name, $candidate_photo)) {

                //inserting into db
                mysqli_query($db, "INSERT INTO candidate_details(election_id,candidate_name,party_details,candidate_photo,inserted_by,inserted_on) VALUES('" . $election_id . "','" . $candidate_name . "','" . $party_details . "','" . $candidate_photo . "','" . $inserted_by . "','" . $inserted_on . "')") or die(mysqli_error($db));


                echo "<script>location.assign('index.php?addCandidatePage=1&added=1')</script>";
            } else {

                echo "<script>location.assign('index.php?addCandidatePage=1&failed=1')</script>";
            }
        } else {
            echo "<script>location.assign('index.php?addCandidatePage=1&invalidFile=1')</script>";
        }
    } else {
        echo "<script>location.assign('index.php?addCandidatePage=1&largeFile=1')</script>";
    }

    //Photograph Logic ends


}

?>