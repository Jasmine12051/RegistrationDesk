<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>New Attendee Registration Page</title>
        <script src="jquery-3.6.4.min.js"></script>
    </head>
    <body>
        <form id="registrationform" name="registrationform" action="#">
            <fieldset>

                <legend>New Attendee Registration</legend>

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
                
                <p>
                    <label for="sessionid">Session:</label>                   
                    <?php
                    
                
                    
                    $mysqli = new mysqli("127.0.0.1", "cs325_p3_user", "P3!user", "cs325_p3_sp23");
                    $result = $mysqli->query("SELECT * FROM session");
                    

                    echo "<select id=\"sessionid\">";
                    echo "<option value=\"0\" selected>Choose Session:</option>";
                    
                    foreach ($result as $record) {
                        $id = $record["id"];
                        $description = $record["description"];
                        echo "<option value=\"{$id}\">{$description}</option>";
                    }

                    echo "</select>";
                    
                    ?>                    
                    
                </p>

                <p>
                    <input type="submit" value="Register" onclick="return register();">
                </p>
            </fieldset>
        </form>
        
        <p>Click <a href="Index.html">here</a> to return to main menu</p>        
        
        <div id="output">
           
        </div>
        
        <script type="text/javascript">
            
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
            
            
        function register() {

            $.ajax({

                url: "attendee.php",
                method: "POST",
                data: $("#registrationform").serialize(),
                dataType: "json",

                success: function (json) {
                    
                    var registration = {};
                    registration["attendeeid"] = json["id"];
                    registration["sessionid"] = Number($("#sessionid").val());
                    
                    $.ajax({
                        url: "registration.php",
                        method: "POST",
                        data: $.param(registration),
                        dataType: "json",

                        success: function (json) {
                            $("#output").html("Registration Completed!");
                        }

                    });
                }

            });
            return false;
        }             
            
         
            
        </script>
    
    </body>
</html>
