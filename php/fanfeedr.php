class FanFeedr{
    public function __construct($apiKey, $tier='basic'){
        $this->apiKey=$apiKey;
        $this->tier=$tier;
    }
    private function fetch($method, $params=array()){
        $url = 'http://api.fanfeedr.com/'.$this->tier.'/'.$method;
        $params['format'] = 'json';
        $params['appid'] = $this->apiKey;
        $url .= '?'.http_build_query($params);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response_body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (intval($status) != 200) die("Error: ");
        return json_decode($response_body);
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