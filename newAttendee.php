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
                    <input type="submit" value="Register" onclick="return register();">
                </p>

            </fieldset>
        </form>
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
                        var success = json['success'];
                        $("#output").html("New attendee added!" + success);
                    }

                });

                return false;

            }
        </script>
    
    </body>
</html>
