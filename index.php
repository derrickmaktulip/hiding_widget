<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo of Hiding Timekit</title>
    <link href="index.css" rel="stylesheet">
</head>
<body>
<h1 class="header">Computer Diagnostic Services</h1>

<?php

$curl = curl_init();

$post=[
    "project_id"=>"0c0c704c-ca1c-442f-adeb-5689f529fc0d",
    "length"=>"30 minutes",
    "from"=>"7 hours",
    "to"=>"1 week",
    "output_timezone"=> "America/Toronto",
    "constraints"=> [
        ["block_weekends"=> []],
        ["allow_hours"=> ["start"=> 9, "end"=> 17]],
        ["block_hours"=> ["start"=> 12, "end"=> 13]] 
      ]
];
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.timekit.io/v2/availability",
  CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($post),
  CURLOPT_USERPWD => ":live_api_key_NuvN1bCG3Vv7la7vpPDYaqJDCOrg135g",
  CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
//   CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_HTTPHEADER => [
    // "Accept: application/json",
    // "Authorization: Basic :live_api_key_NuvN1bCG3Vv7la7vpPDYaqJDCOrg135g",
    "Content-Type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    $first_day;
    $monday_booking=array();
    $tuesday_booking=array();
    $wednesday_booking=array();
    $thursday_booking=array();
    $friday_booking=array();
    $output_json = json_decode($response, true);
    foreach($output_json['data'] as $booking_event)
    {
        $event_date=substr($booking_event["start"],0,10);
        $timestamp=strtotime($event_date);
        $day = date('D', $timestamp);
        if (isset($first_day)!=true) {
          $first_day = $day;
        }
        $dates = array();
        if ($day==="Mon") {
            $monday_booking[] = $booking_event;
        } elseif ($day==="Tue") {
            $tuesday_booking[] = $booking_event;
        } elseif ($day==="Wed") {
            $wednesday_booking[] = $booking_event;
        } elseif ($day==="Thu") {
            $thursday_booking[] = $booking_event;
        } elseif ($day==="Fri") {
            $friday_booking[] = $booking_event;
        }
        
    }
}

echo "<div id='weekly_bookings'>";
$weekly_bookings = array();
$weekly_bookings[] = $monday_booking;
$weekly_bookings[] = $tuesday_booking;
$weekly_bookings[] = $wednesday_booking;
$weekly_bookings[] = $thursday_booking;
$weekly_bookings[] = $friday_booking;

if ($first_day === "Mon") {
  $index = 0;
} else if ($first_day === "Tue") {
  $index = 1;
} else if ($first_day === "Wed") {
  $index = 2;
} else if ($first_day === "Thu") {
  $index = 3;
} else {
  $index = 4;
}


for ($i=0;$i<5;$i++){
  echo "<div class='day_bookings'>";
  if ($index==0){
    echo "<h2>Monday Bookings</h2>";
  } elseif ($index==1){
    echo "<h2>Tuesday Bookings</h2>";
  } elseif ($index==2){
    echo "<h2>Wednesday Bookings</h2>";
  } elseif ($index==3){
    echo "<h2>Thursday Bookings</h2>";
  } elseif ($index==4){
    echo "<h2>Friday Bookings</h2>";
  }

  foreach ($weekly_bookings[$index] as $booking) {
    $start_time = mktime((int)substr($booking['start'],11,13), (int)substr($booking['start'],14,16), (int)substr($booking['start'],17,19), (int)substr($booking['start'],5,7), (int)substr($booking['start'],8,10), (int)substr($booking['start'],0,4));
    $end_time = mktime((int)substr($booking['end'],11,13), (int)substr($booking['end'],14,16), (int)substr($booking['end'],17,19), (int)substr($booking['end'],5,7), (int)substr($booking['end'],8,10), (int)substr($booking['end'],0,4));  
    echo "<form action='booking.php' method='post'>".
    "<input type='hidden' name='start' value='".$booking["start"]."'/>
    <input type='hidden' name='end' value='".$booking["end"]."'/>".
    "<button>".date("M d: h:ia",$start_time)." - ".date("h:ia",$end_time)."</button></form>";
  }
  echo "</div>";
  $index++;
  if($index == 5) {
    $index = 0;
  }
}
echo "</div>";

  // foreach ($tuesday_booking as $booking) {
  //   $start_time = mktime(substr($booking['start'],11,13), substr($booking['start'],14,16), substr($booking['start'],17,19), substr($booking['start'],5,7), substr($booking['start'],8,10), substr($booking['start'],0,4));
  //   $end_time = mktime(substr($booking['end'],11,13), substr($booking['end'],14,16), substr($booking['end'],17,19), substr($booking['end'],5,7), substr($booking['end'],8,10), substr($booking['end'],0,4));
  //   echo "<form action='booking.php' method='post'>".
  //   "<input type='hidden' name='start' value='".$booking["start"]."'/>
  //   <input type='hidden' name='end' value='".$booking["end"]."'/>".
  //   "<button>".date("M d: h:ia",$start_time)." - ".date("h:ia",$end_time)."</button></form>";
  // }
  
  // foreach ($wednesday_booking as $booking) {
  //   $start_time = mktime(substr($booking['start'],11,13), substr($booking['start'],14,16), substr($booking['start'],17,19), substr($booking['start'],5,7), substr($booking['start'],8,10), substr($booking['start'],0,4));
  //   $end_time = mktime(substr($booking['end'],11,13), substr($booking['end'],14,16), substr($booking['end'],17,19), substr($booking['end'],5,7), substr($booking['end'],8,10), substr($booking['end'],0,4));
  //   echo "<form action='booking.php' method='post'>".
  //   "<input type='hidden' name='start' value='".$booking["start"]."'/>
  //   <input type='hidden' name='end' value='".$booking["end"]."'/>".
  //   "<button>".date("M d: h:ia",$start_time)." - ".date("h:ia",$end_time)."</button></form>";
  // }
 
  // foreach ($thursday_booking as $booking) {
  //   $start_time = mktime(substr($booking['start'],11,13), substr($booking['start'],14,16), substr($booking['start'],17,19), substr($booking['start'],5,7), substr($booking['start'],8,10), substr($booking['start'],0,4));
  //   $end_time = mktime(substr($booking['end'],11,13), substr($booking['end'],14,16), substr($booking['end'],17,19), substr($booking['end'],5,7), substr($booking['end'],8,10), substr($booking['end'],0,4));
  //   echo "
  //   <form action='booking.php' method='post'>".
  //   "<input type='hidden' name='start' value='".$booking["start"]."'/>
  //   <input type='hidden' name='end' value='".$booking["end"]."'/>".
  //   "<button>".date("M d: h:ia",$start_time)." - ".date("h:ia",$end_time)."</button></form>";
  // }

  // foreach ($friday_booking as $booking) {
  //   $start_time = mktime(substr($booking['start'],11,13), substr($booking['start'],14,16), substr($booking['start'],17,19), substr($booking['start'],5,7), substr($booking['start'],8,10), substr($booking['start'],0,4));
  //   $end_time = mktime(substr($booking['end'],11,13), substr($booking['end'],14,16), substr($booking['end'],17,19), substr($booking['end'],5,7), substr($booking['end'],8,10), substr($booking['end'],0,4));
  //   echo "<form action='booking.php' method='post'>".
  //   "<input type='hidden' name='start' value='".$booking["start"]."'/>
  //   <input type='hidden' name='end' value='".$booking["end"]."'/>".
  //   "<button>".date("M d: h:ia",$start_time)." - ".date("h:ia",$end_time)."</button></form>";
  // }
  ?>
</div>
</div>
</body>


</html>