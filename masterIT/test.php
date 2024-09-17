<?php
$merchant_id = "1225230";
$order_id = "Order12345";
$amount = "100";
$currency = "LKR";
$merchant_secret = "MjQ3MTA4OTMxMTI1NjE4NjIwMzkxMDcxNDk1NTA3MjQ1NzEwMTkzMA==";

$hash = strtoupper(
    md5(
        $merchant_id .
            $order_id .
            number_format($amount, 2, '.', '') .
            $currency .
            strtoupper(md5($merchant_secret))
    )
);

?>

<html>

<body>
    <form method="post" action="https://sandbox.payhere.lk/pay/checkout">
        <input type="hidden" name="merchant_id" value="1225230"> <!-- Replace your Merchant ID -->
        <input type="hidden" name="return_url" value="https://masterit.lk/">
        <input type="hidden" name="cancel_url" value="https://masterit.lk/">
        <input type="hidden" name="notify_url" value="https://masterit.lk/test2">
        <input type="text" name="order_id" value="Order12345">
        <input type="text" name="items" value="Toy car"><br>
        <input type="text" name="currency" value="LKR">
        <input type="text" name="amount" value="100">
        <br><br>Customer Details<br>
        <input type="text" name="first_name" value="Saman">
        <input type="text" name="last_name" value="Perera"><br>
        <input type="text" name="email" value="m.yousufriyaz@gmail.com">
        <input type="text" name="phone" value="0771234567"><br>
        <input type="text" name="address" value="No.1, Galle Road">
        <input type="text" name="city" value="Colombo">
        <input type="hidden" name="country" value="Sri Lanka">
        <input type="hidden" name="hash" value="<?= $hash ?>"> <!-- Replace with generated hash -->
        <input type="submit" value="Authorize">
    </form>
</body>

</html>