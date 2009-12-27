<?php
/**
 * Just like thrift generators, but instead of pulling from a .thrift file,
 * pulls from api.json
 * 
 * 
 * @author Lucas Hrabovsky <lucas@fanfeedr.com>
 * December 26th, 2009
 */
function generate_python($data){
    $out  = "#!/usr/bin/python\n";
    $out .= "# -*- coding: utf-8 -*-\n";
    $out .= "###################################################################\n";
    $out .= "# A simple python client for making calls to the FanFeedr API.\n";
    $out .= "# For more information, please see http://developer.fanfeedr.com/\n";
    $out .= "# @author Lucas Hrabovsky <lucas@fanfeedr.com>\n";
    $out .= "# December 26th, 2009\n";
    $out .= "###################################################################\n\n";
    $out .= "import os\n";
    $out .= "import sys\n";
    $out .= "import json\n";
    $out .= "import urllib2\n";
    $out .= "import urlib\n\n";
    $out .= "class FanFeedr(object):\n";
    $out .= "    def __init__(self, api_key, tier='basic', gateway_url='http://api.fanfeedr.com/'):\n";
    $out .= "        ''''\n";
    $out .= "        Basic constructor.\n";
    $out .= "        @param string api_key Your API key for the tier you are requesting to.\n";
    $out .= "        @param string tier (Optional) The API tier you have access to (basic, daily, gold, or platinum).\n";
    $out .= "        @param string gateway_url (Optional) Simple placeholder for supporting multiple gateways (ie for staging).\n";
    $out .= "        ''''\n";
    $out .= "        self.tier = tier\n";
    $out .= "        self.api_key = api_key\n";
    $out .= "        self.gateway_url = gateway_url\n\n";
    $out .= "    def __fetch(self, service, method, params=None):\n";
    $out .= "        ''''\n";
    $out .= "        Private method for fetching from HTTP and decoding JSON.\n";
    $out .= "        @param string service URL namespace for service (ie basic, gaming, user, etc).  Placeholder for now as all are basic.\n";
    $out .= "        @param string method The API method to call.\n";
    $out .= "        @param object params (Optional) Additional params to send along with the request.\n";
    $out .= "        ''''\n";
    $out .= "        param_string = ''\n";
    $out .= "        if params!=None:\n";
    $out .= "            param_string = urllib.urlencode(params)\n";
    $out .= "        url = self.gateway_url\n";
    $out .= "        if self.tier!= '':\n";
    $out .= "            url += self.tier+'/'\n";
    $out .= "        url += method+'?format=json&appid='+self.api_key\n";
    $out .= "        url += '&'+param_string\n";
    $out .= "        c=urllib2.urlopen(url)\n";
    $out .= "        contents = c.read()\n";
    $out .= "        return json.read(contents)\n\n";
    
    foreach($data->methods as $method){
        $out .= "    def ".$method->method.'(self';
        $c = count($method->parameters);
        $c_last = $c-1;
        $i = 0;
        $pString = "         params = {\n";
        if(count($method->parameters) > 0){
            $out .= ", ";
            foreach($method->parameters as $param){
                $out .= $param->name;
                $pString .= "            '".$param->name."' : ".$param->name;
                
                if(!$param->required){
                    $out .= "='".$param->default."'";
                }
                if($i!=$c_last){
                    $out .= ",";
                    $pString .= ",\n";
                }
                else{
                    $out .= "){\n";
                }
                $i++;
            }
            $pString .= "\n        }\n";
            $out .= "        '''\n";
            
            $out .= "        ".wordwrap($method->description, 80, "\n        ")."\n";
            $out .= "        Example: \n";
            $out .= "            http://api.fanfeedr.com/basic/".$method->method."?appid=<your-basic-api-key>&format=json";
            if($method->example_data){
                $out .= "&".$method->example_data;
            }
            $out .= "\n";
            $out .= "        '''\n";
            
            $out .= $pString;
            $out .= "        return self.__fetch('basic', '".$method->method."', params);\n\n";
        }
        else{
            $out .= "        return self.__fetch('basic', '".$method->method."');\n\n";
        }
    }
    return $out;
}
function generate_php($data){
    $out = "class FanFeedr{\n";
    $out .= "    public function __construct($"."apiKey, $"."tier='basic'){\n";
    $out .= "        $"."this->apiKey=$"."apiKey;\n";
    $out .= "        $"."this->tier=$"."tier;\n";
    $out .= "    }\n";
    $out .= "    private function fetch($"."method, $"."params=array()){\n";
    $out .= "        $"."url = 'http://api.fanfeedr.com/'.$"."this->tier.'/'.$"."method;\n";
    $out .= "        $"."params['format'] = 'json';\n";
    $out .= "        $"."params['appid'] = $"."this->apiKey;\n";
    $out .= "        $"."url .= '?'.http_build_query($"."params);\n";
    $out .= "        $"."ch = curl_init($"."url);\n";
    $out .= "        curl_setopt($"."ch, CURLOPT_RETURNTRANSFER, true);\n";
    $out .= "        $"."response_body = curl_exec($"."ch);\n";
    $out .= "        $"."status = curl_getinfo($"."ch, CURLINFO_HTTP_CODE);\n";
    $out .= "        if (intval($"."status) != 200) die(\"Error: $response_body\");\n";
    $out .= "        return json_decode($"."response_body);\n";
    $out .= "    }\n";
    
    foreach($data->methods as $method){
        $out .= "    public function ".$method->method.'(';
        $c = count($method->parameters);
        $c_last = $c-1;
        $i = 0;
        $pString = "        $"."params = array(\n";
        
        foreach($method->parameters as $param){
            $out .= "$".$param->name;
            $pString .= "            '".$param->name."'=>$".$param->name;
            
            if(!$param->required){
                $out .= "='".$param->default."'";
            }
            if($i!=$c_last){
                $out .= ",";
                $pString .= ",\n";
            }
            else{
                $out .= "){\n";
            }
            $i++;
        }
        $pString .= "\n        );\n";
        $out .= $pString;
        $out .= "        return $"."this->fetch('".$method->method."', $"."params);\n";
        $out .= "    }\n";
    }
    $out .= "}";
    
    return $out;
}

?>