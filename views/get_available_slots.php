<?php
// include '../config/database.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $tanggal_reservasi = $_POST['tanggal_reservasi'];
//     $service_type = $_POST['service_type'];

//     // Check slot availability
//     $stmt = $conn->prepare("SELECT slot_reservasi, COUNT(*) as count FROM reservasi WHERE tanggal_reservasi = ? AND jenis_layanan = ? GROUP BY slot_reservasi");
//     $stmt->bind_param("ss", $tanggal_reservasi, $service_type);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $slots = ['pagi' => 0, 'sore' => 0]; // Initialize slots with 0

//     while ($row = $result->fetch_assoc()) {
//         if ($row['slot_reservasi'] === 'pagi') {
//             $slots['pagi'] = $row['count'];
//         } else if ($row['slot_reservasi'] === 'sore') {
//             $slots['sore'] = $row['count'];
//         }
//     }

//     $available_slots = [];
//     foreach ($slots as $slot => $count) {
//         $available_slots[] = ['slot' => $slot, 'count' => $count];
//     }

//     echo json_encode($available_slots);

//     $stmt->close();
//     $conn->close();
// }
?>
<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_reservasi = $_POST['tanggal_reservasi'];
    $service_type = $_POST['service_type'];

    $slots = [];

    // Check slot availability for pemeriksaan pagi
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservasi WHERE tanggal_reservasi = ? AND jenis_layanan = 'pemeriksaan' AND slot_reservasi = 'pagi'");
    $stmt->bind_param("s", $tanggal_reservasi);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $slots['pemeriksaan_pagi'] = $row['count'];

    // Check slot availability for pemeriksaan sore
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservasi WHERE tanggal_reservasi = ? AND jenis_layanan = 'pemeriksaan' AND slot_reservasi = 'sore'");
    $stmt->bind_param("s", $tanggal_reservasi);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $slots['pemeriksaan_sore'] = $row['count'];

    // Check slot availability for grooming pagi
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservasi WHERE tanggal_reservasi = ? AND jenis_layanan = 'grooming' AND slot_reservasi = 'pagi'");
    $stmt->bind_param("s", $tanggal_reservasi);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $slots['grooming_pagi'] = $row['count'];

    // Check slot availability for grooming sore
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservasi WHERE tanggal_reservasi = ? AND jenis_layanan = 'grooming' AND slot_reservasi = 'sore'");
    $stmt->bind_param("s", $tanggal_reservasi);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $slots['grooming_sore'] = $row['count'];

    // Check slot availability for pet hotel 
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservasi WHERE jenis_layanan = 'pet_hotel'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $slots['pet_hotel'] = $row['count'];


    echo json_encode($slots);

    $stmt->close();
    $conn->close();
}
?>