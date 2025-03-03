<?php
require 'config.php';
require 'mailer.php';

$now = date("Y-m-d H:i:s");
$reminder_time = date("Y-m-d H:i:s", strtotime("+1 day")); // 24 hours before check-in

$stmt = $pdo->prepare("SELECT * FROM bookings WHERE check_in BETWEEN ? AND ? AND status='Confirmed'");
$stmt->execute([$now, $reminder_time]);
$bookings = $stmt->fetchAll();

foreach ($bookings as $booking) {
    $subject = "Reminder: Your Booking Starts Soon!";
    $message = "Hello " . $booking['user_name'] . ",\n\nThis is a friendly reminder that your rental period will begin on " . $booking['check_in'] . ".\n\nThank you for booking with us!";
    sendEmail($booking['user_email'], $subject, $message);
}
?>
