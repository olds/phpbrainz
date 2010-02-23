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
 * This is the abstract filter which
 * contains the constructor which all 
 * filters share because the only 
 * difference between each filter class 
 * is the valid argument types.
 * 
 * @author Jeff Sherlock
 * @copyright Jeff Sherlock 2007
 * @name phpBrainz_Abstract_Filter
 * @package phpBrainz
 * 
 */
abstract class phpBrainz_Abstract_Filter{
    protected $validArgTypes;
    protected $validArgs;
    function __construct($args){
        $this->validArgs = array();
        foreach($args as $key=>$value){
            if(in_array($key,$this->validArgTypes)){
                $this->validArgs[$key] = $value;
            }
        }
    }
    public function createParameters(){
        return $this->validArgs;
    }
}