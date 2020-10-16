<?php
session_start();

if (!isset($_SESSION['auth_login']) && $_SESSION['auth_login'] !== true) {
    header("Location: /");
    exit;
}

$xml = simplexml_load_file( __DIR__ . '/rss.xml');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Feed</title>

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
                    <div class="text-center mb-5">
                        <h3><?= $xml->channel->title ?></h3>
                        <p><?= $xml->channel->description ?></p>
                        <a href="<?= $xml->channel->link ?>"><?= $xml->channel->title ?></a>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <a href="/dashboard.php" class="btn btn-block btn-dark">Go Back</a>
                        </div>

                        <div class="card-body text-center">
                            
                            <?php
                            foreach ($xml->channel->item as $item) {
                                ?>
                                    <h5 class="card-title"><?= $item->title ?></h5>
                                    <p class="text-muted">Published on: <?= $item->pubDate ?></p>
                                    <p class="card-text"><?= $item->description ?></p>
                                    <a href="<?= $item->link ?>" class="btn btn-link">Link</a> |
                                    <a href="<?= $item->comments ?>" class="btn btn-link">Comment</a> |
                                    <a href="<?= $item->guid ?>" class="btn btn-link">Guid</a>
                                    <hr>
                                <?php
                            } ?>

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