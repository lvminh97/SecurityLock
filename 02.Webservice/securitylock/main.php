<?php
    require_once "config.php";
    require_once "function.php";
    require_once "aes.php";

    $cipher = "";
    if(isset($_POST['data'])){
        $cipher = base64_decode($_POST['data']);
    }

    setTimeZone();

    $aes = new AES;
    $aes->set_key($key, 128);

    $plain = $aes->do_decrypt(str2ByteArray($cipher), $iv);
    // echo byteArray2Str($plain)."<br>";
    $decoded_data = json_decode(byteArray2Str($plain), true);
    $resp = array();
    if(isset($decoded_data['cmd']) && $decoded_data['cmd'] == "lock_code"){
        $resp['cmd'] = "lock_code";
        if(isset($decoded_data['code']) && $decoded_data['code'] == getParam('lock_code')){
            $resp['result'] = "true";
            $resp['otp'] = genOTP();
            setParam("otp_code", $resp['otp']);
            setParam("latest_otp", date("Y-m-d H:i:s"));
        }
        else{
            $resp['result'] = "false";
        }
    }
    else if(isset($decoded_data['cmd']) && $decoded_data['cmd'] == "open"){
        setParam("status", "1");
        setParam("latest_status", date("Y-m-d H:i:s"));
    }
    else if(isset($decoded_data['cmd']) && $decoded_data['cmd'] == "close"){
        setParam("status", "0");
        setParam("latest_status", date("Y-m-d H:i:s"));
    }
    $plain = str2ByteArray(json_encode($resp));
    $blocks = intdiv(count($plain) , 16);
    if(count($plain) % 16 != 0)
        $blocks++;
    // if()
    $cipher = $aes->do_encrypt($plain, $iv);
    echo "RespData:".base64_encode(byteArray2Str($cipher));
?>