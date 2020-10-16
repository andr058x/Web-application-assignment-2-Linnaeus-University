<?php
session_start();

if (!isset($_SESSION['auth_login']) && $_SESSION['auth_login'] !== true) {
    header("Location: /");
    exit;
}

if (isset($_GET['file'])) {
    $fileName = $_GET['file'];
    
    $file = fopen(__DIR__ . "/files/{$fileName}.csv", 'r');
    $csvData = fgetcsv($file);
    $csvFileData = [];
    $index = 0;
    
    foreach ($csvData as $dt) {
        
        if (strpos($dt, "\r")) {
            $explode = explode("\r", $dt);
            $csvFileData[$index][] = $explode[0];
            $index++;
            $csvFileData[$index][] = $explode[1];
            continue;
        }

        $csvFileData[$index][] = $dt;
    }
    unset($csvData);
    fclose($file);
    
} else {
    header("Location: /dashboard.php");
    exit;
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV File Read <?= $fileName . '.csv' ?></title>

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
                        <h3>CSV File Read <?= $fileName . '.csv' ?></h3>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <a href="/dashboard.php" class="btn btn-block btn-dark">Go Back</a>
                        </div>

                        <div class="card-body text-center">
                            <table class="table table-striped">
                            <?php
                            foreach ($csvFileData as $key => $trData) {
                                ?> <tr> <?php
                                foreach ($trData as $td) {
                                    echo $key === 0 ? "<th>{$td}</th>" : "<td>{$td}</td>";
                                }
                                ?> </tr> <?php
                            } ?>
                            </table>
                        </div>

                        <div class="card-footer">
                            <a href="/dashboard.php" class="btn btn-block btn-dark">Go Back</a>
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