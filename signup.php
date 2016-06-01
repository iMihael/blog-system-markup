<?php
    session_start();

    require "functions.php";

    if(isset($_SESSION['user'])) {
        header("Location: index.php");
    } else {

        if (!empty($_POST)) {
            if (
                !empty($_POST['email']) &&
                !empty($_POST['firstName']) &&
                !empty($_POST['lastName']) &&
                !empty($_POST['password']) &&
                !empty($_POST['passwordConfirm']) &&
                $_POST['password'] == $_POST['passwordConfirm']
            ) {
                if (!userExist($_POST['email'])) {
                    if (addUser($_POST['email'],
                        $_POST['firstName'],
                        $_POST['lastName'],
                        $_POST['password']
                    )) {

                        $_SESSION['user'] = true;
                        $_SESSION['firstName'] = $_POST['firstName'];
                        $_SESSION['lastName'] = $_POST['lastName'];
                        header("Location: index.php");
                    }
                }
            }

        }

        require "header.php";
        ?>

        <div class="container">

            <div class="col-lg-6 col-lg-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>Sign up</h2>
                    </div>
                    <div class="panel-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Email</label>
                                <input name="email" type="email" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input name="firstName" type="text" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input name="lastName" type="text" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input name="password" type="password" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input name="passwordConfirm" type="password" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary form-control"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <?php
        require "footer.php";

    }
?>