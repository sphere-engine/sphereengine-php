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
 * @version    0.5
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
    // timeout settings
    private $use_timeouts;
    // url of web service
    private $baseurl;

    // timeouts for methods
    private $timeout = array(
            'test' => 5,
            'languages' => 5,
            'getSubmission' => 5,
            'sendSubmission' => 10,
            'getProblem' => 5
        );

    public function __construct($params=array())
    {
        $this->type = (isset($params['type'])) ? $params['type'] : 'SC';
        $this->version = (isset($params['version'])) ? $params['version'] : '3';
        $this->access_token = (isset($params['access_token'])) ? $params['access_token'] : '';
        $this->default_language_id = 11; // hardcoded C language
        $this->use_timeouts = (isset($params['timeouts'])) ? intval($params['timeouts']) : 1;

        if ($params['type'] == 'SC')
            $this->baseurl = 'http://api.compilers.sphere-engine.com/api/' . $this->version . '/';
        else if ($params['type'] == 'SP')
            $this->baseurl = 'http://problems.sphere-engine.com/api/v' . $this->version . '/';
    }

/**
 * API settings
 *
 */

    /**
     * Set default language
     *
     * @param  integer      $language       id of the language
     */ 
    public function setDefaultLanguage($language)
    {
        $this->default_language_id = $language;
    }

    /**
     * Enable or disable timeouts for connections
     *
     * @param  bool      $t       true to enable timeouts, false to disable timeouts
     */ 
    public function setTimeouts($t)
    {
        $this->use_timeouts = intval($t);
    }

    private function getTimeout($method)
    {
        if ($this->use_timeouts)
            return $this->timeout[$method];
        else
            return intval(ini_get('max_execution_time'));
    }

/**
 * API functions
 *
 */

    /**
     * @return test message or error
     */ 
    public function test()
    {
        $data['method'] = 'test';
        $url = $this->baseurl . 'test?access_token=' . $this->access_token;
        return $this->get_content($url, 'GET', $data);
    }

    /**
     * Get available languages
     * @return list of languages or error
     */ 
    public function languages()
    {
        $data['method'] = 'languages';
        $url = $this->baseurl . 'languages?access_token=' . $this->access_token;
        return $this->get_content($url, 'GET', $data);
    }

    /**
     * Get submission by ID
     *
     * @param  integer  $id         id of the submission
     * @param  array    $params     SphereCompilers: 
     *                                  'withSource' => bool,
     *                                  'withInput' => bool,
     *                                  'withOutput' => bool,
     *                                  'withStderr' => bool,
     *                                  'withCmpinfo' => bool
     *                              SphereProblems: 
     *                                  not applicable
     * @return submission info or error
     */ 
    public function getSubmission($id, $params=array())
    {
        if ($this->type == 'SC') {
            $data = array(
                'withSource' => intval(isset($params['withSource']) ? $params['withSource'] : 0),
                'withInput' => intval(isset($params['withInput']) ? $params['withInput'] : 0),
                'withOutput' => intval(isset($params['withOutput']) ? $params['withOutput'] : 0),
                'withStderr' => intval(isset($params['withStderr']) ? $params['withStderr'] : 0),
                'withCmpinfo' => intval(isset($params['withCmpinfo']) ? $params['withCmpinfo'] : 0),
                );
        }
        $data['access_token'] = $this->access_token;
        $data['method'] = 'getSubmission';
        $url = $this->baseurl . 'submissions/' . $id . '?' . http_build_query($data, '', '&');
        return $this->get_content($url, 'GET', $data);   
    }
    

    /**
     * Send submission
     *
     * @param  array    $params     SphereCompilers: 
     *                                  'source' => string,
     *                                  'language' => integer,
     *                                  'input' => string,
     *                              SphereProblems: 
     *                                  'problemCode' => string,
     *                                  'language' => integer,
     *                                  'source' => string,
     *                                  'contestCode' => string,
     *                                  'userId' => integer,
     *                                  'private' => bool,
     * @return submission id or error
     */ 
    public function sendSubmission($params=array())
    {
        if ($this->type == 'SC') {
            $data = array(
                'sourceCode' => (isset($params['source']) ? $params['source'] : ''),
                'language' => (isset($params['language']) ? $params['language'] : $this->default_language_id),
                'input' => (isset($params['input']) ? $params['input'] : '')
                );
        } else if ($this->type == 'SP') {
            $data = array(
                    'problemCode' => (isset($params['problemCode']) ? $params['problemCode'] : 'TEST'),
                    'languageId' => intval(isset($params['language']) ? $params['language'] : $this->default_language_id),
                    'source' => (isset($params['source']) ? $params['source'] : 'TEST'),
                    'contestCode' => (isset($params['contestCode']) ? $params['contestCode'] : ''),
                    'userId' => intval(isset($params['userId']) ? $params['userId'] : 0),
                    'private' => intval(isset($params['private']) ? $params['private'] : 0)
                );
        }
        $data['method'] = 'sendSubmission';
        $url = $this->baseurl . 'submissions?access_token=' . $this->access_token;
        return $this->get_content($url, 'POST', $data);
    }

    /**
     * Get problem info (SphereProblems only)
     *
     * @param  string    $problemCode    Code of the problem
     * @return problem info or error
     */ 
    public function getProblem($problemCode)
    {
        $data['method'] = 'getProblem';
        if ($this->type == 'SP') {
            $url = $this->baseurl . 'problems/' . $problemCode . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

/**
 * API connection
 *
 */

    private function get_content($url, $type='GET', $data=array())
    {
        // get proper timeout by calling method
        $method = (isset($data['method'])) ? $data['method'] : 'test';
        echo $this->getTimeout($method);
        if ($type == 'GET') {
            $options = array(
                'http' => array(
                'method' => 'GET',
                'timeout' => $this->getTimeout($method),
                'ignore_errors' => true
                )
            );
            $context  = stream_context_create($options);
            if (($content = @file_get_contents($url, false, $context)) === FALSE) {
                return 'timeout';
            } else
                return json_decode($content, true);
        } else if ($type == 'POST') {
            $options = array(
                'http' => array( // even if https
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'timeout' => $this->getTimeout($method),
                    'ignore_errors' => true,
                    'content' => http_build_query($data),
                ),
            );
            $context  = stream_context_create($options);
            if (($content = @file_get_contents($url, false, $context)) === FALSE) {
                return 'timeout';
            } else
                return json_decode($content, true);
        } else {
            return 'ERROR';
        }
    }
}
