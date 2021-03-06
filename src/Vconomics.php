<?php
/**
 * Vconomics Account Creator
 */
class Vconomics{
    
    function __construct(){
        # code...
    }

    public function generateResult($userName, $passwords, $nameFile){
        $file = fopen($nameFile, "a", 1);

        fwrite($file, $userName.'|'.$passwords.PHP_EOL);
        fclose($file);
    }

    public function gendata($domains = null, $jumlahAngka = 0, $usernameGmail = null, $indexDomain = 0) {
        if ($domains === null) {
            $domain = 'honey.cloudns.ph';
        } elseif ($domains === 'random') {
            $domain = $this->get_between_array(file_get_contents("https://generator.email/"), 'onclick="change_dropdown_list(this.innerHTML)" id="', '" style="');
            $domainIndex = array_rand($domain);
            $domain = $domain[$domainIndex];
        } elseif ($domains === 'gmail') {
            $arrDomain = $this->dotGmail($usernameGmail);

            $domain = $arrDomain[$indexDomain];
        } else {
            $domain = 'honey.cloudns.ph';
        }

        // Set Variable
        $angkaJumlah = intval($jumlahAngka);
        // Nama Depan
        $firstName = array(
            'Wildan',
            'Michael',
            'Bobby',
            'Rizky',
            'Danang',
            'Danny',
            'Bagus',
            'Bagas',
            'Annisa',
            'Intan',
            'Doddy',
            'Adi',
            'Sigit',
            'Rohman',
            'Rahman',
            'Gildan',
            'Wahyu',
            'Wiwin',
            'Betari',
            'Audry',
            'Yohanes',
            'Advent',
            'Ega',
            'Putri',
            'Musa',
            'Abi',
            'Rasya',
            'Wisnu'
        );

        // Nama Belakang
        $lastName = array(
            'Setiawan',
            'Septian',
            'Jahowa',
            'Richard',
            'William',
            'Liaw',
            'Febrisa',
            'Vini',
            'Riski',
            'Putra',
            'Sudrajat',
            'Saputra',
            'Fachrizal',
            'Manuru',
            'Doni',
            'Lestari',
            'Ghifari',
            'Firmansyah',
            'Ardiansyah',
            'Giovani',
            'Gionino',
            'Mako',
            'Fatma'
        );

        $indexFirstname = array_rand($firstName);
        $indexLastname = array_rand($lastName);
        $angkaFull = '';

        for ($i=0; $i < $angkaJumlah; $i++) { 
            $angkaFull .= rand(0,9);
        }

        if ($domains != 'gmail') {
            $data = array(
                'email' => strtolower($firstName[$indexFirstname].$lastName[$indexLastname].$angkaFull.'@'.$domain),
                'firstname' => $firstName[$indexFirstname],
                'lastname' => $lastName[$indexLastname]
            );
        } elseif ($domains === 'gmail') {
            $data = array(
                'dotMail' => count($arrDomain),
                'email' => $domain.'@gmail.com',
                'firstname' => $firstName[$indexFirstname],
                'lastname' => $lastName[$indexLastname]
            );
        }

        return $data;
    }

    public function dotGmail($username){
        if ((strlen($username) > 1) && (strlen($username) < 35)) {
            $ca = preg_split("//",$username);
            array_shift($ca);
            array_pop($ca);
            $head = array_shift($ca);
            $res = $this->dotGmail(join('',$ca));
            $result = array();
            foreach($res as $val){
                $result[] = $head . $val;
                $result[] = $head . '.' .$val;
            }
            return $result;
        }
        return array($username);
    }

    public function randomAgent(){
        $dataArray = array(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:74.0) Gecko/20100101 Firefox/74.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.2; G011A Build/N2G48H; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.158 Mobile Safari/537.36'
        );

        $indexArray = array_rand($dataArray);

        return $dataArray[$indexArray];
    }

    public function curl($url, $post, $headers, $follow = false, $method = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = array();
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        return array(
            'header' => $header,
            'body' => $body,
            'cookies' => $cookies
        );
    }

    public function get_between($string, $start, $end) {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }

    public function get_between_array($string, $start, $end) {
    	$aa = explode($start, $string);
    	for ($i=0; $i < count($aa) ; $i++) { 
    		$su = explode($end, $aa[$i]);
    		$uu[] = $su[0];
    	}
    	unset($uu[0]);
    	$uu = array_values($uu);
    	return $uu;
    }

    public function color($color, $text) {
        $arrayColor = array(
            'grey'      => '1;30',
            'red'       => '1;31',
            'green'     => '1;32',
            'yellow'    => '1;33',
            'blue'      => '1;34',
            'purple'    => '1;35',
            'nevy'      => '1;36',
            'white'     => '1;0',
        );  
        return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }
}