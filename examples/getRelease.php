<?php
require_once("../phpBrainz.class.php");
$mbid = "b922ff57-9289-4ea4-999e-cd4ddb986614";

$trackIncludes = array(
	"artist",
	"discs",
	"tracks"
	);

$phpBrainz = new phpBrainz();
print_r($phpBrainz->getRelease($mbid,$trackIncludes));