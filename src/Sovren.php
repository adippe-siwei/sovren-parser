<?php

namespace Siwei\SovrenParser;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Sovren
{
    protected $client;
    private $rootNode;
    private $result = [
        'infos' => [],
        'jobs' => [],
        'diplomas' => []
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function parse($filePath)
    {
        if (!file_exists($filePath)) {
            return new Exception("File does not exists");
        }

        $json = $this->getApiResponse(file_get_contents($filePath));
        $this->rootNode = json_decode($json['Value']['ParsedDocument'], true);
        $this->rootNode = $this->rootNode['Resume'];

        if (is_array($this->rootNode)) {
            foreach (config('sovren.parseConfigMain') as $fieldName => $path) {
                $this->result['infos'][$fieldName] = $this->parseInfo($path, $this->rootNode);
            }

            if ($this->checkDeepArrayKey('StructuredXMLResume.EmploymentHistory.EmployerOrg', $this->rootNode)) {
                foreach ($this->rootNode['StructuredXMLResume']['EmploymentHistory']['EmployerOrg'] as $jobNode) {
                    $job = [];
                    foreach (config('sovren.parseConfigJobs') as $fieldName => $path) {
                        $job[$fieldName] = $this->parseInfo($path, $jobNode);
                    }
                    $this->result['jobs'][] = $job;
                }
            }

            if ($this->checkDeepArrayKey('StructuredXMLResume.EducationHistory.SchoolOrInstitution', $this->rootNode)) {
                foreach ($this->rootNode['StructuredXMLResume']['EducationHistory']['SchoolOrInstitution'] as $schoolNode) {
                    $diplomas = [];
                    foreach (config('sovren.parseConfigDiploma') as $fieldName => $path) {
                        $diplomas[$fieldName] = $this->parseInfo($path, $schoolNode);
                    }
                    $this->result['diplomas'][] = $diplomas;
                }
            }
        }

        return $this->result;
    }

    private function getApiResponse(string $fileContent)
    {
        try {
            $response = $this->client->post('', [
                'json' => [
                    'DocumentAsBase64String' => base64_encode($fileContent)
                ]
            ]);
        } catch (ClientException $e) {
            return json_decode($e->getResponse()->getBody(), true);
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    private static function checkDeepArrayKey($path, $array)
    {
        $path = explode('.', $path);
        $currentArray = $array;
        foreach ($path as $node) {
            if (array_key_exists($node, $currentArray)) {
                $currentArray = $currentArray[$node];
                continue;
            } else {
                return false;
            }
        }

        return true;
    }

    private static function parseInfo($path, $node)
    {
        $path = explode('.', $path);
        $currentLevel = $node;

        foreach ($path as $level) {
            if (substr($level, 0, 1) == '*') {
                $level = substr($level, 1, strlen($level));
                foreach ($currentLevel as $idx => $row) {
                    if (array_key_exists($level, $row)) {
                        if (is_array($row[$level])) {
                            $currentLevel = $row[$level];
                            break;
                        } else {
                            return $row[$level];
                        }
                    }
                }
            } else {
                if (array_key_exists($level, $currentLevel)) {
                    if (is_array($currentLevel[$level])) {
                        $currentLevel = $currentLevel[$level];
                        continue;
                    } else {
                        return $currentLevel[$level];
                    }
                } else {
                    return null;
                }
            }
        }

        return null;
    }
}
