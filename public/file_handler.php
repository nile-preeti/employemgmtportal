<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? null;
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $address = $_POST['address'] ?? null;
    $action = $_POST['action'] ?? null; // "checkin", "checkout", or "fetch"

    $dataDir = "./data/";

    if (!is_dir($dataDir)) {
        mkdir($dataDir, 7777, true);
    }

    $filePath = $dataDir . $userId . ".json";

    if ($action === "fetch" && $userId) {
        if (file_exists($filePath)) {
            $data = json_decode(file_get_contents($filePath), true);
            echo json_encode([
                "status" => "success",
                "message" => "User records fetched successfully.",
                "data" => $data["records"] ?? []
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No records found for User ID: $userId"
            ]);
        }
        exit;
    }

    if ($userId && $latitude && $longitude && $address && $action) {
        $data = ["records" => []];

        if (file_exists($filePath)) {
            $existingData = json_decode(file_get_contents($filePath), true);
            $data["records"] = $existingData["records"] ?? [];
        }

        $today = date("Y-m-d");

        // Check if today's check-in or checkout already exists
        foreach ($data["records"] as $record) {
            if (strpos($record["time"], $today) === 0 && $record["action"] === $action) {
                echo json_encode([
                    "status" => "error",
                    "message" => "You have already performed a $action today."
                ]);
                exit;
            }
        }

        // Add new record
        $newRecord = [
            "time" => date("Y-m-d H:i:s"),
            "latitude" => $latitude,
            "longitude" => $longitude,
            "address" => $address,
            "action" => $action
        ];

        $data["records"][] = $newRecord;

        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

        echo json_encode([
            "status" => "success",
            "message" => ucfirst($action) . " recorded successfully.",
            "data" => $newRecord
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "All fields (userId, latitude, longitude, address, action) are required."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Use POST."
    ]);
}
?>
