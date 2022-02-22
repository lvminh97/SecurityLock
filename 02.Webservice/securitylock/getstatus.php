<?php
    if(isset($_POST['cmd']) && $_POST['cmd'] == "get_status"){
        require_once "function.php";

        $resp = array();
        $resp['cmd'] = "get_status";

        setTimeZone();

        $status = getParam("status");
        $latest = strtotime(getParam("latest_status"));
        $now = strtotime(date("Y-m-d H:i:s"));

        if($now - $latest >= 30){
            $resp['status'] = "disconnect";
        }
        else{
            if($status == "1"){
                $resp['status'] = "open";
            }
            else{
                $resp['status'] = "close";
            }
        }
        echo json_encode($resp);
    }
    else
        echo "NULL";
?>