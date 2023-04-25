<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <title>Attendee Management Page</title>
        <script src="jquery-3.6.4.min.js"></script> 
    </head>
    <body>
        
        
        <form id="attendeeManageform" name="attendeeManageform" action="#">
            <fieldset>
                <legend>Attendee Management</legend>
                <p>                
                    <?php
                    
                    $mysqli = new mysqli("127.0.0.1", "cs325_p3_user", "P3!user", "cs325_p3_sp23");
                    
                    echo "<p><label for=\"id\">Select Attendee:</label>";
                    
                    $result = $mysqli->query("SELECT * FROM attendee");
                    
                    echo "<select name=\"id\" id=\"id\">";
                    echo "<option value=\"0\" selected>Choose Name:</option>";                    
                    
                    foreach ($result as $record) {
                        $firstname = $record["firstname"];
                        $lastname = $record["lastname"];
                        $displayname = $record["displayname"];
                        $id = intval($record["id"]);
                        echo "<option value=\"{$id}\">{$firstname} {$lastname}</option>";
                    }
                    echo "</select>";
                    echo "</p>";
                    ?>
               <p>
                    <label for="firstname">First Name:</label>
                    <input type="text" name="firstname" id="firstname">
                </p>
                
                <p>
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" id="lastname">
                </p>
                
                <p>
                    <label for="displayname">Display Name:</label>
                    <input type="text" name="displayname" id="displayname">
                </p>                    
                </p>

                <p>
                    <input type="submit" value="Edit" onclick="return editAttendee();">
                </p>


            </fieldset>

        </form>
        
        <p>Click <a href="Index.html">here</a> to return to main menu</p>         
        
        <div id="output">
            
        </div>
            
        
        <script>
            $(function() {
                
                $("#firstname").change(displayNameChange);
                $("#lastname").change(displayNameChange);
                
            });

            function displayNameChange() {
                var fname = $("#firstname").val();
                var lname = $("#lastname").val();
                var dname = fname + " " + lname;
                $("#displayname").val(dname);
            }
            
        function editAttendee() {

            $.ajax({

                url: "attendee.php",
                method: "PUT",
                data: $("#attendeeManageform").serialize(),
                dataType: "json",

                success: function (json) {
                    $("#output").html("Attendee Edited!");
                }

            });
            return false;
        }            
        </script>             
        
    </body>
</html>
