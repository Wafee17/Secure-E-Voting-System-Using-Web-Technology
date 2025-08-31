<?php

$election_id = $_GET['viewResults'];


?>



<div class="row my-3 ">
    <div class="col-12">
        <h3 class="ml-2"> Election Results</h3>
        <?php

        $fetchingActiveElections = mysqli_query($db, "SELECT * FROM elections where id ='" . $election_id . "' ") or die(mysqli_error($db));
        $totalActiveElections = mysqli_num_rows($fetchingActiveElections);
        if ($totalActiveElections > 0) {
            //fetching election name
            while ($data = mysqli_fetch_assoc($fetchingActiveElections)) {
                $election_id = $data['id'];
                $election_topic = $data['election_topic'];
        ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" class=" bg-green text-white ">
                                <h5> ELECTION TOPIC: <?php echo strtoupper($election_topic);  ?></h5>
                            </th>

                        </tr>
                        <tr>
                            <th>Photo</th>
                            <th>Candidate Details</th>
                            <th>Number of Votes</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id='" . $election_id . "'") or die(mysqli_error($db));

                        while ($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                            $candidate_id = $candidateData['id'];
                            $candidate_photo = $candidateData['candidate_photo'];



                            //fetching candidate votes
                            $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candidate_id = '" . $candidate_id . "'") or die(mysqli_error($db));
                            $totalVotes = mysqli_num_rows($fetchingVotes);



                        ?>
                            <tr>
                                <td><img src="<?php echo $candidate_photo; ?>" class="candidate_photo"></td>
                                <td> <?php echo "<b>" . $candidateData['candidate_name'] . "</b> <br/>"
                                            . $candidateData['party_details'] ?></td>
                                <td><?php echo $totalVotes; ?></td>

                            </tr>


                        <?php
                        }

                        ?>
                    </tbody>
                </table>


        <?php
            }
        } else {
            echo "No Active Elections found.";
        }
        ?>

        <hr>
        


        <?php

        $fetchingVoteDetails = mysqli_query($db, "SELECT * FROM votings WHERE election_id ='" . $election_id . "'") or die(mysqli_error($db));
        $number_of_votes = mysqli_num_rows($fetchingVoteDetails);


        if ($number_of_votes > 0) {
            $sno = 1;
        ?>

            <table class="table">
                <tr>
                <th colspan="6" class=" bg-green text-white ">
        <h5 >VOTING DETAILS:</h5>
        </th>
                </tr>
                <tr>
                    <th>S no.</th>
                    <th>Voter Name</th>
                    <!-- <th>Contact No.</th> -->
                    <!-- <th>Email</th> -->
                    <!-- <th>Voted To</th> -->
                    <th>Date</th>
                    <th>Time</th>
                </tr>



                <?php
                while ($data = mysqli_fetch_assoc($fetchingVoteDetails)) {
                    $voters_id = $data['voters_id'];
                    $candidate_id = $data['candidate_id'];


                    $fetchingUsername = mysqli_query($db, "SELECT * FROM users WHERE id='" . $voters_id . "'") or die(mysqli_error($db));
                    $isDataAvailable = mysqli_num_rows($fetchingUsername);
                    $userData = mysqli_fetch_assoc($fetchingUsername);
                    if ($isDataAvailable > 0) {
                        $username = $userData['username'];
                        $mobile_number = $userData['mobile_number'];
                        $email = $userData['email'];
                    } else {
                        $username = "No_Data";
                        $mobile_number = $userData['mobile_number'];
                    }


                    $fetchingCandidateName = mysqli_query($db, "SELECT * FROM candidate_details WHERE id='" . $candidate_id . "'") or die(mysqli_error($db));
                    $isDataAvailable = mysqli_num_rows($fetchingCandidateName);
                    $candidateData = mysqli_fetch_assoc($fetchingCandidateName);
                    if ($isDataAvailable > 0) {
                        $candidate_name = $candidateData['candidate_name'];
                    } else {
                        $candidate_name = "No_Data";
                    }



                ?>
                    <tr>
                        <td><?php echo $sno++; ?></td>
                        <td><?php echo $username; ?></td>
                        <!-- <td><?php //echo $mobile_number; ?></td> -->
                        <!-- <td><?php //echo $email; ?></td> -->
                        <!-- <td><?php //echo $candidate_name; ?></td> -->
                        <td><?php echo $data['vote_date']; ?></td>
                        <td><?php echo $data['vote_time']; ?></td>

                    </tr>
            <?php
                }
                echo "</table>";
            } else {
                echo "No Vote Details Available";
            }



            ?>


    </div>
</div>