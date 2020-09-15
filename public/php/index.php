<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require_once 'config.php';
include 'Helpers/complexHelper.php';
require 'Helpers/ConstantsHelper.php';

$app = new \Slim\App;

$app->post('/login', function (Request $request, Response $response) {    
    $input = $request->getParams();
    
    if (!isset($input['username'])) {
        $data['message'] = 'Invalid Username and Password combination';
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json')->withJson($b);
    }
    
    global $DB;
    
    $sql = "SELECT * FROM user WHERE username = '" . $input['username'] . "'";
    
    try {
        $stmt = $DB->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch();
    } catch (Exception $ex) {
        $data['message'] = 'Invalid Username and Password combination';
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json')->withJson($data);
    }
    
    if (!$results) {
        $data['message'] = 'Invalid Username and Password combination';
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json')->withJson($data);
    }  
    
    if(password_verify($input['password'], $results['password'])) {
        $data = true;
        
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json')->withJson($results);
    } else {
        $data = false;
        
        $data['message'] = 'Invalid Username and Password combination';
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json')->withJson($data);
    }  
});

$app->post('/tickets', function (Request $request, Response $response) {
    
    global $DB;
    
    $input = $request->getParams();
    
    $sortId = $input['sortId'];
    $offset = $input['offset'] * 10;
    
    switch ($sortId) {
        case 'creation_date':
        case 'assigned_date':
        case 'status_id':
            $sortId = $sortId . ' DESC';
            break;
        case 'first_name':
        case 'last_name':
            $sortId = $sortId . ' ASC';
            break;
    }
    //Total Tickets
    $sql = "SELECT 
                count(*) as num
            FROM
                tickets T;";    
    try {
        $stmt = $DB->prepare($sql);
        $stmt->execute();
        $totalTickets = $stmt->fetch();
    } catch (Exception $ex) {
        echo errorMessage($ex->getMessage());
    }
    $report['totalTickets'] = $totalTickets['num'];
    
    $pagination = (int) $totalTickets / 10;
    $paginationArray = [];
    
    for ($i = 1; $i < ceil($pagination) + 1; $i++) {
        array_push($paginationArray, $i);
    }
    
    $report['pagination'] = $paginationArray;
    
    $sql = "SELECT 
                T.id,
                creation_date,
                status_id,
                title,
                assigned_date,
                category,
                description,
                U.first_name,
                U.last_name,
                U.email
            FROM
                tickets T
                    LEFT JOIN
                user U ON T.created_user_id = U.id
            ORDER BY ".$sortId."
            LIMIT 10 OFFSET ".$offset.";";    
    
    try {
        $stmt = $DB->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
    } catch (Exception $ex) {
        echo errorMessage($ex->getMessage());
    }
    
    $report['tickets'] = $results;
    
    return $response->withStatus(200)->withJson($report);
});

$app->post('/ticket/log', function (Request $request, Response $response) {
    
    global $DB;
    
    $input = $request->getParams();
    
    //Total Tickets
    $sql = "INSERT INTO tickets (created_user_id, status_id, title, assigned_date, category, description)
            VALUES (".$input['user_id'].", 1, '".$input['title']."', '".$input['date']."', '".$input['category']."', '".$input['description']."')";
    
    try {
        $stmt = $DB->prepare($sql);
        $stmt->execute();
    } catch (Exception $ex) {
        echo errorMessage($ex->getMessage());
    }
    
    return $response->withStatus(200)->withJson(true);
});

$app->post('/ticket/update', function (Request $request, Response $response) {
    
    global $DB;
    
    $input = $request->getParams();
    
    switch ($input['status']) {
        case 'Newly Opened':
            $statusId = 1;
            break;
        case 'Progressing':
            $statusId = 2;
            break;
        case 'Resolved':
            $statusId = 3;
            break;
    }
    
    //Total Tickets
    $sql = "UPDATE tickets
            SET
                status_id = ".$statusId."
            WHERE id = " . $input['id'];
    try {
        $stmt = $DB->prepare($sql);
        $stmt->execute();
    } catch (Exception $ex) {
        echo errorMessage($ex->getMessage());
    }
    
    return $response->withStatus(200)->withJson(true);
});

