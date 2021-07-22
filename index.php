<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo of Hiding Timekit</title>
</head>
<body>
<?php

$curl = curl_init();

$post=[
    "project_id"=>"0c0c704c-ca1c-442f-adeb-5689f529fc0d",
    "length"=>"1 hour",
    "from"=>"now",
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
        // var_dump($day);
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
?>
<div id="weekly_bookings">
<div class="day_bookings">
  <?php
  foreach ($monday_booking as $booking) {
    $start_time = mktime(substr($booking['start'],11,13), substr($booking['start'],14,16), substr($booking['start'],17,19), substr($booking['start'],5,7), substr($booking['start'],8,10), substr($booking['start'],0,4));
    $end_time = mktime(substr($booking['end'],11,13), substr($booking['end'],14,16), substr($booking['end'],17,19), substr($booking['end'],5,7), substr($booking['end'],8,10), substr($booking['end'],0,4));
    echo "<input type='button' onclick=\"location.href='booking.php?start=".$bookng['start']."'\"  value='".date("M d: h:ia",$start_time)." - ".date("h:ia",$end_time)."'/><br>";
    // echo "<a class='booking_link'>".date("M d: h:ia",$start_time)." - ".date("h:ia",$end_time)."</a><br>";
  }
  ?>
</div>
<div class="day_bookings">

</div>
<div class="day_bookings">

</div>
<div class="day_bookings">

</div>
<div class="day_bookings">

</div>
</div>
</body>


</html>