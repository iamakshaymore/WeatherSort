<?php

date_default_timezone_set ( "est" );
$Date = date('Y-m-d');
$start = date('Y-m-d', strtotime($Date. ' - '.$_GET['days'].' days'));

$xml = new SimpleXMLElement('<xml/>');

for ($i = 1; $i <= $_GET['days']; $i++) {
    $track = $xml->addChild('item');
    $track->addChild('date',$start);
    $track->addChild('temperature', rand ( -20 , 40 ));
    $start = date('Y-m-d', strtotime($start. ' +1 days'));
}

Header('Content-type: text/xml');
print($xml->asXML());
?>