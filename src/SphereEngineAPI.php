<?php

/**
 * Sphere Engine API
 *
 * LICENSE
 *
 *
 * Tu treść licencji
 *
 *
 * @copyright  Copyright (c) 2015 Sphere Research Labs (http://sphere-research.com)
 * @license    link do licencji
 * @version    0.1
 */


/**
 * Opis klasy
 *
 */
class SphereEngineAPI 
{
    // type of API (SC for Sphere Compilers or SP for Sphere Problems)
    private $type;
    // version of API
    private $version;
    // access token
    private $access_token;
    // url of web service
    private $baseurl;

    public function __construct($type='SC', $version='1', $access_token)
    {
        $this->type = $type;
        $this->version = $version;
        $this->access_token = $access_token;

        if ($type == 'SC')
            $this->baseurl = 'http://api.compilers.sphere-engine.com/api/' . $version . '/';
        else if ($type == 'SP')
            $this->baseurl = 'http://api.problems.sphere-engine.com/api/' . $version . '/';
        else
            $this->baseurl = '';
    }

    public function test()
    {
        $url = $this->baseurl . 'test?access_token=' . $this->access_token;        
        return $this->get_content_using_files($url);
    }

    private function get_content_using_curl($url)
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch); 

        return $response;
    }

    private function get_content_using_files($url)
    {
        return file_get_contents($url);
    }

}