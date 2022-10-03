<?php
include "../class/SearchQuery.php";
include "../class/TplBlock.php";
include "../class/SearchTail.php";

session_start();

//bdd
$params = json_decode( file_get_contents("../config/config.json"),true);
try {
    $db= new PDO($params["db"]["dsn"], $params["db"]["user"], $params["db"]["pass"]);
} catch (PDOException $e) {
    echo "internal fail";
    die();
}

switch($_SERVER["REQUEST_URI"])
{
    case "/":


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');

            $data= json_decode ( file_get_contents('php://input'), true);

            if(!isset($data["keyword"])){
                http_response_code(400);
                echo json_encode(
                    array(
                        "error"         => true, 
                        "message"       => "Malformed request" 
                        )
                );
                die();
            }
            $inputKeywords = $data["keyword"];
            
            //limit user submissions
            $last_submit = isset( $_SESSION["last_submit"] )? $_SESSION["last_submit"] : 0;            
            if( time() < $last_submit +  $params['randompic']['delayPerUser'] )
            {
                echo json_encode(
                    array(
                        "error"         => true, 
                        "message"       => "Please wait "
                                            . $params['randompic']['delayPerUser'] 
                                            . " secondes beetween two word's submission." 
                        )
                    );
                    die();
            }
            $tail = new SearchTail($db);
            $tail->addQueryOnTail( $inputKeywords );
            $_SESSION["last_submit"] = time();
            echo json_encode(
                array(
                    "error"         => false, 
                    "message"       => "Thanks for submitting the amazing words " 
                                        .  $inputKeywords 
                    )
                );
                die();
        }

        $mainTpl = new TplBlock();
        echo $mainTpl->applyTplFile("../templates/main.html");
        break;

    case "/img":

        $imgTpl = new TplBlock();
        echo $imgTpl->applyTplFile("../templates/img.html");

        break;

    case "/getImg" :
        $tail = new SearchTail($db);
        
        break;
    case "/currentImgInfos":
        echo json_encode(
            array(
                "error"         => false, 
                "identifier"       => "hey" ,
                "key words"     => "key words"
              
                )
            );
        break;
    case "/config":
        break;
}
