<?php
class SearchTail {
    private $db;

    private $delayBeetweenSameRequest = 360;
    private $delayBetweenImgChange = 5;
    private $lastChange = -1;

    private function get_lastChange()
    {
        if( $this->get_lastChange < 0 )
        {
            return $this->lastChange;
        }
        if (!file_exists( sys_get_temp_dir()."/randompiclastchange.txt" )){
            $this->lastChange = 0;
        }else{
            $this->lastChange = file_get_contents( sys_get_temp_dir()."/randompiclastchange.txt" );
        }  
        return $this->lastChange;
    }
    private function set_lastChange( $time=-1 )
    {
        $timeToSet = ( $time == -1 )? time(): $time;
        file_put_contents(sys_get_temp_dir()."/randompiclastchange.txt", $timeToSet);
        $this->lascChange = $timeToset;
        return $this; //for chaining
    }

    public function __Construct( PDO $db )
    {
        $this->db = $db;
    }
    public function setDelayBeetweenSameRequest (int $delay )
    {
        $this->delayBeetweenSameRequest = $delay;
        return $this;

    }
    public function addQueryOnTail(string $words, int $timestamp = 0)
    {
        $q = "INSERT INTO queries ( keywords, `date`, processed ) VALUES (:keywords,current_timestamp,0); ";
        $rs = $this->db->prepare($q);
        $rs->execute(array(":keywords" => $words));

        return $this;
    }

    public function pickNewImgOnTail(int $minDelayDontChange = -1)
    {
        if( $minDelayDontChange > -1 )
        {
            if ( time() - $this-> get_lastChange() < $minDelayDontChange ){
                return $this->pickImgOnTail();
            }
        }
        //chosse the new key words
        $q = "SELECT keywords FROM queries WHERE processed=0 ORDER BY date DESC LIMIT 1";
        $rs = $this->db->prepare($q);
        $rs = execute();
        if($r = $rs->fetch()){
            $keywords = $r[0]; //TO DO VERIF

            //set the query as processed
            $qupdate = "UPDATE queries SET processed=1 WHERE keywords=:keywords";
            $rsupdate = $this->db->prepare($qupdate);
            $rsupdate->execute( array(":keywords"   => $keywords));
        }else{
            //no key words given;
            $keywords = "it's cool";
        }


        $searchQuery = new SearchQuery();
        return $this->pickImgOnTail();
    }
    


}