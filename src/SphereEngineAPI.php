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
 * @version    0.6
 */


/**
 * SphereEngineAPI
 *
 */
class SphereEngineAPI 
{
    public $compilers;
    public $problems;

    public function __construct($access_token, $url_compilers=NULL, $url_problems=NULL)
    {
        $this->compilers = new SphereEngineCompilersAPI($access_token, $url_compilers);
        $this->problems = new SphereEngineProblemsAPI($access_token, $url_problems);
    }

    /**
     * Set default language
     *
     * @param  integer      $language       id of the language
     */ 
    public function setDefaultLanguage($language)
    {
        $this->compilers->setDefaultLanguage($language);
        $this->problems->setDefaultLanguage($language);
    }

    /**
     * Enable or disable timeouts for connections
     *
     * @param  bool      $t       true to enable timeouts, false to disable timeouts
     */ 
    public function setTimeouts($t)
    {
        $this->compilers->setTimeouts($t);
        $this->problems->setTimeouts($t);
    }
}


/**
 * SphereEngineCompilersAPI
 *
 */
class SphereEngineCompilersAPI 
{
    // version of API
    private $version = 3;
    // access token
    private $access_token;
    // default language
    public $default_language_id = 11;
    // timeout settings
    public $use_timeouts = 1;
    // url of web service
    private $baseurl;

    // timeouts for methods
    private $timeout = array(
            'test' => 5,
            'languages' => 5,
            'getSubmission' => 5,
            'sendSubmission' => 10,
        );

    public function __construct($access_token, $url=NULL)
    {
        $this->access_token = $access_token;
        if (isset($url))
            $this->baseurl = $url;
        else
            $this->baseurl = 'http://api.compilers.sphere-engine.com/api/' . $this->version . '/';
    }

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
     * Test API
     *
     * @return test message or error
     */ 
    public function test()
    {
        $url = $this->baseurl . 'test?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('test'));
    }

    /**
     * Get available languages
     *
     * @return list of languages or error
     */ 
    public function languages()
    {
        $url = $this->baseurl . 'languages?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('languages'));
    }

    /**
     * Get submission by ID
     *
     * @param  integer   $id                    id of the submission
     * @param  bool      $withSource            include source in response
     * @param  bool      $withInput             include input in response
     * @param  bool      $withOutput            include output in response
     * @param  bool      $withStderr            include stderr info in response
     * @param  bool      $withCmpinfo           include cmpinfo in response
     *
     * @return submission info or error
     */ 
    public function getSubmission($id, $withSource=0, $withInput=0, $withOutput=0, $withStderr=0, $withCmpinfo=0)
    {
        $data = array(
            'withSource' => intval($withSource),
            'withInput' => intval($withInput),
            'withOutput' => intval($withOutput),
            'withStderr' => intval($withStderr),
            'withCmpinfo' => intval($withCmpinfo),
            );
        $data['access_token'] = $this->access_token;
        $url = $this->baseurl . 'submissions/' . $id . '?' . http_build_query($data, '', '&');
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('getSubmission')); 
    }


    /**
     * Send submission
     *
     * @param  string    $source        source code
     * @param  integer   $language      language ID
     * @param  string    $input         input for the program
     *
     * @return submission id or error
     */ 
    public function sendSubmission($source, $language=NULL, $input='')
    {
        $data = array(
            'sourceCode' => $source,
            'language' => intval((isset($language) ? $language : $this->default_language_id)),
            'input' => $input,
            );
     
        $url = $this->baseurl . 'submissions?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'POST', $this->getTimeout('sendSubmission'), $data);
    }
}


/**
 * SphereEngineProblemsAPI
 *
 */
class SphereEngineProblemsAPI 
{
    // version of API
    private $version = 3;
    // access token
    private $access_token;
    // default language
    public $default_language_id = 11; //hardcoded C
    // timeout settings
    public $use_timeouts = 1;
    // url of web service
    private $baseurl;

    // timeouts for methods
    private $timeout = array(
            'test' => 5,
            'languages' => 5,
            'getSubmission' => 5,
            'sendSubmission' => 10,
            'problemsList' => 5,
            'getProblem' => 5,
        );

    public function __construct($access_token, $url=NULL)
    {
        $this->access_token = $access_token;
        if (isset($url))
            $this->baseurl = $url;
        else
            $this->baseurl = 'http://problems.sphere-engine.com/api/v' . $this->version . '/';
    }

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
     * Test API
     *
     * @return test message or error
     */ 
    public function test()
    {
        $url = $this->baseurl . 'test?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('test'));
    }

    /**
     * Get available languages
     *
     * @return list of languages or error
     */ 
    public function languages()
    {
        $url = $this->baseurl . 'languages?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('languages'));
    }

    /**
     * Get submission by ID
     *
     * @param  integer  $id         id of the submission
     *
     * @return submission info or error
     */ 
    public function getSubmission($id)
    {
        $url = $this->baseurl . 'submissions/' . $id . '?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('getSubmission'));   
    }


    /**
     * Send submission
     *
     * @param  string       $problemCode       code of the problem
     * @param  string       $source            source code
     * @param  integer      $language          language id
     * @param  string       $contestCode       code of the contest
     * @param  integer      $userId            user ID
     * @param  bool         $private           flag for private submissions
     *
     * @return submission id or error
     */ 
    public function sendSubmission($problemCode, $source, $language=NULL, $contestCode='', $userId=0, $private=0)
    {
        $data = array(
            'problemCode' => $problemCode,
            'source' => $source,
            'languageId' => intval((isset($language) ? $language : $this->default_language_id)),
            'contestCode' => $contestCode,
            'userId' => intval($userId),
            'private' => intval($private)
            );
        $url = $this->baseurl . 'submissions?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'POST', $this->getTimeout('sendSubmission'), $data);
    }

    /**
     * Get problems                       CHYBA TU JEST PAGINACJA??
     *
     * @return problem list or error
     */ 
    public function problemsList()
    {
        $url = $this->baseurl . 'problems?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('problemsList'));

    }

    /**
     * Get problem info
     *
     * @param  string    $problemCode    Code of the problem
     * @return problem info or error
     */ 
    public function getProblem($problemCode)
    {
        $url = $this->baseurl . 'problems/' . $problemCode . '?access_token=' . $this->access_token;
        return SphereEngineREST::get_content($url, 'GET', $this->getTimeout('getProblem'));
    }
}

/**
 * SphereEngineREST
 *
 */
class SphereEngineREST
{
    public static function get_content($url, $type='GET', $timeout=10, $data=array())
    {
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n",
                    'method' => $type,
                    'timeout' => $timeout,
                    'ignore_errors' => true,
                    'content' => http_build_query($data),
                )
            );
        $context  = stream_context_create($options);
        if (($content = @file_get_contents($url, false, $context)) === FALSE)
            return 'ERROR: timeout or other exception';
        else
            return json_decode($content, true);
    }
}