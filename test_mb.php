<?php
require("phpBrainz.class.php");
$mb = new phpBrainz();
$mb_test = new stdClass();
$mb_rf = new phpBrainz_TrackFilter(
    array(
        "title"=>"Buddy Holly",
        "artist"=>"Weezer"
        ));

$time1 = microtime(true);

print_r($mb->findTrack($mb_rf));
//print_r($mb->getASINFromTrackMBID("7a408099-5c69-4f53-8050-6b15837398d1"));

//print_r($mb->getTrack());
$time2 = microtime(true);
print("\n".($time2-$time1)."\n");