<?php
// Display success or failed messages
if(isset($_GET['success'])) {
    ?>
    <div class="alert alert-success my-3" role="alert">
        Election has been edited successfully!
    </div>
    <?php
} else if(isset($_GET['failed'])) {
    ?>
    <div class="alert alert-danger my-3" role="alert">
        Election couldn't be edited, please try again!
    </div>
    <?php
}
?>

<div class="row my-3 ml-2">
   
    <div class="col-12">
        <h3>Elections</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S No.</th>
                    <th scope="col">Election Name</th>
                    <th scope="col">No. of Candidates</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $fetchingData = mysqli_query($db,"SELECT * FROM elections") or die (mysqli_error($db));
            $isAnyElectionAdded = mysqli_num_rows($fetchingData);

            if($isAnyElectionAdded > 0)
            {
                $sno = 1;
                while ($row=mysqli_fetch_assoc($fetchingData)) 
                {
                    $election_id=$row['id'];
                    ?>
                    <tr>
                        <td><?php echo $sno++;?></td>
                        <td><?php echo $row['election_topic'];?></td>
                        <td><?php echo $row['no_of_candidates'];?></td>
                        <td><?php echo $row['starting_date'];?></td>
                        <td><?php echo $row['ending_date'];?></td>
                        <td><?php echo $row['status'];?></td>
                        <td>
                            <a href="index.php?viewResults=<?php echo $election_id;?>" class="btn btn-sm btn-success">View Results</a>
                            
                        </td>
                    </tr>

                    <?php
                }
            }else{
                ?>
                    <tr>
                        <td colspan="7">No Election found</td>
                    </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>



