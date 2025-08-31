<?php
require_once("inc/header.php");
require_once("inc/navigation.php");


if (isset($_GET['homepage'])) {
    require_once("inc/homepage.php");
} elseif (isset($_GET['addElectionPage'])) {
    require_once("inc/add_elections.php");
} else if (isset($_GET['addCandidatePage'])) {
    require_once("inc/add_candidates.php");
} elseif(isset($_GET['viewResults']))
{
    require_once("inc/viewResults.php");
}elseif(isset($_GET['edit'])){
    require_once("inc/editElection.php");
}elseif(isset($_GET['editCandidate'])){
    require_once("inc/editCandidates.php");
}


?>

<?php

require_once("inc/footer.php");
?>