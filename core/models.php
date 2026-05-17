<?php

// -----------------------------------------------
// activity logs
// -----------------------------------------------


function insertLog($pdo, $performed_by, $action, $table_affected, $record_description)
{
    $sql = "INSERT INTO activity_logs (performed_by, action, table_affected, record_description)
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$performed_by, $action, $table_affected, $record_description]);
}

function getAllLogs($pdo)
{
    $sql = "SELECT * FROM activity_logs ORDER BY date_performed DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}


// -----------------------------------------------
// funeral directors (parent entity)
// -----------------------------------------------

// inserts a new director and logs the action
function insertDirector(
    $pdo,
    $username,
    $first_name,
    $last_name,
    $date_of_birth,
    $specialization,
    $added_by
) {

    $sql = "INSERT INTO funeral_directors (username, first_name, last_name, 
        date_of_birth, specialization, added_by) VALUES(?,?,?,?,?,?)";

    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([
        $username,
        $first_name,
        $last_name,
        $date_of_birth,
        $specialization,
        $added_by
    ]);

    if ($executeQuery) {
        // log who added this director
        insertLog(
            $pdo,
            $added_by,
            'CREATE',
            'funeral_directors',
            "Added director: $first_name $last_name (username: $username)"
        );
        return true;
    }
}

// updates an existing director's info and logs the action
function updateDirector(
    $pdo,
    $first_name,
    $last_name,
    $date_of_birth,
    $specialization,
    $director_id,
    $last_updated_by
) {

    $sql = "UPDATE funeral_directors
                SET first_name = ?,
                    last_name = ?,
                    date_of_birth = ?, 
                    specialization = ?,
                    last_updated_by = ?
                WHERE director_id = ?
            ";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([
        $first_name,
        $last_name,
        $date_of_birth,
        $specialization,
        $last_updated_by,
        $director_id
    ]);

    if ($executeQuery) {
        // log who updated this director
        insertLog(
            $pdo,
            $last_updated_by,
            'UPDATE',
            'funeral_directors',
            "Updated director ID $director_id: $first_name $last_name"
        );
        return true;
    }

}

// deletes a director and all their services, then logs the action
function deleteDirector($pdo, $director_id, $deleted_by)
{
    // Grab name before deleting for the log
    $dirRow = getDirectorByID($pdo, $director_id);
    $dirName = $dirRow ? $dirRow['first_name'] . ' ' . $dirRow['last_name'] : "ID $director_id";

    // delete the director's services first (child records)
    $deleteDirectorProj = "DELETE FROM services WHERE director_id = ?";
    $deleteStmt = $pdo->prepare($deleteDirectorProj);
    $executeDeleteQuery = $deleteStmt->execute([$director_id]);

    if ($executeDeleteQuery) {
        $sql = "DELETE FROM funeral_directors WHERE director_id = ?";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute([$director_id]);

        if ($executeQuery) {
            // log who deleted this director
            insertLog(
                $pdo,
                $deleted_by,
                'DELETE',
                'funeral_directors',
                "Deleted director: $dirName (ID: $director_id) and their associated services"
            );
            return true;
        }

    }

}




function getAllDirectors($pdo)
{
    $sql = "SELECT * FROM funeral_directors";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}

// returns a single director by their id
function getDirectorByID($pdo, $director_id)
{
    $sql = "SELECT * FROM funeral_directors WHERE director_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$director_id]);

    if ($executeQuery) {
        return $stmt->fetch();
    }
}

// searches directors by name, username, or specialization
function searchDirectors($pdo, $keyword)
{
    $sql = "SELECT * FROM funeral_directors
            WHERE first_name LIKE ? OR last_name LIKE ? OR username LIKE ? OR specialization LIKE ?";
    $stmt = $pdo->prepare($sql);
    $like = '%' . $keyword . '%';
    $stmt->execute([$like, $like, $like, $like]);
    return $stmt->fetchAll();
}

// searches services by name, package type, or assigned director name
function searchServices($pdo, $keyword)
{
    $sql = "SELECT 
                services.service_id AS service_id,
                services.service_name AS service_name,
                services.package_type AS package_type,
                services.added_by AS added_by,
                services.last_updated_by AS last_updated_by,
                services.date_added AS date_added,
                services.director_id AS director_id,
                CONCAT(funeral_directors.first_name,' ',funeral_directors.last_name) AS assigned_director
            FROM services
            JOIN funeral_directors ON services.director_id = funeral_directors.director_id
            WHERE services.service_name LIKE ? OR services.package_type LIKE ?
                  OR funeral_directors.first_name LIKE ? OR funeral_directors.last_name LIKE ?";
    $stmt = $pdo->prepare($sql);
    $like = '%' . $keyword . '%';
    $stmt->execute([$like, $like, $like, $like]);
    return $stmt->fetchAll();
}



// returns all services belonging to a specific director
function getServicesByDirector($pdo, $director_id)
{

    $sql = "SELECT 
                services.service_id AS service_id,
                services.service_name AS service_name,
                services.package_type AS package_type,
                services.added_by AS added_by,
                services.last_updated_by AS last_updated_by,
                services.date_added AS date_added,
                CONCAT(funeral_directors.first_name,' ',funeral_directors.last_name) AS assigned_director
            FROM services
            JOIN funeral_directors ON services.director_id = funeral_directors.director_id
            WHERE services.director_id = ? 
            GROUP BY services.service_name;
            ";

    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$director_id]);
    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}


