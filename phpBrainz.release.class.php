<?php
/**
 * Represents a release object.
 * 
 * @package phpBrainz
 * @author Jeff Sherlock
 * @copyright Jeff Sherlock 2007
 * @name phpBrainz_Release
 *
 */

class phpBrainz_Release{
    private $artist;
    private $title;
    private $tracksCount;
    private $id;
    private $discCount;
    private $asin;
    private $tracks;
    private $score;
    private $tracksOffset;
    
    function __construct(){
    	$this->tracks = array();
    }
    
    /**
     * Sets the artist for this release
     * object.
     *
     * @param phpBrainz_Artist $artist
     * @return none
     */
    public function setArtist(phpBrainz_Artist $artist){
        $this->artist = $artist;
    }
    /**
     * Returns the artist for this release
     *
     * @param none
     * @return phpBrainz_Artist
     */
    public function getArtist(){
        return $this->artist;
    }
    
    /**
     * Sets the musicbrainz id for this
     * release.
     *
     * @param string $id
     * @return none
     */
    public function setId($id){
        $this->id = $id;        
    }
    
    /**
     * Returns the musicbrainz id for this
     * release.
     * 
     * @param none
     * @return string
     */
    public function getId(){
        return $this->id;
    }
    
    public function setTracksCount($trackCount){
        $trackCount = (int)$trackCount;
        if(!is_numeric($trackCount) && strlen($trackCount)>0){
            die(print("setTrackCount requires an integer."));
        }
        $this->tracksCount = $trackCount;
    }
    public function getTracksCount($trackCount){
    	return $this->tracksCount;
    }
    
    public function addTrack($track){
    	if(!($track instanceof phpBrainz_Track)){
    		die(print("addTrack only takes a phpBrainz_Track object"));
    	}
    	$this->tracks[] = $track;
    }
    
    public function getTracks($track){
    	return $this->tracks;
    }
    
    public function getTitle(){
        return $this->title;
    }
    public function setTitle($title){
        $this->title = (string)$title;
    }
    
    public function getScore(){
        return $this->score;
    }
    public function setScore($score){
        $this->score = intval($score);
    }
    
    public function getTracksOffset(){
        return $this->tracksOffset;
    }
    public function setTracksOffset($offset){
        $this->tracksOffset = intval($offset);
    }
    
    /**
     * Returns the number of discs in this release, 
     * if this information is available.
     *
     * @return int
     */
    public function getDiscCount(){
        return $this->discCount;
    }
    /**
     * Sets the number of discs in this release.
     *
     * @param int
     */
    public function setDiscCount($count){
        $this->discCount = $count;
    }
    
    /**
     * Sets the asin value.
     * @param string $asin
     */
    public function setASIN($asin){
        $this->asin = $asin;
    }
    
    /**
     * Retrieves the asin value.
     * @param none
     * @return string
     */
    public function getASIN(){
        return $this->asin;
    }
    
    public function equals(phpBrainz_Release $compareObj){
        if((
                $this->id == $compareObj->getId() &&
                phpBrainz::isValidMBID($this->id)
            ) ||
            (
                $this->artist->equals($compareObj->getArtist()) && 
                $this->title == $compareObj->getTitle()
            )
            ){
                return true;
            }
            
            return false;
    }
    
    public function isSingleArtistRelease(){
        foreach($this->tracks as $track){
            if($track->id != $this->artist->id){
                return false;
            }
        }
        return true;
    }
}

?>