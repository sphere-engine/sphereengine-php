<?php

require "src/SphereEngineAPI.php";

// for the time when there is no single access token
$SE1 = new SphereEngineAPI('access_token');
$SE2 = new SphereEngineAPI('access_token');


$test =  '<h3>Sphere Engine Compilers</h3>';
$test .= '<p>Test: ' . substr(json_encode(($SE1->compilers->test())), 0, 100) . '</p>';
$test .= '<p>Languages: ' . substr(json_encode(($SE1->compilers->languages())), 0, 100) . '...</p>';
$test .= '<p>Send submission: ' . substr(json_encode(($SE1->compilers->sendSubmission('int main() { return 0; }'))), 0, 100) . '</p>';
$test .= '<p>Get submission: ' . substr(json_encode(($SE1->compilers->getSubmission(33880429))), 0, 100) . '</p>';
$test .= '<h3>Sphere Engine Problems</h3>';
$test .= '<p>Test: ' . substr(json_encode(($SE2->problems->test())), 0, 100) . '</p>';
$test .= '<p>Languages: ' . substr(json_encode(($SE2->problems->languages())), 0, 100) . '...</p>';
$test .= '<p>Send submission: ' . substr(json_encode(($SE2->problems->sendSubmission('SEDEMO4', 'int main() { return 0; }'))), 0, 100) . '</p>';
$test .= '<p>Get submission: ' . substr(json_encode(($SE2->problems->getSubmission(2196))), 0, 100) . '</p>';
$test .= '<p>Problems list: ' . substr(json_encode(($SE2->problems->problemsList())), 0, 100) . '...</p>';
$test .= '<p>Get problem: ' . substr(json_encode(($SE2->problems->getProblem('SEDEMO4'))), 0, 80) . '...</p>';

echo $test;
