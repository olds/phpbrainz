<?php
require_once("../phpBrainz.class.php");
$mbid = "d615590b-1546-441d-9703-b3cf88487cbd";

$trackIncludes = array(
	"artist",
	"releases",
	"puids"
	);

$phpBrainz = new phpBrainz();
print_r($phpBrainz->getTrack($mbid,$trackIncludes));
