<?php
session_start();

if (!isset($_SESSION['auth_login']) && $_SESSION['auth_login'] !== true) {
    header("Location: /");
    exit;
}

require __DIR__ . '/Database.php';
$database = new Database;

$payload = json_decode($_SESSION['payload'], true);
$name = $payload['name'];
$email = $payload['email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Social Auth</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="section-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-8 offset-2 my-5">

                    <!-- Welcome Text -->
                    <div class="text-center">
                        <h3 class="my-5">Account Type: <?= ucfirst($database->fetchRole($payload['Role_IDrole'])) ?></h3>
                        <h3 class="my-5">Organization: <?= $database->fetchOrgnization($payload['Organization']) ?></h3>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="m-0">Hi, <?= $name ?>!</h4>
                            <hr/>
                            <h6>Login Email: <?= $email ?></h6>
                        </div>
                        <div class="card-body text-center">

                        <?php
                        // if ($_SESSION['auth_type'] == 'github' || $_SESSION['auth_type'] == 'gmail') {
                        if ( (int) $payload['Role_IDrole'] !== 1 ) {

                            $findData = $database->getFilesData();

                            foreach ($findData as $data) {
                                ?>
                                <a href="/read-csv.php?file=<?= $data['DataURL'] ?>" class="btn btn-block btn-file"><?= $data['DataURL'] ?></a>
                                <?php
                            } ?>
                            <?php
                        } else {
                            ?>

                            <div class="row">
                                <div class="col-6 my-3">
                                    <iframe src="https://www.youtube.com/embed/MsXlZ_phGNY" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="col-6 my-3">
                                    <iframe src="https://www.youtube.com/embed/wkDiOCIX_xA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="col-6 my-3">
                                    <iframe src="https://www.youtube.com/embed/JtZ4pO0AzbM" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="col-6 my-3">
                                    <iframe src="https://www.youtube.com/embed/Ez2GeaMa4c8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>

                            <?php
                        }
                        
                        if ((int) $payload['Role_IDrole'] === 3 || (int) $payload['Role_IDrole'] === 4 ) {
                            ?>
                            
                            <a href="/news.php" class="btn btn-block btn-info">Health News RSS</a>

                            <?php
                        } ?>

                        </div>

                        <div class="card-footer">
                            <a href="/logout.php" class="btn btn-block btn-dark">Logout</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>