<?php

require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertDirectorBtn'])) {

    $query = insertDirector(
        $pdo,
        $_POST['username'],
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['dateOfBirth'],
        $_POST['specialization'],
        $_SESSION['username']
    );

    if ($query) {
        header("Location: ../index.php");
    } else {
        echo "Insertion failed";
    }

}


if (isset($_POST['editDirectorBtn'])) {
    $query = updateDirector(
        $pdo,
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['dateOfBirth'],
        $_POST['specialization'],
        $_GET['director_id'],
        $_SESSION['username']
    );

    if ($query) {
        header("Location: ../index.php");
    } else {
        echo "Edit failed";
        ;
    }

}




if (isset($_POST['deleteDirectorBtn'])) {
    $query = deleteDirector($pdo, $_GET['director_id'], $_SESSION['username']);

    if ($query) {
        header("Location: ../index.php");
    } else {
        echo "Deletion failed";
    }
}




if (isset($_POST['insertNewServiceBtn'])) {
    $query = insertService($pdo, $_POST['serviceName'], $_POST['packageType'], $_GET['director_id'], $_SESSION['username']);

    if ($query) {
        header("Location: ../viewprojects.php?director_id=" . $_GET['director_id']);
    } else {
        echo "Insertion failed";
    }
}




if (isset($_POST['editServiceBtn'])) {
    $query = updateService($pdo, $_POST['serviceName'], $_POST['packageType'], $_GET['service_id'], $_SESSION['username']);

    if ($query) {
        header("Location: ../viewprojects.php?director_id=" . $_GET['director_id']);
    } else {
        echo "Update failed";
    }

}




if (isset($_POST['deleteServiceBtn'])) {
    $query = deleteService($pdo, $_GET['service_id'], $_SESSION['username']);

    if ($query) {
        header("Location: ../viewprojects.php?director_id=" . $_GET['director_id']);
    } else {
        echo "Deletion failed";
    }
}

if (isset($_POST['registerUserBtn'])) {
    $username = $_POST['username'];
    $rawPassword = $_POST['password'];
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $age = $_POST['age'];

    // check that no fields are empty
    if (!empty($username) && !empty($rawPassword) && !empty($firstName) && !empty($lastName) && !empty($address) && !empty($age)) {

        // check if passwords match
        if ($rawPassword !== $confirmPassword) {
            $_SESSION['message'] = "Passwords do not match. Please try again.";
            header("Location: ../register.php");
            exit();
        }

        // check password strength: min 8 chars, must have number, uppercase, and lowercase
        if (strlen($rawPassword) < 8 || !preg_match("/[0-9]/", $rawPassword) || !preg_match("/[A-Z]/", $rawPassword) || !preg_match("/[a-z]/", $rawPassword)) {
            $_SESSION['message'] = "Password must be at least 8 characters long and contain at least one number, one uppercase letter, and one lowercase letter.";
            header("Location: ../register.php");
            exit();
        }

        $password = sha1($rawPassword);
        $insertQuery = insertNewUser($pdo, $username, $password, $firstName, $lastName, $address, $age);
        if ($insertQuery) {
            header("Location: ../login.php");
        } else {
            header("Location: ../register.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure all input fields are not empty for registration!";
        header("Location: ../register.php");
    }
}

if (isset($_POST['loginUserBtn'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $loginQuery = loginUser($pdo, $username, $password);
        if ($loginQuery) {
            header("Location: ../index.php");
        } else {
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
        header("Location: ../login.php");
    }
}

if (isset($_GET['logoutAUser'])) {
    $loggedOutUser = $_SESSION['username'];

    // log the logout event before destroying the session
    insertLog($pdo, $loggedOutUser, 'READ', 'user_passwords', "User '$loggedOutUser' logged out");
    unset($_SESSION['username']);
    $_SESSION['message'] = "logout successfully";
    header('Location: ../login.php');
}

?>