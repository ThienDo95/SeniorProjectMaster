<?php 
if (!include('mysql_connect.php')) {
    die('error finding connect file');
}

header('Content-Type: text/plain; charset=utf-8');
require_once('test/includes/aws/aws-autoloader.php');

use Aws\Credentials\CredentialProvider;
use Aws\Rekognition\RekognitionClient;

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
        echo "Nothign was Returned.";
    } else {

        if ($_FILES["source"]["error"] > 0) {
            echo "Error: " . $_FILES["source"]["error"] . "<br />";       
        } else {
            
            $source = $_FILES["source"]["tmp_name"];
            
            //$dir = "./users/$id/target";
            //$a = scandir($dir);
            //$num_targets = count($a) - 2;

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
            
            /* For Testing
            $conf = $result['SourceImageFace']['Confidence'];
            $sim = $result['FaceMatches'][0]['Similarity'];
            
            $return = "Confidence: $conf, Similarity: $sim";
            echo $return;
            */
            
            $match = "False ";
            $info = $result['SourceImageFace']['Confidence'];
            
            if($info < 99.99) {
                echo $match.$info;
            } else {
                foreach($result['FaceMatches'] as $face) {
                    if($face['Similarity'] > 94) {
                        $match = "True ";
                    }
                }
                echo $match.$info;
            }
        }
    }
}

?>
