<?php

    $mysqli = new mysqli("127.0.0.1", "cs325_p3_user", "P3!user", "cs325_p3_sp23");

    $get_args = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $post_args = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $server_args = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING);

    header("Content-Type: application/json; charset=utf-8");
    
    if ($server_args['REQUEST_METHOD'] == 'GET') {
        
        $result = array();
        
        $result["message1"] = "TEST1";

        if (isset($get_args['id'])) {

            $id = $get_args['id'];
            $stmt = $mysqli->prepare("SELECT * FROM registration WHERE attendeeid=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();
            $json = array();            
            foreach ($result as $record) {
                $entry = array();
                $entry["attendeeid"] = $record["attendeeid"];
                $entry["sessionid"] = $record["sessionid"];               
                array_push($json, $entry);

            }

        }
        
        echo json_encode($json);
        
    }
    
    else if($server_args['REQUEST_METHOD'] == 'POST'){
        
        $json = array();
        
        $json["input"] = file_get_contents("php://input");
            
        if (isset($post_args['attendeeid']) && isset(($post_args['sessionid']))) {

            $attendeeid = intval($post_args['attendeeid']);
            $sessionid = intval($post_args['sessionid']);
            
            $stmt = $mysqli->prepare("INSERT INTO registration (attendeeid, sessionid) VALUES (?, ?)");
            $stmt->bind_param("ii", $attendeeid, $sessionid);
            $stmt->execute();
            
            $json["success"] = ($stmt->affected_rows == 1);
            
        }
        
        echo json_encode($json);
        
    }
    
    else if($server_args['REQUEST_METHOD'] == 'PUT'){
        parse_str(file_get_contents("php://input"), $put_args);
        
        if (isset($put_args['attendeeid']) && isset(($put_args['sessionid']))) {
            
            $attendeeid = intval($put_args['attendeeid']);
            $sessionid = intval($put_args['sessionid']);
            
            $stmt = $mysqli->prepare("UPDATE registration SET sessionid=? WHERE attendeeid=?");
            $stmt->bind_param("ii", $sessionid, $attendeeid);
            $stmt->execute();            
        }
        
    }
    
    else if($server_args['REQUEST_METHOD'] == 'DELETE'){
        
        parse_str(file_get_contents("php://input"), $delete_args);
        
        if (isset($delete_args['attendeeid'])) {
            
            $attendeeid = intval($delete_args['attendeeid']);
            
            $stmt = $mysqli->prepare("DELETE FROM registration WHERE attendeeid=?");
            $stmt->bind_param("i", $attendeeid);
            $stmt->execute();
            
            $json = array();
            $json["success"] = ($stmt->affected_rows == 1);
            
        }
        
        echo json_encode($json);
        
    }    
    
?>