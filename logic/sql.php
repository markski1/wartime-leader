<?php
    // only used to make submissions and requests for the hall of fame.
    function connect() {
        // auth.php contains db_host, db_user, db_password, db_base
        include "auth/auth.php";
    
        $con = new mysqli($db_host, $db_user, $db_password, $db_base);
        if ($con->connect_error) {
            echo ('could not connect. error: '.$con->connect_errno);
            die();
        }
    
        $con->set_charset('latin1');
    
        return $con;
    }
?>