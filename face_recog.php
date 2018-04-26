<?php 
if (!include('mysql_connect.php')) {
    die('error finding connect file');
}

header('Content-Type: text/plain; charset=utf-8');
require_once('test/includes/aws/aws-autoloader.php');

use Aws\Credentials\CredentialProvider;
use Aws\Rekognition\RekognitionClient;

//Check for Token
if (!isset($_POST['token'])) {
    echo "Not Token Submitted.";
} else {
    $token = $_POST['token'];
    
    $sql = "SELECT user_id
            FROM devices
            WHERE token = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param('s', $token);  // prevents SQL injection
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id);
    $success = $stmt->fetch();
    $stmt->close();
    
    if($success == NULL){
        echo "An Error Occured.";
    } elseif (!$success) {
        echo "Token Not Found.";
    } else {
        //Compare image
        if ($_FILES["source"]["error"] > 0) {
            echo "Error: " . $_FILES["source"]["error"] . "<br />";       
        } else {
            
            $source = $_FILES["source"]["tmp_name"];
            $target = "./users/$id/target/1.jpg"; 
            
            $client = new RekognitionClient([
                'version' => 'latest',
                'region'  => 'us-east-1',
                'credentials' => CredentialProvider::defaultProvider()
            ]);
            
            $result = $client->compareFaces([
                'SimilarityThreshold' => 70,
                'SourceImage' => [
                    'Bytes' => file_get_contents($source),
                ],
                'TargetImage' => [ 
                    'Bytes' => file_get_contents($target),
                ],
            ]);
            
            $match = "False ";
            $info = $result['SourceImageFace']['Confidence'];
            
            if($info < 99.999) {
                echo $match.$info;
            } else {
                foreach($result['FaceMatches'] as $face) {
                    if($face['Similarity'] > 90) {
                        $match = "True ";
                    }
                }
                echo $match.$info;
            }
            
        }
    }
}

?>
