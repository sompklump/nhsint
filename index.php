<?php
session_start();
require("php/msql.php");
require("php/settings.php");

$logged_in = false;
$edit_priv = "noselect";
if(isset($_SESSION['steamid'])){
  $logged_in = true;
  $edit_priv = null;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['insert_player'])){
    $name = $_POST['newP_name'];
    $steamid = $_POST['newP_steamid'];
    $forumid = $_POST['newP_forumid'];
    if(!empty($name) && !empty($steamid) && !empty($forumid)){
      $sql = "INSERT INTO members (name, steamid, forumid) VALUES ('". mysqli_real_escape_string($conn, $name) ."', '". mysqli_real_escape_string($conn, $steamid) ."', '". mysqli_real_escape_string($conn, $forumid) ."')";
      if(mysqli_query($conn, $sql)) {
        echo "<script>location.replace('./');</script>";
      }
      else{
        echo "Something went wrong when making a new player row!";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="assets/ranks.css" rel="stylesheet" type="text/css">
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <title>Interpol</title>
  </head>
  <body>
    <?php
    if(isset($_SESSION['steamid'])){
      echo "<form method='post'>
      <input class='form-control' required name='newP_name' placeholder='Name'>
      <input class='form-control' required name='newP_steamid' placeholder='Steam ID'>
      <input class='form-control' required name='newP_forumid' placeholder='Forum ID'>
      <button class='btn btn-primary' type='submit' name='insert_player'>New Player</button>
      </form>";
    }
    ?>
    <style type="text/css">
      body {
        background-color: #2f3742;
      }
      table, th, td {
        table-layout: none;
        text-align: center;
        border: 1px solid black;
        border-collapse: collapse;
        border-spacing: 0 15px;
        padding:0 5px;
        white-space: nowrap;
      }
      .noselect {
        pointer-events: none;
        -webkit-touch-callout: none; /* iOS Safari */
          -webkit-user-select: none; /* Safari */
           -khtml-user-select: none; /* Konqueror HTML */
             -moz-user-select: none; /* Firefox */
              -ms-user-select: none; /* Internet Explorer/Edge */
                  user-select: none; /* Non-prefixed version, currently
                                        supported by Chrome and Opera */
      }
    </style>
    <div class="<?= $edit_priv ?>">
      <div>
        <div class='alert alert-dark' role='alert'>
          <center><strong>NHS Command</strong></center>
        </div>
          <table class="table table-bordered table-striped table-dark" cellspacing="0" cellpadding="0">
            <tr>
              <th>Forum Rank</th>
              <th>Name</th>
              <th>Rank</th>
              <th>Mas</th>
              <th>Department</th>
              <th>RTD Rank</th>
              <th>FI Rank</th>
              <th>Join Date</th>
              <th>Forum ID</th>
              <th>Player ID</th>
              <th>Last Promotion</th>
              <th>Warning Points</th>
              <th>Promotion?</th>
              <th>Notes</th>
              <th>Last Login</th>
              <th>Status</th>
            </tr>
            <?php
            // Get command members
            $sql = "SELECT * FROM members WHERE dept = '4'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_array($result)){
                $forumRank = $forum_ranks[$row['forumRank']];
                $name = $row['name'];
                $rank = $ranks[$row['rank']];
                $cat = $cat_ranks[$row['cat']];
                $department = $departments[$row['dept']];
                $rtd = $rtd_ranks[$row['rtd']];
                $mas = $mas_ranks[$row['mas']];
                $joinDate = date('d/m/Y', strtotime($row['joinDate']));
                $forumId = $row['forumId'];
                $steamId = $row['steamid'];
                $lastProm = date('d/m/Y', strtotime($row['promoDate']));
                $warnings = $row['warnings'];
                $promotion = $row['forProm'];
                $notes = $row['notes'];
                $lastLogin = date('d/m/Y', strtotime($row['lastLogin']));
                $status = $row['status'];
                
                if($status == 1){
                  $status = "Absent";
                }
                elseif($status == 2){
                  $status = "Semi Active";
                }
                elseif($status == 3){
                  $status = "Active";
                }
                else{
                  if(strtotime($row['lastLogin']) > strtotime('-7 day') || strtotime($row['joinDate']) > strtotime('-7 day')){
                    $status = "Active";
                  }
                  else{
                    $status = "Inactive";
                  }
                }
                echo "<tr><td>$forumRank</td>";
                echo "<td>$name</td>";
                echo "<td>$rank</td>";
                echo "<td>$cat</td>";
                echo "<td>$department</td>";
                echo "<td class='{$department_colors[$row['rtd']]}'>$rtd</td>";
                echo "<td class='{$department_colors[$row['mas']]}'>$mas</td>";
                echo "<td>$joinDate</td>";
                echo "<td>$forumId</td>";
                echo "<td>$steamId</td>";
                echo "<td>$lastProm</td>";
                echo "<td><select id='warningPoints' name='warningPoints'><option value='$warnings' slected>$warnings</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option><option value='0'>0</option></select></td>";
                echo "<td><input type='checkbox' value='$promotion'></td>";
                echo "<td>$notes</td>";
                echo "<td>$lastLogin</td>";
                echo "<td>$status</td></tr>";
              }
            }
            ?>
        </table>
      </div>
      <div>
        <div class='alert alert-dark' role='alert'>
          <center><strong>Qualified</strong></center>
        </div>
          <table class="table table-bordered table-striped table-dark">
            <tr>
              <th>Forum Rank</th>
              <th>Name</th>
              <th>Rank</th>
              <th>Mas</th>
              <th>Department</th>
              <th>RTD Rank</th>
              <th>FI Rank</th>
              <th>Join Date</th>
              <th>Forum ID</th>
              <th>Player ID</th>
              <th>Last Promotion</th>
              <th>Warning Points</th>
              <th>Promotion?</th>
              <th>Notes</th>
              <th>Last Login</th>
              <th>Status</th>
            </tr>
            <?php
            // Get command members
            $sql = "SELECT * FROM members WHERE dept = '3'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_array($result)){
                $forumRank = $forum_ranks[$row['forumRank']];
                $name = $row['name'];
                $rank = $ranks[$row['rank']];
                $cat = $cat_ranks[$row['cat']];
                $department = $departments[$row['dept']];
                $rtd = $rtd_ranks[$row['rtd']];
                $mas = $mas_ranks[$row['mas']];
                $joinDate = date('d/m/Y', strtotime($row['joinDate']));
                $forumId = $row['forumId'];
                $steamId = $row['steamid'];
                $lastProm = date('d/m/Y', strtotime($row['promoDate']));
                $warnings = $row['warnings'];
                $promotion = $row['forProm'];
                $notes = $row['notes'];
                $lastLogin = date('d/m/Y', strtotime($row['lastLogin']));
                $status = $row['status'];

                if($status == 1){
                  $status = "Absent";
                }
                elseif($status == 2){
                  $status = "Semi Active";
                }
                elseif($status == 3){
                  $status = "Active";
                }
                else{
                  if(strtotime($row['lastLogin']) > strtotime('-7 day') || strtotime($row['joinDate']) > strtotime('-7 day')){
                    $status = "Active";
                  }
                  else{
                    $status = "Inactive";
                  }
                }
                echo "<tr><td>$forumRank</td>";
                echo "<td>$name</td>";
                echo "<td>$rank</td>";
                echo "<td>$cat</td>";
                echo "<td>$department</td>";
                echo "<td class='{$department_colors[$row['rtd']]}'>$rtd</td>";
                echo "<td class='{$department_colors[$row['mas']]}'>$mas</td>";
                echo "<td>$joinDate</td>";
                echo "<td>$forumId</td>";
                echo "<td>$steamId</td>";
                echo "<td>$lastProm</td>";
                echo "<td><select id='warningPoints' name='warningPoints'><option value='$warnings' slected>$warnings</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option><option value='0'>0</option></select></td>";
                echo "<td><input type='checkbox' value='$promotion'></td>";
                echo "<td>$notes</td>";
                echo "<td>$lastLogin</td>";
                echo "<td>$status</td></tr>";
              }
            }
            ?>
        </table>
      </div>
      <div>
        <div class='alert alert-dark' role='alert'>
          <center><strong>Trainee</strong></center>
        </div>
          <table class="table table-bordered table-striped table-dark">
            <tr>
              <th>Forum Rank</th>
              <th>Name</th>
              <th>Rank</th>
              <th>Mas</th>
              <th>Department</th>
              <th>RTD Rank</th>
              <th>FI Rank</th>
              <th>Join Date</th>
              <th>Forum ID</th>
              <th>Player ID</th>
              <th>Last Promotion</th>
              <th>Warning Points</th>
              <th>Promotion?</th>
              <th>Notes</th>
              <th>Last Login</th>
              <th>Status</th>
            </tr>
            <?php
            // Get command members
            $sql = "SELECT * FROM members WHERE dept = '2'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_array($result)){
                $forumRank = $forum_ranks[$row['forumRank']];
                $name = $row['name'];
                $rank = $ranks[$row['rank']];
                $cat = $cat_ranks[$row['cat']];
                $department = $departments[$row['dept']];
                $rtd = $rtd_ranks[$row['rtd']];
                $mas = $mas_ranks[$row['mas']];
                $joinDate = date('d/m/Y', strtotime($row['joinDate']));
                $forumId = $row['forumId'];
                $steamId = $row['steamid'];
                $lastProm = date('d/m/Y', strtotime($row['promoDate']));
                $warnings = $row['warnings'];
                $promotion = $row['forProm'];
                $notes = $row['notes'];
                $lastLogin = date('d/m/Y', strtotime($row['lastLogin']));
                $status = $row['status'];

                if($status == 1){
                  $status = "Absent";
                }
                elseif($status == 2){
                  $status = "Semi Active";
                }
                elseif($status == 3){
                  $status = "Active";
                }
                else{
                  if(strtotime($row['lastLogin']) > strtotime('-7 day') || strtotime($row['joinDate']) > strtotime('-7 day')){
                    $status = "Active";
                  }
                  else{
                    $status = "Inactive";
                  }
                }
                echo "<tr><td>$forumRank</td>";
                echo "<td>$name</td>";
                echo "<td>$rank</td>";
                echo "<td>$cat</td>";
                echo "<td>$department</td>";
                echo "<td class='{$department_colors[$row['rtd']]}'>$rtd</td>";
                echo "<td class='{$department_colors[$row['mas']]}'>$mas</td>";
                echo "<td>$joinDate</td>";
                echo "<td>$forumId</td>";
                echo "<td>$steamId</td>";
                echo "<td>$lastProm</td>";
                echo "<td><select id='warningPoints' name='warningPoints'><option value='$warnings' slected>$warnings</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option><option value='0'>0</option></select></td>";
                echo "<td><input type='checkbox' value='$promotion'></td>";
                echo "<td>$notes</td>";
                echo "<td>$lastLogin</td>";
                echo "<td>$status</td></tr>";
              }
            }
            ?>
        </table>
      </div>
      <div>
        <div class='alert alert-dark' role='alert'>
          <center><strong>Reserve</strong></center>
        </div>
          <table class="table table-bordered table-striped table-dark">
            <tr>
              <th>Forum Rank</th>
              <th>Name</th>
              <th>Rank</th>
              <th>Mas</th>
              <th>Department</th>
              <th>RTD Rank</th>
              <th>FI Rank</th>
              <th>Join Date</th>
              <th>Forum ID</th>
              <th>Player ID</th>
              <th>Last Promotion</th>
              <th>Warning Points</th>
              <th>Promotion?</th>
              <th>Notes</th>
              <th>Last Login</th>
              <th>Status</th>
            </tr>
            <?php
            // Get command members
            $sql = "SELECT * FROM members WHERE dept = '1'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_array($result)){
                $forumRank = $forum_ranks[$row['forumRank']];
                $name = $row['name'];
                $rank = $ranks[$row['rank']];
                $cat = $cat_ranks[$row['cat']];
                $department = $departments[$row['dept']];
                $rtd = $rtd_ranks[$row['rtd']];
                $mas = $mas_ranks[$row['mas']];
                $joinDate = date('d/m/Y', strtotime($row['joinDate']));
                $forumId = $row['forumId'];
                $steamId = $row['steamid'];
                $lastProm = date('d/m/Y', strtotime($row['promoDate']));
                $warnings = $row['warnings'];
                $promotion = $row['forProm'];
                $notes = $row['notes'];
                $lastLogin = date('d/m/Y', strtotime($row['lastLogin']));
                $status = $row['status'];

                if($status == 1){
                  $status = "Absent";
                }
                elseif($status == 2){
                  $status = "Semi Active";
                }
                elseif($status == 3){
                  $status = "Active";
                }
                else{
                  if(strtotime($row['lastLogin']) > strtotime('-7 day') || strtotime($row['joinDate']) > strtotime('-7 day')){
                    $status = "Active";
                  }
                  else{
                    $status = "Inactive";
                  }
                }
                echo "<tr><td>$forumRank</td>";
                echo "<td>$name</td>";
                echo "<td>$rank</td>";
                echo "<td>$cat</td>";
                echo "<td>$department</td>";
                echo "<td class='{$department_colors[$row['rtd']]}'>$rtd</td>";
                echo "<td class='{$department_colors[$row['mas']]}'>$mas</td>";
                echo "<td>$joinDate</td>";
                echo "<td>$forumId</td>";
                echo "<td>$steamId</td>";
                echo "<td>$lastProm</td>";
                echo "<td><select id='warningPoints' name='warningPoints'><option value='$warnings' slected>$warnings</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option><option value='0'>0</option></select></td>";
                echo "<td><input type='checkbox' value='$promotion'></td>";
                echo "<td>$notes</td>";
                echo "<td>$lastLogin</td>";
                echo "<td>$status</td></tr>";
              }
            }
            ?>
        </table>
      </div>
    </div>
  </body>
</html>