$app->post('/complex-query', function (Request $request, Response $response) {
    
    global $DB;
    
    $input = $request->getParams();
    
    $filter = "";
    
    if (isset($input['filter'])) {
        switch ($input['filter']) {
            case 'Animals': 
                $filter = "WHERE I.name = 'Animals'";
                break;
            case 'ChildrenSport': 
                $filter = "WHERE I.name = 'Children' OR I.name = 'Sport'";
                break;
            default:
                $filter = "";
        }
    }
            
    //clear Tables
    ComplexHelper::clearTables();
    //Insert Personal Details
    ComplexHelper::insertPersonalDetailsTable();
    //Insert Interests
    ComplexHelper::insertInterestsTable();
    //Insert Documents
    ComplexHelper::insertDocumentsTable();
    //Insert Personal Interests
    ComplexHelper::insertPersonalInterestsTable();
    
    //interests
    $interests = ConstantsHelper::$INTERESTS;
    //names
    $names = ConstantsHelper::$NAMES;
        
    //Get Data
    $sql = "SELECT 
                P.name as person_name, I.name as interest_name, D.name as document_name
            FROM
                personinterest PI
                    LEFT JOIN
                documents D ON PI.interest_id = D.interest_id
                    INNER JOIN
                personaldetails P ON P.id = PI.person_id
                    INNER JOIN
                interests I ON I.id = PI.interest_id
               ".$filter."
            ;";
            
    try {
        $stmt = $DB->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
    } catch (Exception $ex) {
        echo errorMessage($ex->getMessage());
    }
    
    $nameReport = [];
    $report = [];
    $multipleName = [];
    
    foreach($names as $name) {
        $report[$name] = [];
        $nameReport[$name]['interests'] = [];
        $nameReport[$name]['documents'] = [];
        foreach ($results as $key=>$value) {
            if ($name == $value['person_name']) {
                if (!in_array($value['interest_name'], $nameReport[$name]['interests'])) {
                    array_push($nameReport[$name]['interests'], $value['interest_name']);
                }
                array_push($nameReport[$name]['documents'], $value['document_name']);
            }
        }
        if (in_array('Sport', $nameReport[$name]['interests']) || in_array('Fishing', $nameReport[$name]['interests'])) {
            $nameReport[$name]['documents'] = [];
        }
        
        $multipleDocuments = false;
        foreach($nameReport[$name]['interests'] as $interest) {
            $data = [];
            $data['documents'] = [];
            $numberOfDocuments = rand(1, 5);
            $index = 0;
            shuffle($nameReport[$name]['documents']);
            foreach($nameReport[$name]['documents'] as $document) {
                if ($numberOfDocuments > $index) {
                    if (substr($document, 0, strpos($document,'/')) == $interest) {
                        array_push($data['documents'], $document);
                    }
                    $index = $index + 1;
                }
            }
            if (isset($input['filter'])) {
                switch ($input['filter']) {
                    case []:
                    case 'ChildrenSport':
                        $data['interest'] = $interest;
                        array_push($report[$name], $data);
                        break;
                    case 'Animals': 
                        if (count($data['documents']) == 1) {
                            $data['interest'] = $interest;
                            array_push($report[$name], $data);
                        } else {
                            unset($report[$name]);
                        }
                        break;
                    case 'MultipleInterest':
                        if (count($nameReport[$name]['interests']) == 5 || count($nameReport[$name]['interests']) == 6) {
                            $data['interest'] = $interest;
                            if(count($data['documents']) > 1) {
                                $multipleDocuments = true;
                            }
                            array_push($multipleName, $data);
                        } else {
                            unset($report[$name]);
                        }
                }
            } else {
                $data['interest'] = $interest;
                array_push($report[$name], $data);
            }
        }
        if (isset($input['filter'])) {
            if ($input['filter'] == 'MultipleInterest' && $multipleDocuments == true) {
                $report[$name] = $multipleName;
            }
        }
    }
    
    return $response->withStatus(200)->withJson($report);
});

$app->run();
