<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json');

require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['PATH_INFO'], '/'));

$db = new db();
$conn = $db->connect();

switch ($uri[0]) {
    case 'roles':
        handleRoles($conn, $method, $uri);
        break;
    case 'users':
        handleUsers($conn, $method, $uri);
        break;
    case 'testcases':
        handleTestCases($conn, $method, $uri);
        break;
    case 'userstatuses':
        handleUserStatuses($conn, $method, $uri);
        break;
    default:
        echo json_encode(["error" => "Unknown endpoint"]);
        break;
}

function handleRoles($conn, $method, $uri) {
    switch ($method) {
        case 'GET':
            if (isset($uri[1])) {
                $stmt = $conn->prepare("SELECT * FROM Roles WHERE roleID = ?");
                $stmt->execute([$uri[1]]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $conn->query("SELECT * FROM Roles");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $conn->prepare("INSERT INTO Roles (description, roleName) VALUES (?, ?)");
            $stmt->execute([$data['description'], $data['roleName']]);
            echo json_encode(["message" => "Role created"]);
            break;
        case 'PUT':
            if (isset($uri[1])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $conn->prepare("UPDATE Roles SET description = ?, roleName = ? WHERE roleID = ?");
                $stmt->execute([$data['description'], $data['roleName'], $uri[1]]);
                echo json_encode(["message" => "Role updated"]);
            }
            break;
        case 'DELETE':
            if (isset($uri[1])) {
                $stmt = $conn->prepare("DELETE FROM Roles WHERE roleID = ?");
                $stmt->execute([$uri[1]]);
                echo json_encode(["message" => "Role deleted"]);
            }
            break;
        default:
            echo json_encode(["error" => "Invalid method"]);
            break;
    }
}

function handleUsers($conn, $method, $uri) {
    switch ($method) {
        case 'GET':
            if (isset($uri[1])) {
                $stmt = $conn->prepare("SELECT * FROM Users WHERE userID = ?");
                $stmt->execute([$uri[1]]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $conn->query("SELECT * FROM Users");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $conn->prepare("INSERT INTO Users (email, username, password, roleID, resetToken) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['email'], $data['username'], $data['password'], $data['roleID'], $data['resetToken']]);
            echo json_encode(["message" => "User created"]);
            break;
        case 'PUT':
            if (isset($uri[1])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $conn->prepare("UPDATE Users SET email = ?, username = ?, password = ?, roleID = ?, resetToken = ? WHERE userID = ?");
                $stmt->execute([$data['email'], $data['username'], $data['password'], $data['roleID'], $data['resetToken'], $uri[1]]);
                echo json_encode(["message" => "User updated"]);
            }
            break;
        case 'DELETE':
            if (isset($uri[1])) {
                $stmt = $conn->prepare("DELETE FROM Users WHERE userID = ?");
                $stmt->execute([$uri[1]]);
                echo json_encode(["message" => "User deleted"]);
            }
            break;
        default:
            echo json_encode(["error" => "Invalid method"]);
            break;
    }
}

function handleTestCases($conn, $method, $uri) {
    switch ($method) {
        case 'GET':
            if (isset($uri[1])) {
                $stmt = $conn->prepare("SELECT * FROM TestCases WHERE idtest_cases = ?");
                $stmt->execute([$uri[1]]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $conn->query("SELECT * FROM TestCases");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $conn->prepare("INSERT INTO TestCases (test_desc, deadline, dateUpdated, projectId, reason, testCaseName, dateCreated, smartContractID, overallStatus, username, createdBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['test_desc'], $data['deadline'], $data['dateUpdated'], $data['projectId'], $data['reason'], $data['testCaseName'], $data['dateCreated'], $data['smartContractID'], $data['overallStatus'], $data['username'], $data['createdBy']]);
            echo json_encode(["message" => "Test case created"]);
            break;
        case 'PUT':
            if (isset($uri[1])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $conn->prepare("UPDATE TestCases SET test_desc = ?, deadline = ?, dateUpdated = ?, projectId = ?, reason = ?, testCaseName = ?, dateCreated = ?, smartContractID = ?, overallStatus = ?, username = ?, createdBy = ? WHERE idtest_cases = ?");
                $stmt->execute([$data['test_desc'], $data['deadline'], $data['dateUpdated'], $data['projectId'], $data['reason'], $data['testCaseName'], $data['dateCreated'], $data['smartContractID'], $data['overallStatus'], $data['username'], $data['createdBy'], $uri[1]]);
                echo json_encode(["message" => "Test case updated"]);
            }
            break;
        case 'DELETE':
            if (isset($uri[1])) {
                $stmt = $conn->prepare("DELETE FROM TestCases WHERE idtest_cases = ?");
                $stmt->execute([$uri[1]]);
                echo json_encode(["message" => "Test case deleted"]);
            }
            break;
        default:
            echo json_encode(["error" => "Invalid method"]);
            break;
    }
}

function handleUserStatuses($conn, $method, $uri) {
    switch ($method) {
        case 'GET':
            if (isset($uri[1]) && isset($uri[2])) {
                $stmt = $conn->prepare("SELECT * FROM UserStatuses WHERE testCaseID = ? AND userID = ?");
                $stmt->execute([$uri[1], $uri[2]]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $stmt = $conn->query("SELECT * FROM UserStatuses");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $conn->prepare("INSERT INTO UserStatuses (testCaseID, userID, status) VALUES (?, ?, ?)");
            $stmt->execute([$data['testCaseID'], $data['userID'], $data['status']]);
            echo json_encode(["message" => "User status created"]);
            break;
        case 'PUT':
            if (isset($uri[1]) && isset($uri[2])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $conn->prepare("UPDATE UserStatuses SET status = ? WHERE testCaseID = ? AND userID = ?");
                $stmt->execute([$data['status'], $uri[1], $uri[2]]);
                echo json_encode(["message" => "User status updated"]);
            }
            break;
        case 'DELETE':
            if (isset($uri[1]) && isset($uri[2])) {
                $stmt = $conn->prepare("DELETE FROM UserStatuses WHERE testCaseID = ? AND userID = ?");
                $stmt->execute([$uri[1], $uri[2]]);
                echo json_encode(["message" => "User status deleted"]);
            }
            break;
        default:
            echo json_encode(["error" => "Invalid method"]);
            break;
    }
}
?>
