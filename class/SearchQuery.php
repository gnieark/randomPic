<?php

//https://developers.google.com/custom-search/v1/using_rest


class SearchQuery
{
    private $google_apiKey;
    private $google_cx;

    const BASEURL = "https://www.googleapis.com/customsearch/v1?";

    public function __construct(string $google_apiKey,string $google_cx)
    {
        $this->google_apiKey = $google_apiKey;
        $this->google_cx = $google_cx;

    }
    private function makeQuery($keyWord)
    {
        $queryUrl = self::BASEURL
                    ."key=".$this->google_apiKey
                    ."&cx=".$this->google_cx
                    ."&q=" . urlencode ( $keyWord )
                    ."&searchType=image";

   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$queryUrl);
        $result=curl_exec($ch);
        curl_close($ch);
        $rs = json_decode($result,true);
        return $rs["items"];
        
    }
    //get a picture, taking a random one on the search results
    public function getRandomPic(string $keyWord)
    {
        $rs = $this->makeQuery($keyWord);
        shuffle($rs);
        return array(
            "content"   => file_get_contents( $rs[0]["link"] ),
            "fileName"  => basename($rs[0]["link"])
        );

    }
    


}