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
    // default language
    private $default_language_id;
    // url of web service
    private $baseurl;

    public function __construct($params=array())
    {
        if (isset($params['type'])) {
            if ($params['type'] == 'SC')
                $this->initSphereCompilers($params);
            else if ($params['type'] == 'SP')
                $this->initSphereProblems($params);
        }
    }

    private function initSphereCompilers($params)
    {
        $this->init($params);
        $this->baseurl = 'http://api.compilers.sphere-engine.com/api/' . $this->version . '/';

        $this->access_token = (isset($params['access_token'])) ? $params['access_token'] : '';
    }

    public function initSphereProblems($params)
    {
        $this->init($params);
        $this->baseurl = 'http://problems.sphere-engine.com/api/v' . $this->version . '/';

        if (isset($params['username']) && isset($params['password']))
            $data = $this->auth($params['username'], $params['password']);
    }

    private function init($params)
    {
        $this->type = $params['type'];
        $this->version = (isset($params['version'])) ? $params['version'] : '3';
        $this->default_language_id = 11; // hardcoded C language
    }

    public function setDefaultLanguage($language)
    {
        $this->default_language_id = $language;
    }

    public function getDefaultLanguage()
    {
        return $this->default_language_id;
    }

    public function test()
    {
        $url = $this->baseurl . 'test?access_token=' . $this->access_token;
        return $this->get_content($url);
    }

    public function auth($username, $password)
    {
        if ($this->type == 'SP') {
            $url = $this->baseurl . 'auth';
            $data = array(
                'username' => $username,
                'password' => $password,
                );
            $response = $this->get_content($url, 'POST', $data);
            $this->access_token = $response['access_token'];
            return $response;
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    public function languages()
    {
        $url = $this->baseurl . 'languages?access_token=' . $this->access_token;
        return $this->get_content($url);
    }

    public function getSubmission($id, $withSource=0, $withInput=0, $withOutput=0, $withStderr=0, $withCmpinfo=0)
    {
        if ($this->type == 'SC') {
            $data = array(
                'withSource' => $withSource,
                'withInput' => $withInput,
                'withOutput' => $withOutput,
                'withStderr' => $withStderr,
                'withCmpinfo' => $withCmpinfo,
                'access_token' => $this->access_token
                );
        } else if ($this->type == 'SP') {
            $data = array(
                'access_token' => $this->access_token
                );
        }
        $url = $this->baseurl . 'submissions/' . $id . '?' . http_build_query($data, '', '&');
        return $this->get_content($url);   
    }
    
/*
access_token        query   string
problemCode     form    string
languageId      form    integer
source      form    string
contestCode     form    string
userId      form    integer
private bool
*/
    public function sendSubmission($sourceCode='', $language=NULL, $input='')
    {
        if ($language == NULL) $language = $this->default_language_id;

        $url = $this->baseurl . 'submissions?access_token=' . $this->access_token;
        $data = array(
            'sourceCode' => $sourceCode,
            'language' => intval($language),
            'input' => $input,
            );
        return $this->get_content($url, 'POST', $data);
    }

    public function getProblem($problemCode)
    {
        if ($this->type == 'SP') {
            $url = $this->baseurl . 'problems/' . $problemCode . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET');
        } else
            return 'Error: action available only for Sphere Problem service';
    }



    private function get_content($url, $type='GET', $data=array())
    {
        return json_decode($this->get_content_curlless($url, $type, $data), true);
    }

    private function get_content_curlless($url, $type='GET', $data=array())
    {
        if ($type == 'GET') {
            $options = array(
                'http' => array(
                'method' => "GET",
                'ignore_errors' => true
                )
            );
            $context  = stream_context_create($options);
            return file_get_contents($url, false, $context);
        } else if ($type == 'POST') {
            $options = array(
                'http' => array( // even if https
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'ignore_errors' => true,
                    'content' => http_build_query($data),
                ),
            );
            $context  = stream_context_create($options);
            return file_get_contents($url, false, $context);
        } else {
            return 'ERROR';
        }
    }

    /*
    private function get_content_curl($url)
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch); 

        return $response;
    }
    */
}