function insertService($pdo, $service_name, $package_type, $director_id, $added_by)
{
    $sql = "INSERT INTO services (service_name, package_type, director_id, added_by) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$service_name, $package_type, $director_id, $added_by]);
    if ($executeQuery) {
        $dirRow = getDirectorByID($pdo, $director_id);
        $dirName = $dirRow ? $dirRow['first_name'] . ' ' . $dirRow['last_name'] : "Director ID $director_id";

        // log who added this service
        insertLog(
            $pdo,
            $added_by,
            'CREATE',
            'services',
            "Added service '$service_name' (package: $package_type) under director: $dirName"
        );
        return true;
    }

}

// returns a single service with its assigned director info
function getServiceByID($pdo, $service_id)
{
    $sql = "SELECT 
                services.service_id AS service_id,
                services.service_name AS service_name,
                services.package_type AS package_type,
                services.added_by AS added_by,
                services.last_updated_by AS last_updated_by,
                services.date_added AS date_added,
                services.director_id AS director_id,
                CONCAT(funeral_directors.first_name,' ',funeral_directors.last_name) AS assigned_director
            FROM services
            JOIN funeral_directors ON services.director_id = funeral_directors.director_id
            WHERE services.service_id  = ? 
            GROUP BY services.service_name";

    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$service_id]);
    if ($executeQuery) {
        return $stmt->fetch();
    }
}

// updates a service's name and package type, then logs the action
function updateService($pdo, $service_name, $package_type, $service_id, $last_updated_by)
{
    // save old name for the log message
    $old = getServiceByID($pdo, $service_id);
    $oldName = $old ? $old['service_name'] : "ID $service_id";

    $sql = "UPDATE services
            SET service_name = ?,
                package_type = ?,
                last_updated_by = ?
            WHERE service_id = ?
            ";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$service_name, $package_type, $last_updated_by, $service_id]);

    if ($executeQuery) {
        // log who updated this service and what changed
        insertLog(
            $pdo,
            $last_updated_by,
            'UPDATE',
            'services',
            "Updated service ID $service_id: '$oldName' → '$service_name' (package: $package_type)"
        );
        return true;
    }
}

// deletes a service and logs the action
function deleteService($pdo, $service_id, $deleted_by)
{
    // get service name before deleting for the log message
    $old = getServiceByID($pdo, $service_id);
    $oldName = $old ? $old['service_name'] : "ID $service_id";

    $sql = "DELETE FROM services WHERE service_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$service_id]);
    if ($executeQuery) {
        // log who deleted this service
        insertLog(
            $pdo,
            $deleted_by,
            'DELETE',
            'services',
            "Deleted service '$oldName' (ID: $service_id)"
        );
        return true;
    }
}


// -----------------------------------------------
// users
// -----------------------------------------------

// registers a new user after checking if username already exists
function insertNewUser($pdo, $username, $password, $first_name, $last_name, $address, $age)
{
    // check if username is already taken
    $checkUserSql = "SELECT * FROM user_passwords WHERE username = ?";
    $checkUserSqlStmt = $pdo->prepare($checkUserSql);
    $checkUserSqlStmt->execute([$username]);

    if ($checkUserSqlStmt->rowCount() == 0) {
        $sql = "INSERT INTO user_passwords (username, password, first_name, last_name, address, age) VALUES(?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute([$username, $password, $first_name, $last_name, $address, $age]);

        if ($executeQuery) {
            // log the new registration
            insertLog($pdo, $username, 'CREATE', 'user_passwords', "Registered new user: $username");
            $_SESSION['message'] = "User successfully inserted";
            return true;
        } else {
            $_SESSION['message'] = "An error occured from the query";
        }
    } else {
        $_SESSION['message'] = "User already exists";
    }
}

// validates login credentials and starts a session if correct
function loginUser($pdo, $username, $password)
{
    $sql = "SELECT * FROM user_passwords WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->rowCount() == 1) {
        $userInfoRow = $stmt->fetch();
        $usernameFromDB = $userInfoRow['username'];
        $passwordFromDB = $userInfoRow['password'];

        if ($password == $passwordFromDB) {
            // store username in session and log the login
            $_SESSION['username'] = $usernameFromDB;
            insertLog($pdo, $username, 'READ', 'user_passwords', "User '$username' logged in");
            $_SESSION['message'] = "Login successful!";
            return true;
        } else {
            $_SESSION['message'] = "Password is invalid, but user exists";
        }
    }

    if ($stmt->rowCount() == 0) {
        $_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
    }
}

// returns all registered users
function getAllUsers($pdo)
{
    $sql = "SELECT * FROM user_passwords";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}

// returns a single user by their id
function getUserByID($pdo, $user_id)
{
    $sql = "SELECT * FROM user_passwords WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$user_id]);
    if ($executeQuery) {
        return $stmt->fetch();
    }
}

?>