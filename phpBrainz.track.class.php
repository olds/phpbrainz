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
 * Track object.
 * 
 * @package phpBrainz
 * @author Jeff Sherlock
 * @name phpBrainz_Track
 * @version 0.2
 *
 */
class phpBrainz_Track{
    private $id;
    private $artist;
    private $duration;
    private $puids;
    private $releases;
    private $title;
    private $score;

    function __construct(){
        $this->releases = array();
        $this->puids    = array();
    }

    /**
     * Sets the artist of the track object.
     *
     * @param phpBrainz_Artist $artist
     * @return void
     */
    public function setArtist(phpBrainz_Artist $artist){
        $this->artist = $artist;
    }
    
    /**
     * Returns the artist object for the track.
     *
     * @return phpBrainz_Artist
     */
    public function getArtist(){
        return $this->artist;
    }

    public function setDuration($duration){
        if(!is_numeric($duration)){
            throw new Exception("Duration must be numeric.");
        }
        $this->duration = $duration;
    }
    
    public function getDuration(){
        return $this->duration;
    }

    public function setTitle($title){
        $this->title = $title;
    }
    public function getTitle(){
        return $this->title;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    /**
     * Returns an array of 
     * phpBrainz_Release objects
     *
     * @return array
     */
    public function getReleases(){
        return $this->releases;
    }
    
    /**
     * Add a release on which this track appears.
     *
     * @param phpBrainz_Release $release
     */
    public function addRelease(phpBrainz_Release $release){
        $this->releases[] = $release;
    }

    /**
     * Add a PUID to this track.
     *
     * @param string $puid string containing a PUID
     */ 
    public function addPuid($puid){
        $this->puids[] = $puid;
    }
    
    /**
     * Returns the PUIDs associated with this track.
     * Please note that a PUID may be associated with more than one track.
     *
     * @return array an array of strings, each containing one PUID
     */
    public function getPuids(){
        return $this->puids;
    }
    
    
    public function getScore(){
    	return $this->score;
    }
    
    public function setScore($score){
    	$this->score = $score;
    }
    
}