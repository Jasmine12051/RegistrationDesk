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
    
    
    else if($server_args['REQUEST_METHOD'] == 'POST'){
            
        if (isset($post_args['firstname'], $post_args['lastname'], $post_args['displayname'])) {

            $firstname = $post_args['firstname'];
            $lastname = $post_args['lastname'];
            $displayname = $post_args['displayname']; 
            
            $stmt = $mysqli->prepare("INSERT INTO attendee (firstname, lastname, displayname) VALUES (?, ?, ?)");
            $stmt->bind_param("sss",$firstname, $lastname, $displayname);
            $stmt->execute();
            $json = array();
            $json["success"] = ($stmt->affected_rows == 1);
            
            if ($stmt->affected_rows == 1) {
                
                $json["id"] = ($stmt->insert_id);
                
            }
            
        }
        
        echo json_encode($json);
        
    }
    
    else if($server_args['REQUEST_METHOD'] == 'PUT'){
        parse_str(file_get_contents("php://input"), $put_args);
        
        if (isset($put_args['id'], $put_args['firstname'], $put_args['lastname'], $put_args['displayname'])) {
            
            $id = intval($put_args['id']);
            $firstname = $put_args['firstname'];
            $lastname = $put_args['lastname'];
            $displayname = $put_args['displayname'];
            
            $stmt = $mysqli->prepare("UPDATE attendee SET firstname=?, lastname=?, displayname=? WHERE id=?");
            $stmt->bind_param("sssi", $firstname, $lastname, $displayname, $id);
            $stmt->execute();
            $json = array();
            $json["success"] = ($stmt->affected_rows == 1);            
        }
        
        echo json_encode($json);        
        
    }    
    
    
?>