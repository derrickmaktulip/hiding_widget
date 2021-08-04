<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
    <link href="booking.css" rel="stylesheet">
</head>
<body>
<?php 
if (!isset($_POST["full_name"])){


    $start_time = mktime((int)substr($_POST['start'],11,13), (int)substr($_POST['start'],14,16), (int)substr($_POST['start'],17,19), (int)substr($_POST['start'],5,7), (int)substr($_POST['start'],8,10), (int)substr($_POST['start'],0,4));
    $end_time = mktime((int)substr($_POST['end'],11,13), (int)substr($_POST['end'],14,16), (int)substr($_POST['end'],17,19), (int)substr($_POST['end'],5,7), (int)substr($_POST['end'],8,10), (int)substr($_POST['end'],0,4));
    echo "You are booking ".date("F d: h:ia",$start_time)." - ".date("h:ia",$end_time);
    echo "<form action='/booking.php' method='post'>";
    echo "<input type='hidden' name='start' value='".$_POST["start"]."'>";
    echo "<input type='hidden' name='end' value='".$_POST["end"]."'>";
    echo "<p>
    <label for='name'>Enter your full name:</label>
    <input id ='name' type='text' placeholder='Full Name' name='full_name' required>
    </p>
    <p>
    <label for='email'>Enter your email:</label>
    <input id='email' type='email' require placeholder='Email' name='user_email' required>
    </p>
    <button>Submit</button>
    </form>";}
if (isset($_POST["full_name"])){
    $ch_post = curl_init();
    curl_setopt($ch_post, CURLOPT_URL, 'https://api.timekit.io/v2/bookings');
    curl_setopt($ch_post, CURLOPT_POST, 1);
    curl_setopt($ch_post, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_post, CURLOPT_USERPWD, ":live_api_key_NuvN1bCG3Vv7la7vpPDYaqJDCOrg135g");
    curl_setopt($ch_post, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_post, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    )); 
    $post_data = [
        'customer' => [
            'email' => $_POST['user_email'],
            'name' => $_POST['full_name'],
            'timezone' => "America/Toronto"
        ],
        'end' => $_POST['end'],
        'graph' => 'instant',
        'project_id' => '0c0c704c-ca1c-442f-adeb-5689f529fc0d',
        'resource_id' => 'a16ecbd1-043e-4ec4-9000-4b1dee660164',
        'start' => $_POST['start']
    ];
    curl_setopt($ch_post, CURLOPT_POSTFIELDS, json_encode($post_data));
    $response = curl_exec($ch_post);
    $err = curl_error($ch_post);
    $response_json = json_encode($response);
    if (substr($response_json,4,6) == "errors") {
        echo "<h2>There seems to be an error, we got the following message:</h2>";
        echo "<h2>".$response_json."</h2>";
        echo "<h2>Please call our support line 123-456-7890</h2>";
    } else {
        echo "<h2>Your booking has been confirmed</h2>";
        echo "<meta http-equiv = 'refresh' content = '3; url = index.php' />";
    }
}


?>
</body>
</html>