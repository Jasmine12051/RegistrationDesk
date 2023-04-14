<?php

    $mysqli = new mysqli("127.0.0.1", "cs325_p3_user", "P3!user", "cs325_p3_sp23");

    $get_args = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $post_args = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $server_args = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING);

    header("Content-Type: application/json; charset=utf-8");
    
    if ($server_args['REQUEST_METHOD'] == 'GET') {
        
        $result = array();

        if (isset($get_args['id'])) {

            $id = $get_args['id'];
            $stmt = $mysqli->prepare("SELECT * FROM attendee WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();
            
            $json = array();            
            foreach ($result as $record) {
                $entry = array();
                $entry["id"] = $record["id"];
                $entry["firstname"] = $record["firstname"];
                $entry["lastname"] = $record["lastname"];
                $entry["displayname"] = $record["displayname"];                
                array_push($json, $entry);

            }

        }
        
        echo json_encode($json);
        
    }
    
    
    else if($server_args['REQUEST_METHOD'] == 'POST') {

        $firstname = $post_args['firstname'];
        $lastname = $post_args['lastname'];
        $displayname = $post_args['displayname']; 

        $stmt = $mysqli->prepare("INSERT INTO attendee (firstname, lastname, displayname) VALUES (?, ?, ?)");
        
        $stmt->bind_param("sss", $firstname, $lastname, $displayname);
        $stmt->execute();
        $rows = $stmt->affected_rows;
        
        $json = array();
        $json["success"] = ($rows == 1);
        
        echo json_encode($json);
        
    }    
    
?>