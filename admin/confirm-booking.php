<?php
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookingId = $_POST['booking_id'];

    $query = "UPDATE tblbooking SET Status=1 WHERE id=:bookingId";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "Booking Confirmed!";
    } else {
        echo "Error confirming booking.";
    }
}
?>
