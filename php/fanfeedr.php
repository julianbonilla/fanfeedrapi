<?php
/**
 * Basic class for interacting with the FanFeedr API.
 * @author Lucas Hrabovsky <lucas@fanfeedr.com>
 * 
 * For more information, please see http://developer.fanfeedr.com
 * 
 * Example:
 * $fanfeedr = new FanFeedr('basic', '<your-basic-api-key>');
 * $search_results = $fanfeedr->search('steelers');
 * print "<h1>Steelers News</h1>";
 * foreach($search_results->docs as $result){
 *     print "<a href=\"".$result['article.link']."\">".$result['entity.name']."</a><br />";
 * }
 */
class FanFeedr{
    private $tier;
    private $apiKey;
    
    public function __construct($apiKey, $tier='basic'){
        $this->apiKey=$apiKey;
        $this->tier=$tier;
    }
    private function fetch($method, $params){
        $params['format'] = 'json';
        $params['appid'] = $this->apiKey;
        $url = "http://api.fanfeedr.com/".$this->tier."/".$method."?".http_build_query($params, '', '&');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (intval($status) != 200){
            die("Error: $response");
        }
        $r = json_decode($response);
        if($r->docs){
            $clean = array();
            foreach($r->docs as $docs){
                $clean[] = get_object_vars($docs);
            }
            $r->docs = $clean;
        }
        return $r;
    }
    public function suggest($q){
        $params = array(
            'q'=>$q
        );
        return $this->fetch('suggest', $params);
    }
    public function all_scores($start='0',$rows='20'){
        $params = array(
            'start'=>$start,
            'rows'=>$rows
        );
        return $this->fetch('all_scores', $params);
    }
    public function scores($resource,$start='0',$rows='20'){
        $params = array(
            'resource'=>$resource,
            'start'=>$start,
            'rows'=>$rows
        );
        return $this->fetch('scores', $params);
    }
    public function geo_feed($start='0',$rows='20',$lat='',$long=''){
        $params = array(
            'start'=>$start,
            'rows'=>$rows,
            'lat'=>$lat,
            'long'=>$long
        );
        return $this->fetch('geo_feed', $params);
    }
    public function search($q,$start='0',$rows='20',$filter='search',$date='all-time',$content_type='all'){
        $params = array(
            'q'=>$q,
            'start'=>$start,
            'rows'=>$rows,
            'filter'=>$filter,
            'date'=>$date,
            'content_type'=>$content_type
        );
        return $this->fetch('search', $params);
    }
    public function article($resource){
        $params = array(
            'resource'=>$resource
        );
        return $this->fetch('article', $params);
    }
    public function recap($resource){
        $params = array(
            'resource'=>$resource
        );
        return $this->fetch('recap', $params);
    }
    public function boxscore($resource){
        $params = array(
            'resource'=>$resource
        );
        return $this->fetch('boxscore', $params);
    }
    public function schedule($resource){
        $params = array(
            'resource'=>$resource
        );
        return $this->fetch('schedule', $params);
    }
    public function resource_feed($resource){
        $params = array(
            'resource'=>$resource
        );
        return $this->fetch('resource_feed', $params);
    }
}
?>