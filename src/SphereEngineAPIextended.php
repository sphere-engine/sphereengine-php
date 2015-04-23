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
            // users
            'users' => 5,
            'addUser' => 5,
            'getUser' => 5,
            'removeUser' => 5,
            // judges
            'judges' => 5,
            'getJudge' => 5,
            'removeJudge' => 5,
            'addJudge' => 5,
            'updateJudge' => 5,
            // submissions
            'submissions' => 5,
            'getSubmission' => 5,
            'sendSubmission' => 10,
            // problems
            'problems' => 5,
            'getProblem' => 5,
            'addProblem' => 5,
            'updateProblem' => 5,
            'removeProblem' => 5,
            // testcases
            'testcases' => 5,
            'addTestcase' => 5,
            'updateTestcase' => 5,
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
     * 
     * USERS
     *
     */ 

    /**
     * Get users
     * @return list of users or error
     */ 
    public function users()
    {
        if ($this->type == 'SP') {
            $data['method'] = 'users';
            $url = $this->baseurl . 'users?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Add user
     *
     * @param  
     * @param  
     * @param  
     *
     * @return success info or error
     */ 
    public function addUser($name)
    {
        if ($this->type == 'SP') {
            $data = array(
                'username' => $name,
                );
            $data['method'] = 'addUser';
            $url = $this->baseurl . 'users?access_token=' . $this->access_token;
            return $this->get_content($url, 'POST', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Get user by id
     * @return user info or error
     */ 
    public function getUser($id)
    {
        if ($this->type == 'SP') {
            $data['method'] = 'getUser';
            $url = $this->baseurl . 'users/' . $id . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Remove user
     * @return success info or error
     */ 
    public function removeUser($id)
    {
        if ($this->type == 'SP') {
            $data['method'] = 'removeUser';
            $url = $this->baseurl . 'users/' . $id . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'DELETE', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }


    /**
     * 
     * JUDGES
     *
     */ 

    /**
     * Get available judges
     * @return list of judges or error
     */ 
    public function judges()
    {
        if ($this->type == 'SP') {
            $data['method'] = 'judges';
            $url = $this->baseurl . 'judges?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Get judge by id
     * @return judge info or error
     */ 
    public function getJudge($id)
    {
        if ($this->type == 'SP') {
            $data['method'] = 'getJudge';
            $url = $this->baseurl . 'judges/' . $id . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Remove judge
     * @return success info or error
     */ 
    public function removeJudge($id)
    {
        if ($this->type == 'SP') {
            $data['method'] = 'removeJudge';
            $url = $this->baseurl . 'judges/' . $id . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'DELETE', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Add judge
     *
     * @param  string   $name           judge name
     * @param  string   $source         judge source code
     * @param  integer  $language       source code language (default if not provided)
     *
     * @return success info or error
     */ 
    public function addJudge($name, $source, $language=NULL)
    {
        if ($this->type == 'SP') {
            $data = array(
                'name' => $name,
                'sourceCode' => $source,
                'languageId' => (isset($language) ? $language : $this->default_language_id),
                );
            $data['method'] = 'addJudge';
            $url = $this->baseurl . 'judges?access_token=' . $this->access_token;
            return $this->get_content($url, 'POST', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Modify judge
     *
     * @param  integer  $id             judge id
     * @param  string   $name           judge name
     * @param  string   $source         judge source code
     * @param  integer  $language       source code language (default if not provided)
     *
     * @return success info or error
     */ 
    public function updateJudge($id, $name, $source, $language=NULL)
    {
        if ($this->type == 'SP') {
            $data = array(
                'name' => $name,
                'sourceCode' => $source,
                'languageId' => (isset($language) ? $language : $this->default_language_id),
                );
            $data['method'] = 'updateJudge';
            $url = $this->baseurl . 'judges/' . $id . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'PUT', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }


    /**
     * 
     * SUBMISSIONS
     *
     */ 

    /**
     * Get submissions
     *
     * @return submission info or error
     */ 
    public function submissions()
    {
        if ($this->type == 'SP') {
            $data['method'] = 'submissions';
            $url = $this->baseurl . 'submissions?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service'; 
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
     *                                  'problemCode' => string, (required)
     *                                  'language' => integer, (required)
     *                                  'source' => string, (required)
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
     * 
     * PROBLEMS
     *
     */ 

    /**
     * Get problems (SphereProblems only)
     *
     * @return problem list or error
     */ 
    public function problems()
    {
        $data['method'] = 'problems';
        if ($this->type == 'SP') {
            $url = $this->baseurl . 'problems?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
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
     * Add problem
     *
     * @param  
     * @param  
     * @param  
     * @param  
     * @param  
     *
     * @return success info or error
     */ 
    public function addProblem($code, $name, $body, $type, $interactive, $seq)
    {
        if ($this->type == 'SP') {
            $data = array(
                    'problemCode' => $code,
                    'problemName' => $name,
                    'problemBody' => $body,
                    'problemType' => $type,
                    'interactive' => $interactive,          // nie działa
                    'seq' => $seq,                          // nie działa
                );
            $data['method'] = 'addProblem';
            $url = $this->baseurl . 'problems?access_token=' . $this->access_token;
            return $this->get_content($url, 'POST', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Update problem
     *
     * @param  
     * @param  
     * @param  
     * @param  
     * @param  
     *
     * @return success info or error
     */ 
    public function updateProblem($problemCode, $name, $body, $type, $interactive)
    {
        if ($this->type == 'SP') {
            $data = array(
                    'problemName' => $name,
                    'problemBody' => $body,
                    'problemType' => $type,
                    'interactive' => $interactive,
                );
            $data['method'] = 'updateProblem';
            $url = $this->baseurl . 'problems/' . $problemCode . '?access_token=' . $this->access_token;
            echo $url;
            return $this->get_content($url, 'PUT', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Remove problem
     *
     * @return success info or error
     */ 
    public function removeProblem($problemCode)
    {
        if ($this->type == 'SP') {
            $data['method'] = 'removeProblem';
            $url = $this->baseurl . 'problems/' . $problemCode . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'DELETE', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * 
     * TESTCASES
     *
     */ 

    /**
     * Get testcases (SphereProblems only)
     *
     * @return testcases list or error
     */ 
    public function testcases($problemCode)
    {
        $data['method'] = 'testcases';
        if ($this->type == 'SP') {
            $url = $this->baseurl . 'problems/' . $problemCode . '/testcases?access_token=' . $this->access_token;
            return $this->get_content($url, 'GET', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Add testcase (SphereProblems only)
     *
     */ 
    public function addTestcase($problemCode)
    {
        $data['method'] = 'addTestcase';
        if ($this->type == 'SP') {
            $data = array(
                  // UZUPEŁNIĆ
                );
            $url = $this->baseurl . 'problems/' . $problemCode . '/testcases?access_token=' . $this->access_token;
            return $this->get_content($url, 'POST', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }

    /**
     * Update testcase (SphereProblems only)
     *
     */ 
    public function updateTestcase($problemCode, $id)
    {
        $data['method'] = 'updateTestcase';
        if ($this->type == 'SP') {
            $data = array(
                  // UZUPEŁNIĆ
                );
            $url = $this->baseurl . 'problems/' . $problemCode . '/testcases/' . $id . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'POST', $data);
        } else
            return 'Error: action available only for Sphere Problem service';
    }


    /**
     * Remove testcase
     *
     * @param  string   $problemCode    problem code
     * @param  integer  $id             testcase id
     *
     * @return success info or error
     */ 
    public function removeProblem($problemCode, $id)
    {
        if ($this->type == 'SP') {
            $data['method'] = 'removeProblem';
            $url = $this->baseurl . 'problems/' . $problemCode . '/testcases/' . $id . '?access_token=' . $this->access_token;
            return $this->get_content($url, 'DELETE', $data);
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
        } else if ($type == 'DELETE') {
            $options = array(
                'http' => array( // even if https
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'DELETE',
                    'timeout' => $this->getTimeout($method),
                    'ignore_errors' => true,
                ),
            );
            $context  = stream_context_create($options);
            if (($content = @file_get_contents($url, false, $context)) === FALSE) {
                return 'timeout';
            } else
                return json_decode($content, true);
        } else if ($type == 'PUT') {
            $options = array(
                'http' => array( // even if https
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'PUT',
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
