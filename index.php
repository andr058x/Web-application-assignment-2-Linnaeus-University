<?php

include __DIR__ . '/config.php';
unset($_SESSION['signup']);

if (!isset($_SESSION['auth_login']) || $_SESSION['auth_login'] != true) {
    $googleAuthUrl = $googleClient->createAuthUrl();
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Auth</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="section-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-6 offset-3 card-warpper">
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="m-0">Social Auth</h4>
                        </div>
                        <div class="card-body text-center">
                            <?php
                                if (isset($_SESSION['auth_login']) && $_SESSION['auth_login'] === true) {
                                ?>
                                <h4 class="mb-5">Your are logged in with <?= $_SESSION['auth_type'] ?>, Please logout and test another authentiaction.</h4>
                                
                                <div class="my-5">
                                    <?= $_SESSION['payload'] ?>
                                </div>

                                <a href="/logout.php" class="btn btn-block btn-success">Logout</a>
                                <?php
                                } else {
                                ?>
                                
                                <a href="<?= $twitterAuthUrl ?>" class="btn btn-block btn-twitter">Login with Twitter</a>
                                <a href="<?= $googleAuthUrl ?>" class="btn btn-block btn-gmail">Login with Google</a>
                                <a href="<?= $githubAuthUrl ?>" class="btn btn-block btn-github">Login with Github</a>

                                <?php
                                }
                            ?>

                        </div>
                        <div class="card-footer">
                            <p class="text-muted m-0 p-0">Created for assignment</p>
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