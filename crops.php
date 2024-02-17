<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (isset($_GET['crops_id'])) {
            $crops_id_spe = $_GET['crops_id'];
            $sql = "SELECT * FROM crops WHERE crops_id = :crops_id";
        }


        if (!isset($_GET['crops_id'])) {
            $sql = "SELECT * FROM crops ORDER BY crops_id DESC ";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($crops_id_spe)) {
                $stmt->bindParam(':crops_id', $crops_id_spe);
            }

            $stmt->execute();
            $crops = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($crops);
        }



        break;





    case "POST":
        $crops = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO crops (crops_id, crops_img, crops_name, planting_date, expected_harvest, created_at, variety, ogc) VALUES (:crops_id, :crops_img, :crops_name, :planting_date, :expected_harvest, :created_at, :variety, :ogc)";

        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d H:i:s');
        $stmt->bindParam(':crops_id', $crops->crops_id);
        $stmt->bindParam(':crops_img', $crops->crops_img);
        $stmt->bindParam(':crops_name', $crops->crops_name);

        $stmt->bindParam(':planting_date', $crops->planting_date);
        $stmt->bindParam(':expected_harvest', $crops->expected_harvest);

        $stmt->bindParam(':ogc', $crops->ogc);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':variety', $crops->variety);



        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "crops successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "crops failed"
            ];
        }

        echo json_encode($response);
        break;

    case "PUT":
        $crops = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE crops SET crops_img = :crops_img, crops_name = :crops_name, planting_date = :planting_date, expected_harvest = :expected_harvest, variety = :variety, ogc = :ogc WHERE crops_id = :crops_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':crops_id', $crops->crops_id);
        $stmt->bindParam(':crops_img', $crops->crops_img);
        $stmt->bindParam(':crops_name', $crops->crops_name);
        $stmt->bindParam(':planting_date', $crops->planting_date);
        $stmt->bindParam(':expected_harvest', $crops->expected_harvest);
        $stmt->bindParam(':variety', $crops->variety);
        $stmt->bindParam(':ogc', $crops->ogc);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "crops updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "crops update failed"
            ];
        }

        echo json_encode($response);
        break;

    case "DELETE":
        $crops = json_decode(file_get_contents('php://input'));
        $sql = "DELETE FROM crops WHERE crops_id = :crops_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':crops_id', $crops->crops_id);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "crops deleted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "crops delete failed"
            ];
        }

        echo json_encode($response);
        break;
}
