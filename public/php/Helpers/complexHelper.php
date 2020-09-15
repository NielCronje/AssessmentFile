<?php

class ComplexHelper {
    
    static function clearTables() {
        global $DB;
        //clear tables
        $sql = "DELETE FROM personinterest where person_id>0;
                ALTER TABLE personinterest AUTO_INCREMENT = 1;
                DELETE FROM documents where id>0;
                ALTER TABLE documents AUTO_INCREMENT = 1;
                DELETE FROM interests where id>0;
                ALTER TABLE interests AUTO_INCREMENT = 1;
                DELETE FROM personaldetails where id>0;
                ALTER TABLE personaldetails AUTO_INCREMENT = 1;";
        try {
            $stmt = $DB->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex) {
            echo errorMessage($ex->getMessage());
        }
    }
    
    static function insertPersonalDetailsTable() {
        global $DB;
        //names
        $names = ["ANNABELLE","MADDISON","ARIANA","AYLA","PEYTON","MADISON","FRANKIE","PIPER","PIPPA","QUINN","ADDISON",
            "INDIANA","PAIGE","SADIE","AURORA","EMILIA","HARLOW","HAYLEY","STELLA","SUMMER","ELEANOR","HOLLY","LARA","LOLA","LILLY","DELILAH","ELENA","ELOISE",
            "ANNA","BROOKLYN","CHARLIE","CLAIRE","ESTHER","MAYA","SOFIA","ADELINE","ALEXIS","AMBER","ELIZA","ALLEGRA","EDEN","ELISE","FREYA","OLIVE",
            "ELIZABETH","HEIDI","JASMINE","LUNA","MAGGIE","MOLLY"];

        //Insert Names
        foreach ($names as $name) {
            $sql = "INSERT INTO personaldetails
                        (name)
                    VALUES
                        ('".$name."');";
            try {
                $stmt = $DB->prepare($sql);
                $stmt->execute();
            } catch (Exception $ex) {
                echo errorMessage($ex->getMessage());
            }
        }
    }
    
    static function insertInterestsTable() {
        global $DB;
        //interests
        $interests = ['Sport', 'Fishing', 'Gardening', 'Children', 'Animals', 'Astronomy', 'Cars', 'Theatre', 'Archery', 'Video Games',
        'Travelling', 'Artificial Intelligence', 'Painting', 'Dancing', 'Camping'];
    
        //Insert Interests
        foreach ($interests as $interest) {
            $sql = "INSERT INTO interests
                        (name)
                    VALUES
                        ('".$interest."');";
            try {
                $stmt = $DB->prepare($sql);
                $stmt->execute();
            } catch (Exception $ex) {
                echo errorMessage($ex->getMessage());
            }
        }
    }
    
    static function insertDocumentsTable() {
        global $DB;
        //interests
        $interests = ['Sport', 'Fishing', 'Gardening', 'Children', 'Animals', 'Astronomy', 'Cars', 'Theatre', 'Archery', 'Video Games',
        'Travelling', 'Artificial Intelligence', 'Painting', 'Dancing', 'Camping'];
        //Insert Documents
        $linked = 0;

        for ($i=0; $i<100; $i++) {

            $linkText = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

            $index = rand(0, count($interests) - 1);

            if ($interests[$index] !== 'Sport' && $interests[$index] !== 'Fishing' && $linked <= 60) {
                $sql = "SELECT id FROM interests WHERE name = '".$interests[$index]."'";

                try {
                    $stmt = $DB->prepare($sql);
                    $stmt->execute();
                    $interestResult = $stmt->fetch();
                } catch (Exception $ex) {
                    echo errorMessage($ex->getMessage());
                }
                $sql = "INSERT INTO documents
                            (name, interest_id)
                        VALUES
                            ('".$interests[$index]."/".$linkText."', ".$interestResult['id'].");";
                try {
                    $stmt = $DB->prepare($sql);
                    $stmt->execute();
                } catch (Exception $ex) {
                    echo errorMessage($ex->getMessage());
                }
                $linked = $linked + 1;
            } else {
                $sql = "INSERT INTO documents
                            (name)
                        VALUES
                            ('".$interests[$index]."/".$linkText."');";
                try {
                    $stmt = $DB->prepare($sql);
                    $stmt->execute();
                } catch (Exception $ex) {
                    echo errorMessage($ex->getMessage());
                }
            }
        }
    }
    
    
    static function insertPersonalInterestsTable() {
        global $DB;
        //interests
        $interests = ['Sport', 'Fishing', 'Gardening', 'Children', 'Animals', 'Astronomy', 'Cars', 'Theatre', 'Archery', 'Video Games',
        'Travelling', 'Artificial Intelligence', 'Painting', 'Dancing', 'Camping'];
        //names
        
        $names = ["ANNABELLE","MADDISON","ARIANA","AYLA","PEYTON","MADISON","FRANKIE","PIPER","PIPPA","QUINN","ADDISON",
            "INDIANA","PAIGE","SADIE","AURORA","EMILIA","HARLOW","HAYLEY","STELLA","SUMMER","ELEANOR","HOLLY","LARA","LOLA","LILLY","DELILAH","ELENA","ELOISE",
            "ANNA","BROOKLYN","CHARLIE","CLAIRE","ESTHER","MAYA","SOFIA","ADELINE","ALEXIS","AMBER","ELIZA","ALLEGRA","EDEN","ELISE","FREYA","OLIVE",
            "ELIZABETH","HEIDI","JASMINE","LUNA","MAGGIE","MOLLY"];
        
        //Insert Person/Interests Table
        foreach($names as $name) {
            $tempInterests = $interests;
            $index = rand(3, 12);
            for ($i = 0; $i < $index; $i++) {
                $interestIndex = rand(0, (count($interests) - 1 - $i ));

                $sql = "INSERT INTO personinterest
                            (person_id, interest_id)
                        values
                            ((select id from personaldetails where name = '".$name."'),(select id from interests where name = '".$tempInterests[$interestIndex]."'))";
                try {
                    $stmt = $DB->prepare($sql);
                    $stmt->execute();
                } catch (Exception $ex) {
                    echo errorMessage($ex->getMessage());
                }
                unset($tempInterests[$interestIndex]);
                $tempInterests = array_values($tempInterests);
            }
        } 
    }
}

