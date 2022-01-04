<?php
require 'src/Vconomics.php';

$vconomics = new Vconomics();

echo $vconomics->color('blue', "[+]")." Vconomics Bot - Gidhan B.A & Denny Septian\n";
echo $vconomics->color('blue', "[+]")." Input Reff: ";
$reff = trim(fgets(STDIN));

Start:
$domain = 'honey.cloudns.ph';

$base = $vconomics->gendata($domain);

$email = $base['email'];
$pswd = 'Passku1010!!';
$name = $base['firstname'].' '.$base['lastname'];

echo $vconomics->color('blue', "\n[+]")." Email: $email\n";

$headers = array();
$headers[] = 'User-Agent: '.$vconomics->randomAgent();
$headers[] = 'Content-Type: application/json';
$headers[] = 'x-culture-code: EN';
$headers[] = 'x-location: ';
$reg = $vconomics->curl('https://id.vscore.vn/api-v1/accounts/register/4', '{"fromReferralId":"'.$reff.'","fullName":"'.$name.'","password":"'.$pswd.'","rePassword":"'.$pswd.'","userName":"'.$email.'"}', $headers);

if (strpos($reg[1], 'REGISTER_SUCCESSFUL_NEED_CONFIRM')) {
    // Delay 1,2 Seconds
    sleep(1.2);
    $vconomics->generateResult($email, $pswd, 'result_noverify.txt');
    
    $token = json_decode($reg[1])->data->token;
    echo $vconomics->color('green', "[+]")." Registration successfuly!\n";
    echo $vconomics->color('yellow', "[+]")." Checking email";

    $emails = explode("@", $email);
    $emailx = "surl=".trim($emails[1])."%2F".trim($emails[0]);
    $xyz = array();
    $xyz[] = 'User-Agent: '.$vconomics->randomAgent();
    $xyz[] = 'Cookie: '.$emailx;

    $a = true;
    $b = 0;
    while ($a) {
        if ($b > 7) {
            echo $vconomics->color('red', "\n[+]")." Bad email!\n";
            goto Start;
        }

        $cek = $vconomics->curl('https://generator.email/', null, $xyz, true);
        
        if (strpos($cek[1], 'Vconomics')) {
            $otp = $vconomics->get_between($cek[1], '"color: #fa7800; font-weight: bold; text-align: center; font-size: 40px">', "</p>");
            echo $vconomics->color('green', " [$otp]\n");
            $a = false;
        } else {
            echo ".";
            $b++;
        }
    }

    $headersOTP = array();
    $headersOTP[] = 'User-Agent: okhttp/3.12.1';
    $headersOTP[] = 'Content-Type: application/json';
    $headersOTP[] = 'x-culture-code: EN';
    $headersOTP[] = 'x-location: ';
    $ver = $vconomics->curl('https://id.vscore.vn/api-v1/tokens/verify-otp', '{"otp":"'.$otp.'","otpType":1,"validateToken":"'.$token.'"}', $headersOTP);
    
    if (strpos($ver[1], 'VERIFY_OTP_SUCCESS')) {
        $vconomics->generateResult($email, $pswd, 'result.txt');
        echo $vconomics->color('green', "[+]")." Verification successfuly!\n";
    } else {
        echo $vconomics->color('red', "[+]")." Error: ".$ver[1]."\n";
    }

    // Delay 1 Seconds
    sleep(1);

    goto Start;
} else {
    echo $vconomics->color('red', "[+]")." Error: ".$reg[1]."\n";
    goto Start;
}