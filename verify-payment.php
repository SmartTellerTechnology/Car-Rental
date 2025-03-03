<?php
require 'config.php'; // Database connection
require 'mailer.php'; // Email function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reference = $_POST['reference'];
    $booking_id = $_POST['booking_id'];

    // Verify payment with Paystack API
    $url = "https://api.paystack.co/transaction/verify/" . $reference;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["sk_test_b8ed668057396fcc12216c7e61a316b3d721be6c"]);
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);

    if ($result['status'] && $result['data']['status'] == "success") {
        // Payment successful, update booking in the database
        $stmt = $pdo->prepare("UPDATE bookings SET status='Confirmed', payment_status='Paid' WHERE id=?");
        $stmt->execute([$booking_id]);

        // Fetch user details
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id=?");
        $stmt->execute([$booking_id]);
        $booking = $stmt->fetch();

        // Send confirmation email
        $subject = "Payment Successful - Booking Confirmation";
        $message = "Hello " . $booking['user_name'] . ",\n\nYour payment for booking #" . $booking_id . " has been received.\n\nBooking Details:\nCheck-in: " . $booking['check_in'] . "\nCheck-out: " . $booking['check_out'] . "\nAmount Paid: ₦" . $booking['amount'] . "\n\nThank you for choosing us!";
        
        sendEmail($booking['user_email'], $subject, $message);

        echo "Payment successful and booking confirmed!";
    } else {
        echo "Payment verification failed.";
    }
}
?>
