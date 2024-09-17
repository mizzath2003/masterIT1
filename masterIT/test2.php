<?php
//SMS API Info
$smsUsername = "ChronicleHistory";
$smsPassword = "M.yousuf1234";
$smsSourceAddress = "Master Acad";
$SMSURLMessageKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ODk5MSwiaWF0IjoxNjcwNjY0OTIwLCJleHAiOjQ3OTQ4NjczMjB9.VwZhkJBST-jjIUGi9EtbRTvEFGZVj_AGy2pg4ZXh1UY";

function createSMScapaign($message, $phone)
{
    global $SMSURLMessageKey, $smsSourceAddress;
    $smsSend = file_get_contents("https://e-sms.dialog.lk/api/v1/message-via-url/create/url-campaign?esmsqk=$SMSURLMessageKey&source_address=" . urlencode($smsSourceAddress) . "&message=" . urlencode($message) . "&list=$phone");
    echo $smsSend;
}

$smsMessageToSend = "";
foreach ($_POST as $key => $value) {
    $smsMessageToSend .= htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
}
createSMScapaign($smsMessageToSend, "776163181");