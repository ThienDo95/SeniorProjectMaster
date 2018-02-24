<?php 
    if(!isset($_POST['target'])) {     
        echo "nope";        
    } else {
        header('Content-Type: text/plain; charset=utf-8');
        
        $target = $_POST['target'];
        $source = "source.jpg"; //this will be some image from the server.
        
        require_once('includes/aws/aws-autoloader.php');
        
        use Aws\Credentials\CredentialProvider;
        use Aws\Rekognition\RekognitionClient;
    
        $client = new RekognitionClient([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => CredentialProvider::defaultProvider()
        ]);
        
        $result = $client->compareFaces([
            'SimilarityThreshold' => 70,
            'SourceImage' => [
                'Bytes' => file_get_contents($target),
            ],
            'TargetImage' => [ 
                'Bytes' => file_get_contents($source),
            ],
        ]);
        
        $match = "False";

        foreach($result["FaceMatches"] as $face) {
            if($face['Similarity'] > 85) {
                $match = "True";
            }
        }
        
        echo $match;
    }

?>