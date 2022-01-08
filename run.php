<?php
require 'src/Vconomics.php';

$vconomics = new Vconomics();

echo $vconomics->color('blue', "[+]")." Vconomics Bot - Gidhan B.A & Denny Septian\n";
echo $vconomics->color('blue', "[+]")." Input Reff: ";
$reff = trim(fgets(STDIN));
echo $vconomics->color('blue', "[+]")." Input Jumlah Random Angka: ";
$jumlahAngka = intval(trim(fgets(STDIN)));

Start:
$base = $vconomics->gendata('random', $jumlahAngka);

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
        if ($b > 9) {
            echo $vconomics->color('red', "\n[+]")." Bad email!\n";
            goto Start;
        }

        sleep(3);

        $cek = $vconomics->curl('https://generator.email/', null, $xyz, true);
        
        if (strpos($cek[1], 'Vconomics')) {
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

    goto Start;
} else {
    echo $vconomics->color('red', "[+]")." Error: ".$reg[1]."\n";
    goto Start;
}