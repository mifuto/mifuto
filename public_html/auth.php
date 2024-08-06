<!DOCTYPE HTML>
<html lang="en">
    <head>
        <!--=============== basic  ===============-->
        <meta charset="UTF-8">
        <title>MIfuto-online photographer booking</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="robots" content="index, follow"/>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <!--=============== css  ===============-->
        <link type="text/css" rel="stylesheet" href="css/reset.css">
        <link type="text/css" rel="stylesheet" href="css/plugins.css">
        <link type="text/css" rel="stylesheet" href="css/style.css">
        <link type="text/css" rel="stylesheet" href="css/color.css">
        <!--=============== favicons ===============-->
        <link rel="shortcut icon" href="images/favicon.ico">
        
        <style>
            .error-input {
                border: 1px solid red !important; /* Red border style */
            }
            
          
            .error-message {
                color: red;
                font-size: 12px;
                margin-bottom: 20px;
                display: none; /* Initially hidden */
            }
            
            .success-message {
                color: green;
                font-size: 12px;
                margin-bottom: 20px;
                display: none; /* Initially hidden */
            }
            
            
            
            
        </style>
        
       
        
    </head>
<body>
    
    <div>
        
        
        
        <div class="container">
            <div class="logo-holder">
                <a href="index.php"><img src="images/logo.png" alt=""></a>
            </div>
           
        </div>
        
        
        <div class="home-intro">
            <div class="section-title-separator"><span><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span></div>
            <h2>Activate Your Account: Verify Your Email</h2>
            <span class="section-separator"></span>                                    
            <h3>Let's start exploring the world together with MIfuto</h3>
        </div><br>
               
        
    
    

<?php
// Check if key and value parameters are set in the URL
if (isset($_GET['key']) && isset($_GET['value'])) {
    // Get the values of key and value parameters
    $key = $_GET['key'];
    $value = $_GET['value'];
    
    date_default_timezone_set ("Asia/Calcutta");

    // Decode the base64 encoded values
    $decodedKey = base64_decode($key);
    $decodedValue = base64_decode($value);

    // Output the decoded key and value
    echo '<h3 >Email ID: ' . $decodedKey . '</h3><br>';
    
    // Current date and time (now)
    $currentDateTime = new DateTime();
    
    // Specific date and time to compare (2024-04-19 23:31:00)
    $specificDateTime = new DateTime($decodedValue);
    
    // Calculate the difference between the two dates
    $timeDifference = $currentDateTime->diff($specificDateTime);
    
   
    // Check if the time difference is greater than 10 minutes
    if ($timeDifference->format('%i') > 10 || $timeDifference->format('%d') >= 1 || $timeDifference->format('%h') >= 1   ) {
        echo '<p>Expired! The authentication link has expired.</p>';
    } else {
        echo '<div class="centered">';
        echo '<button type="button" onclick="authenticate()">Authenticate</button>';
        echo '</div>';
    }
} else {
    echo '<p>Error: Key and/or value parameters are missing in the URL.</p>';
}
?>

</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/plugins.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>

        

<script>
    function authenticate() {
        
        var email = '<?=$decodedKey?>';
        
         var postData = {
            function: 'User',
            method: "authenticate",
            'email':email,
          }
      
        $.ajax({
            url: '/admin/ajaxHandler.php',
            type: 'POST',
            data: postData,
            dataType: "json",
            success: function (data) {
                console.log(data);
                console.log(data.status);
                //called when successful
                if (data.status == 1) {
                  // Redirect to another page
                    window.location.href = 'index.php';

                }else{
                    location.reload();
                }
               
            },
            error: function (x,h,r) {
            //called when there is an error
                console.log(x);
                console.log(h);
                console.log(r);
                $('#btnLogIn').removeClass('d-none');
               
            }
        });
                
        
    }
</script>


</body>
</html>
