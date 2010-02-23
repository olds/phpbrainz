<?php
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

phpBrainz is a php class for querying the musicbrainz web service.
Copyright (c) 2007 Jeff Sherlock

*/
/**
 * Contains most of the querying and processing
 * methods for communicating with the musicbrainz
 * XML webservice.
 * 
 * @author Jeff Sherlock
 * @copyright Jeff Sherlock 2007
 * @package phpBrainz
 * @name phpBrainz
 * 
 */
error_reporting(E_ALL);
define('ARTIST_URL', "http://musicbrainz.org/ws/1/artist/");
define('RELEASE_URL', "http://musicbrainz.org/ws/1/release/");
define('TRACK_URL', "http://musicbrainz.org/ws/1/track/");
define('LABEL_URL', "http://musicbrainz.org/ws/1/label/");

$curdir = dirname(__FILE__)."/";
require($curdir."phpBrainz.abstractFilter.class.php");

require($curdir."phpBrainz.releaseFilter.class.php");
require($curdir."phpBrainz.trackFilter.class.php");
require($curdir."phpBrainz.artistFilter.class.php");

require($curdir."phpBrainz.artist.class.php");
require($curdir."phpBrainz.track.class.php");
require($curdir."phpBrainz.release.class.php");



class phpBrainz{	
	//Holds the valid "include" types for album lookups
	private $validAlbumInc = array(
    		"artist",
    		"counts",
    		"release-events",
    		"discs",
    		"tracks",
    		"artist-rels",
    		"label-rels",
    		"release-rels",
    		"track-rels",
    		"url-rels",
    		"track-level-rels",
    		"labels"
	);
	
	//Holds the valid "include" types for track lookups
	private $validTrackInc = array(
		"artist",
		"releases",
		"puids",
		"artist-rels",
		"label-rels",
		"release-rels",
		"track-rels",
		"url-rels"
        );
	
