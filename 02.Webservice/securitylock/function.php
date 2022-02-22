<?php 

require_once "DB.php";

function setTimeZone(){
    date_default_timezone_set('asia/ho_chi_minh');
}

function str2ByteArray($str){
    $arr = array();
    for($i = 0; $i < strlen($str); $i++){
        $arr[$i] = ord($str[$i]);
    }
    return $arr;
}
    
function byteArray2Str($arr){
    $str = "";
    for($i = 0; $i < count($arr); $i++){
        if($arr[$i] == 0)
            break;
        $str .= chr($arr[$i]);
    }
    return $str;
}

function getParam($param){
    $db = new DB;
    $data = $db->select("params", "*", "name='$param'");
    if(count($data) == 1){
        return $data[0]['value'];
    }
    else return null;
}

function setParam($param, $value){
	$db = new DB;
	$db->update("params", array('value' => $value), "name='$param'");
}

function genOTP(){
    $buf = "0123456789";
    $str = "";
    do{
        for($i = 0; $i < 6; $i++){
            $str .= $buf[rand(0, 9)];
        }
    }
    while($str == "000000");
    return $str;
}
?>