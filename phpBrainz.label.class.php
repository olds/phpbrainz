<?php
class phpBrainz_Label{
    private $type;
    private $name;
    private $sortName;
    private $disambiguation;
    private $beginDate;
    private $endDate;
    private $country;
    private $code;
    private $aliases;
    
    public function __construct(){
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function setType($type){
        #TODO Validate the type
        $this->type = $type;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getSortName(){
        return $this->sortName;
    }
    
    public function setSortName($sortName){
        $this->sortName = $sortName;
    }
    
    public function getDisambiguation(){
        return $this->disambiguation;
    }
    
    public function setDisambiguation($disambiguation){
        $this->disambiguation = $disambiguation;
    }
    
    public function getUniqueName(){
        return $this->name . " (".$this->disambiguation.")";
    }
    
    public function getBeginDate(){
        return $this->beginDate;
    }
    
    public function setBeginDate($date){
        $this->beginDate = $date;
    }
    
    public function getEndDate(){
        return $this->endDate;
    }
    
    public function setEndDate($date){
        $this->endDate = $date;
    }
    
    public function getCountry(){
        return $this->country;
    }
    
    public function setCountry($country){
        #TODO validate country code
        $this->country = $country;
    }
    
    public function getCode(){
        return $this->code;
    }
    
    public function setCode($code){
        $this->code = $code;
    }
    
}
?>