	/**
	 * Performs a query based on the parameters
	 * supplied in the ReleaseFilter object. Returns 
	 * an array of possible matches with scores, as 
	 * returned by the musicBrainz XML web service.
	 * Note that these types of queries only return some 
	 * information, and not all the information available
	 * about a particular release is available using
	 * this type of query. You will need to get the
	 * MusicBrainz id (mbid) and perform a lookup with
	 * getRelease to return complete information about 
	 * a release.
	 *
	 * @return array
	 * @param phpBrainz_ReleaseFilter
	 * 
	 */
	public function findRelease(phpBrainz_ReleaseFilter $releaseFilterObj){
	    if(sizeof($releaseFilterObj->createParameters())<1){
	        throw new Exception('The release filter object needs 
	        at least 1 argument to create a query.');
	    }
	    
		$queryStr = RELEASE_URL."?type=xml";
		$args = $releaseFilterObj->createParameters();
		foreach($args as $key=>$value){
		    $args[$key] = utf8_encode($value);
		}
		
		$queryStr .= "&".http_build_query($args);

		$xml = simplexml_load_file($queryStr);
		
                if($xml === false){
	   	    throw new Exception("Unable to load XML file. URL: ".$url);
	  	}
                
		$releases = array();
		
                foreach($xml->{'release-list'}->release as $release){
		    
		    $releaseObj = $this->parseReleaseXML($release);
		    $releases[] = $releaseObj;
		}
		return $releases;
	}
	
	/**
	 * Performs a query based on the parameters
	 * supplied in the TrackFilter object. Returns 
	 * an array of possible matches with scores, as 
	 * returned by the musicBrainz XML web service.
	 * Note that these types of queries only return some 
	 * information, and not all the information available
	 * about a particular track is available using
	 * this type of query. You will need to get the
	 * MusicBrainz id (mbid) and perform a lookup with
	 * getTrack to return complete information about 
	 * a release. This method returns an array of 
	 * Track objects that are possible matches.
	 *
	 * @param phpBrainz_TrackFilter $trackFilterObj
	 * @return array
	 */
	public function findTrack(phpBrainz_TrackFilter $trackFilterObj){
	    if(sizeof($trackFilterObj->createParameters())<1){
		throw new Exception('The release filter object needs 
	        at least 1 argument to create a query.');
	    }
	    
	    $queryStr = TRACK_URL."?type=xml";
	    $args = $trackFilterObj->createParameters();
	    
	    foreach($args as $key=>$value){
		$args[$key] = utf8_encode($value);
	    }
		
	    $queryStr .= "&".http_build_query($args);

	    $xml = simplexml_load_file($queryStr);
	    
            if($xml === false){
                throw new Exception("Unable to load XML file. URL: ".$url);
	    }
            
	    $tracks = array();
	
	    foreach($xml->{'track-list'}->track as $track){
		# Must specify the namespace
	        $attrNS = $track->attributes("http://musicbrainz.org/ns/ext-1.0#");
		
	        $trackRels = array();
	        foreach($track->{'release-list'}->release as $release){
	            $trackRels[] = $this->parseReleaseXML($release);
	            
	        }
	        $newTrack = $this->parseTrackXML($track);
	        $tracks[] = $newTrack; 
	    }
	    return $tracks;
	}	
	
	/**
	 * Retrieves a track given the musicbrainz id
	 * and an array of items to retrieve.
	 *
	 * @param string $mbid
	 * @param array $trackIncludes
	 * @return phpBrainz_Track
	 */
	public function getTrack($mbid, $trackIncludes=null){
	   if(!is_array($trackIncludes) && !is_null($trackIncludes)){
	       die(print("getTrack only takes 1 mbid and, if you wish, 1 array."));
	   }
	   $url = TRACK_URL.$mbid."?type=xml&inc=";
	   
           $url .= implode("+",$trackIncludes);
           
	   $xml = simplexml_load_file($url);
	   if($xml === false){
	   	throw new Exception("Unable to load XML file. URL: ".$url);
	   }
	   $track = $this->parseTrackXML($xml->track);
	   return $track;
	}

	/**
	 * Retrieves a release given the musicbrainz id
	 * and an array of items to retrieve.
	 *
	 * @param string $mbid
	 * @param array $trackIncludes
	 * @return phpBrainz_Release
	 */
	public function getRelease($mbid, $albumIncludes){
	   $url = RELEASE_URL.$mbid."?type=xml&inc=";
           
           $url .= implode("+",$albumIncludes);
           
	   $xml = simplexml_load_file($url);
	   if($xml === false){
	   		throw new Exception("Unable to load XML file. URL: ".$url);
	   }
	   $release = $this->parseReleaseXML($xml->release);
	   return $release;
	}
	/**
	 * Tests whether the input string 
	 * has the same form of a valid 
	 * musicbrainz id. Does not check to 
	 * see if the id exists in the 
	 * musicbrainz database.
	 *
	 * @param string $str
	 * @return boolean
	 */
	public static function isValidMBID($str){
	   $strArray = explode("-",$str);

	   if(
	       sizeof($strArray)   !=5 ||
	       strlen($strArray[0])!=8 ||
	       strlen($strArray[1])!=4 ||
	       strlen($strArray[2])!=4 ||
	       strlen($strArray[3])!=4 ||
	       strlen($strArray[4])!=12)
	       {
	           return false;
	       }
	    return true;
	}
	
	private function parseArtistXML($artist){
	    $newArtist = new phpBrainz_Artist();
	    $newArtist->setName((string)$artist->name);
	    $newArtist->setSortName((string)$artist->{'sort-name'});
	    $newArtist->setId((string)$artist['id']);
	    $newArtist->setBeginDate((string)$artist->{'life-span'}['begin']);
	    $newArtist->setEndDate((string)$artist->{'life-span'}['end']);
	    return $newArtist;
	}
	
	private function parseTrackXML($track){
	    $newTrack = new phpBrainz_Track();
	    $newArtist = $this->parseArtistXML($track->artist);
	    
	    $attrNS = $track->attributes("http://musicbrainz.org/ns/ext-1.0#");
	    $newTrack->setArtist($newArtist);
	    $newTrack->setId((string)$track['id']);
	    $newTrack->setDuration((intval((string)$track->duration)));
	    $newTrack->setTitle((string)$track->title);
	    $newTrack->setScore((string)$attrNS->score);
	    
	    if(isset($track->{'puid-list'})){
		foreach($track->{'puid-list'}->puid as $puid){
		    $newTrack->addPuid((string)$puid['id']);
		}
	    }
	    if(isset($track->{'release-list'}->release)){
                $newRelease = $this->parseReleaseXML($track->{'release-list'}->release);
                $newTrack->addRelease($newRelease);
	    }
	    return $newTrack;	        
	}		
	
	private function parseReleaseXML($release){
	   
            $releaseObj = new phpBrainz_Release();
            $artistObj = $this->parseArtistXML($release->artist);
        
            $attrNS = $release->attributes("http://musicbrainz.org/ns/ext-1.0#");
            $releaseObj->setScore((string)$attrNS->score);
            $releaseObj->setArtist($artistObj);
            $releaseObj->setId((string)$release['id']);
            $releaseObj->setTitle((string)$release->title);
            $releaseObj->setTracksCount((string)$release->{'track-list'}['count']);
            $releaseObj->setTracksOffset((string)$release->{'track-list'}['offset']);
            $releaseObj->setDiscCount((string)$release->{'disc-list'}['count']);
	   
            if(isset($release->{'track-list'})){
                foreach($release->{'track-list'}->track as $trackXML){
                    $releaseObj->addTrack($this->parseTrackXML($trackXML));
                }
            }
            if(isset($release->asin)){
                $releaseObj->setASIN((string)$release->asin);
            }
 
            return $releaseObj;
	}    
	
}
?>