<?php
// opening all errors for website
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/Database.php';
$database = new Database;

session_start();

if (isset($_POST['email'], $_POST['auth'], $_POST['username'], $_POST['name'], $_POST['orgnization'], $_POST['role'])) {
    $auth = $_POST['auth'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $roleId = $_POST['role'];
    $orgnizationId = $_POST['orgnization'];

    $create = $database->createUser($auth, $name, $username, $email, $roleId, $orgnizationId);

    if (is_bool($create) && $create) {
        $user = $database->getLastUserDetail();
        $_SESSION['payload'] = json_encode($user);

        header("Location: /dashboard.php");
        exit;
    } else {
        $error = "We are unable to create your account." . $create;
    }
} elseif(isset($_GET['error'])) {
    $error = $_GET['error'];
} else {
    $payload = json_decode($_SESSION['payload'], true);
    $email = $payload['email'];
    $name = $payload['name'];
    $error = false;

    if (!$email) {
        $error = "Sorry, Your account can't created we are unable to find your email address via " . $_SESSION['auth_type'];
    } else {

        if ($_SESSION['auth_type'] === 'twitter') {
            $username = $payload['screen_name'];
        } elseif ($_SESSION['auth_type'] === 'gmail') {
            $username = $email;
        } elseif ($_SESSION['auth_type'] === 'github') {
            $username = $payload['login'];
        } else {
            $error = 'Error with auth_type login';
        }
    
        $ifExits = $database->checkIfEmailExist($email);
    
        if (is_bool($ifExits)) {
            if (!$ifExits) {
                $error = "This email is already registered, please <a href='/'>Login</a>";
            }
        } else {
            $error = $ifExits;
        }
    }

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
                            <h4 class="m-0">Sign Up Social Auth</h4>
                        </div>
                        <div class="card-body">

                            <?php
                            if ($error) {
                                ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                                <?php
                            } else {
                                ?>
                                <form class="text-left" method="post">

                                    <input type="hidden" name="email" value="<?= $email ?>">
                                    <input type="hidden" name="name" value="<?= $name ?>">
                                    <input type="hidden" name="username" value="<?= $username ?>">
                                    <input type="hidden" name="auth" value="<?= $_SESSION['auth_type'] ?>">
                                    
                                    <h3 class="text-center">Welcome <?= $name ?></h3>
                                    <hr/>

                                    <div class="form-group">
                                        <label for="orgnization">Select Orgnization</label>
                                        <select class="form-control" id="orgnization" name="orgnization">
                                            <?php
                                            foreach ($database->getOrginizations() as $row) {
                                                echo "<option value='" . $row['organizationID'] . "'>" . $row['name'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="role">Select Account Type</label>
                                        <select class="form-control" id="role" name="role">
                                            <?php
                                            foreach ($database->getRoles() as $row) {
                                                echo "<option value='" . $row['roleID'] . "'>" . ucfirst($row['name']) . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-block btn-info">Create Profile</button>
                                    
                                </form>
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