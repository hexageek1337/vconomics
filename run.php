<?php
require 'src/Vconomics.php';

$vconomics = new Vconomics();

echo $vconomics->color('blue', "[+]")." Vconomics Bot - Gidhan B.A & Denny Septian\n";
echo $vconomics->color('blue', "[+]")." Input Reff : ";
$reff = trim(fgets(STDIN));
echo $vconomics->color('blue', "[+]")." Tipe Email [random = random with generator.email|gmail = with your google account|denny = honey.cloudns.ph] : ";
$tipemail = trim(fgets(STDIN));

if ($tipemail != 'gmail') {
    echo $vconomics->color('blue', "[+]")." Input Jumlah Random Angka : ";
    $jumlahAngka = intval(trim(fgets(STDIN)));
} elseif ($tipemail === 'gmail') {
    echo $vconomics->color('blue', "[+]")." Username Gmail (without @gmail.com) : ";
    $userNameGmail = trim(fgets(STDIN));
    $jumlahAngka = 0;
}

$indexMail = 0;
Start:
if ($tipemail != 'gmail') {
    $base = $vconomics->gendata($tipemail, $jumlahAngka);
} elseif ($tipemail === 'gmail') {
    $base = $vconomics->gendata('gmail', $jumlahAngka, $userNameGmail, $indexMail);

    if ($indexMail === $base['dotMail']) {
        exit();
    }

    $indexMail++;
}

$email = $base['email'];
$pswd = 'Passku1010!!';
$name = $base['firstname'].' '.$base['lastname'];

echo $vconomics->color('blue', "\n[+]")." Email: $email\n";

$headers = array();
$headers[] = 'accept: application/json, text/plain, */*';
$headers[] = 'x-user-agent: '.$vconomics->randomAgent();
$headers[] = 'x-culture-code: EN';
$headers[] = 'x-location: ';
$headers[] = 'x-via: 3';
$headers[] = 'Content-Type: application/json;charset=utf-8';
$headers[] = 'Host: id.vscore.vn';
$headers[] = 'Connection: Keep-Alive';
$headers[] = 'User-Agent: okhttp/3.12.1';
$reg = $vconomics->curl('https://id.vscore.vn/api-v1/accounts/register/4', '{"fromReferralId":"'.$reff.'","fullName":"'.$name.'","password":"'.$pswd.'","rePassword":"'.$pswd.'","userName":"'.$email.'"}', $headers);

if (strpos($reg['body'], 'REGISTER_SUCCESSFUL_NEED_CONFIRM')) {
    // Delay 2 Seconds
    sleep(2);

    echo $vconomics->color('green', "[+]")." Registration successfuly!\n";
    echo $vconomics->color('yellow', "[+]")." Checking email";

    $emails = explode("@", $email);
    $emailx = "_ga=GA1.2.1780620157.1640362675; _gid=GA1.2.44486953.1641566799; surl=".trim($emails[1])."%2F".trim($emails[0]);
    $xyz = array();
    $xyz[] = 'Connection: Keep-Alive';
    $xyz[] = 'User-Agent: '.$vconomics->randomAgent();
    $xyz[] = 'sec-ch-ua: "Not A;Brand";v="99", "Chromium";v="96", "Google Chrome";v="96"';
    $xyz[] = 'sec-ch-ua-mobile: ?0';
    $xyz[] = 'sec-ch-ua-platform: "Windows"';
    $xyz[] = 'Upgrade-Insecure-Requests: 1';
    $xyz[] = 'Cookie: '.$emailx;

    if ($tipemail != 'gmail') {
        $a = true;
        $b = 0;
        while ($a) {
            if ($b > 9) {
                echo $vconomics->color('red', "\n[+]")." Bad email!\n";
                goto Start;
            }

            sleep(3);

            $cek = $vconomics->curl('https://generator.email/', null, $xyz, true);
            
            if (strpos($cek['body'], 'Vconomics')) {
                // Save Result with No Verify
                $vconomics->generateResult($email, $pswd, 'result_noverify.txt');
                // GET OTP
                $otp = $vconomics->get_between($cek[1], '"color: #fa7800; font-weight: bold; text-align: center; font-size: 40px">', "</p>");
                echo $vconomics->color('green', " [$otp]\n");
                $a = false;
            } else {
                echo ".";
                $b++;
            }
        }
    } elseif ($tipemail === 'gmail') {
        sleep(2);
        
        echo $vconomics->color('blue', "\n[+]")." OTP: ";
        $otp = intval(trim(fgets(STDIN)));
    }

    $token = json_decode($reg['body'])->data->token;

    $headersOTP = array();
    $headersOTP[] = 'accept: application/json, text/plain, */*';
    $headersOTP[] = 'x-user-agent: '.$vconomics->randomAgent();
    $headersOTP[] = 'x-culture-code: EN';
    $headersOTP[] = 'x-location: ';
    $headersOTP[] = 'x-via: 3';
    $headersOTP[] = 'Content-Type: application/json;charset=utf-8';
    $headersOTP[] = 'Host: id.vscore.vn';
    $headersOTP[] = 'Connection: Keep-Alive';
    $headersOTP[] = 'User-Agent: okhttp/3.12.1';

    sleep(2);

    $ver = $vconomics->curl('https://id.vscore.vn/api-v1/tokens/verify-otp', '{"otp":"'.$otp.'","otpType":1,"validateToken":"'.$token.'"}', $headersOTP);

    if (strpos($ver['body'], 'VERIFY_OTP_SUCCESS')) {
        $vconomics->generateResult($email, $pswd, 'result.txt');
        echo $vconomics->color('green', "[+]")." Verification successfuly!\n";
    } else {
        echo $vconomics->color('red', "[+]")." Error: ".$ver['body']."\n";
    }

    goto Start;
} else {
    echo $vconomics->color('red', "[+]")." Error: ".$reg['body']."\n";
    goto Start;
}