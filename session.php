<?php

    $mysqli = new mysqli("127.0.0.1", "cs325_p3_user", "P3!user", "cs325_p3_sp23");

    $get_args = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $server_args = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING);

   header("Content-Type: application/json; charset=utf-8");

    if ($server_args['REQUEST_METHOD'] == 'GET') {
        
        $result = array();
        
        if (isset($get_args['id'])) {
            
            $id = $get_args['id'];
            $stmt = $mysqli->prepare("SELECT *, CONCAT(\"R\", LPAD(attendeeid, 6, 0)) AS num FROM registration JOIN attendee ON registration.attendeeid = attendee.id WHERE sessionid=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $json = array();            
            foreach ($result as $record) {
                $entry = array();
                $entry["attendeeid"] = $record["attendeeid"];
                $entry["sessionid"] = $record["sessionid"];
                $entry["id"] = $record["id"];
                $entry["firstname"] = $record["firstname"];
                $entry["lastname"] = $record["lastname"];
                $entry["displayname"] = $record["displayname"];
                $entry["num"] = $record["num"];                
                
                
                array_push($json, $entry);
                
            }         
            
        }
        
        else {
        
            $result = $mysqli->query("SELECT * FROM session");
            $json = array();
            foreach ($result as $record) {
                $entry = array();
                $entry["id"] = $record["id"];
                $entry["description"] = $record["description"];
                array_push($json, $entry);
            }
            
        }
    echo json_encode($json);
    

    }  

?>
