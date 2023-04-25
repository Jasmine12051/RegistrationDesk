<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <title>Session Management Page</title>
        <script src="jquery-3.6.4.min.js"></script> 
    </head>
    <body>
        
        <form id="sessionform" name="sessionform" action="#">
            <fieldset>
                <legend>Session Management</legend>
                <p>
                    <label for="sessionid">Select Session:</label>                    
                    <?php
                    
                    $mysqli = new mysqli("127.0.0.1", "cs325_p3_user", "P3!user", "cs325_p3_sp23");
                    $result = $mysqli->query("SELECT * FROM session");
                    

                    echo "<select name=\"sessionid\" id=\"sessionid\">";
                    echo "<option value=\"0\" selected>Choose Session:</option>";
                    
                    foreach ($result as $record) {
                        $id = $record["id"];
                        $description = $record["description"];
                        echo "<option value=\"{$id}\">{$description}</option>";
                    }

                    echo "</select>";
                    
                    
                    echo "<p><label for=\"attendeeid\">Select Attendee:</label>";
                    
                    $result2 = $mysqli->query("SELECT * FROM attendee");
                    
                    echo "<select name=\"attendeeid\" id=\"attendeeid\">";
                    echo "<option value=\"0\" selected>Choose Name:</option>";                    
                    
                    foreach ($result2 as $record) {
                        $firstname = $record["firstname"];
                        $lastname = $record["lastname"];
                        $displayname = $record["displayname"];
                        $id = intval($record["id"]);
                        echo "<option value=\"{$id}\">{$firstname} {$lastname}</option>";
                    }
                    echo "</select>";
                    echo "</p>";
                    ?>
                </p>

                <p>
                    <input type="submit" value="List Registrations" onclick="return listRegistration();">              
                    <input type="submit" value="Update Registrations" onclick="return updateRegister(); ">
                    <input type="submit" value="Cancel Registrations" onclick="return deleteRegister(); ">
                </p>


            </fieldset>

        </form>
        
        <p>Click <a href="Index.html">here</a> to return to main menu</p>         
        
        <div id="output">
            
        </div>
            
        
        <script>
            
            function listRegistration(){
                
            var sessionid = $("#sessionid").val();

            $.ajax({

                url: "session.php?id=" + sessionid,
                method: "GET",
                dataType: "json",

                success: function (json) {
                    var output_table = document.createElement("table");
                    output_table.setAttribute("style", "border: 1px solid black;");
                    var header_row = document.createElement("tr");
                    var json_keys = ["Id", "First Name", "Last Name", "Display Name", "Registration Code"];
                    
                    for(var index in json_keys){
                        
                        var header_col = document.createElement("th");
                        header_col.innerHTML = json_keys[index];
                        header_col.setAttribute("style", "border: 1px solid black;");
                        header_row.appendChild(header_col);
                        output_table.appendChild(header_row);
                        
                    }
                     
                    var data = json;
                    
                    for(let i = 0; i < data.length; ++i){
                        var data_row = document.createElement("tr");
                        var id = data[i].id;
                        var firstname = data[i].firstname;
                        var lastname = data[i].lastname;
                        var displayname = data[i].displayname;
                        var registrationcode = data[i].num;
                        
                        var keys = [id, firstname, lastname, displayname, registrationcode];

                        for(var index in keys){
                            var table_data = document.createElement("td");
                            table_data.setAttribute("style", "border: 1px solid black;");
                            table_data.innerHTML = keys[index];
                            data_row.appendChild(table_data);
                            output_table.appendChild(data_row);
                        }                         
                    }
                    
                    $("#output").html(output_table);

                }

            });

            return false;

        }


        function updateRegister() {

            $.ajax({

                url: "registration.php",
                method: "DELETE",
                data: $("#sessionform").serialize(),
                dataType: "json",

                success: function (json) {
                    
                    $.ajax({
                        url: "registration.php",
                        method: "POST",
                        data: $("#sessionform").serialize(),
                        dataType: "json",

                        success: function (json) {
                            $("#output").html("Registration Updated!");
                        }

                    });
                }

            });
            return false;
        }
        
        function deleteRegister() {

            $.ajax({

                url: "registration.php",
                method: "DELETE",
                data: $("#sessionform").serialize(),
                dataType: "json",

                success: function (json) {
                    $("#output").html("Registration Canceled!");
                }
            });
            return false;
        }            
            

        </script>             
        
    </body>
</html>
