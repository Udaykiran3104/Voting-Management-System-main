<?php include('db_connect.php'); ?>
<?php
// Check if user is logged in

if (!isset($_SESSION['login_id'])) {
    header('location: login.php');
}

// Fetch default voting session
$voting = $conn->query("SELECT * FROM voting_list WHERE is_default = 1 ");
$voting_data = $voting->fetch_assoc();

// If voting session is found
if ($voting_data) {
    $id = $voting_data['id'];
    $title = $voting_data['title'];
    $description = $voting_data['description'];

    // Fetch user's votes for the current voting session
    $mvotes = $conn->query("SELECT * FROM votes WHERE voting_id = $id AND user_id = " . $_SESSION['login_id']);
    $vote_arr = array();
    while ($row = $mvotes->fetch_assoc()) {
        $vote_arr[$row['category_id']][] = $row;
    }

    // Fetch voting options
    $opts = $conn->query("SELECT * FROM voting_opt WHERE voting_id = $id");
    $opt_arr = array();
    while ($row = $opts->fetch_assoc()) {
        $opt_arr[$row['id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting System</title>
    <!-- Include CSS and JS files -->
    <?php include('./header.php'); ?>
</head>

<body>
    <!-- Include top bar -->
    <?php include 'topbar.php'; ?>
    <main id="view-panel">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-primary btn-sm col-md-2 float-right" href="voting.php?page=home">View Poll</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <small><b>Your Vote for</b></small>
                                <h3><b><?php echo $title ?></b></h3>
                                <small><b><?php echo $description; ?></b></small>
                            </div>

                            <?php
                            // If voting options exist
                            if (!empty($opt_arr)) {
                                // Fetch categories for which voting options are available
                                $cats = $conn->query("SELECT * FROM category_list WHERE id IN (SELECT category_id FROM voting_opt WHERE voting_id = $id)");
                                while ($row = $cats->fetch_assoc()) :
                            ?>
                                    <hr>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div class="text-center">
                                                <h3><b><?php echo $row['category'] ?></b></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <?php
                                        // If user has voted for options in this category
                                        if (isset($vote_arr[$row['id']])) {
                                            foreach ($vote_arr[$row['id']] as $voted) :
                                        ?>
                                                <div class="candidate" style="position: relative;">
                                                    <div class="item">
                                                        <div style="display: flex">
                                                            <img src="assets/img/<?php echo $opt_arr[$voted['voting_opt_id']]['image_path'] ?>" alt="">
                                                        </div>
                                                        <br>
                                                        <div class="text-center">
                                                            <large class="text-center"><b><?php echo ucwords($opt_arr[$voted['voting_opt_id']]['opt_txt']) ?></b></large>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            endforeach;
                                        } else {
                                            // If no votes found for this category
                                            echo "No votes found for this category.";
                                        }
                                        ?>
                                    </div>
                            <?php
                                endwhile;
                            } else {
                                // If no voting options found
                                echo "No voting options available.";
                            }
                            ?>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<!-- Include JS scripts -->

</html>
