@include('functions')
<?php
$pdo = connectDB();
$err_msg = '';
//post request process
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //store images to db
    if (!empty($_FILES['image']['name'][0])) {
        $files = $_FILES['image'];

        for ($i =0; $i  < count($files['name']); $i++) { 
            $name = $files['name'][$i];
            $type = $files['type'][$i];
            $content = file_get_contents($files['tmp_name'][$i]);
            $size = $files['size'][$i];
            
            //check file type and size(2M)
            $maxFileSize = 1048576*2;
            $validFileTypes = ['image/png', 'image/jpeg'];
            if ($size > $maxFileSize || !in_array($type, $validFileTypes)) {
                $err_msg = 'please select * jpg, jpeg, png file up to 2MB';
            }

            //insert image into db
            if ($err_msg == '') {
                //check error
                try {
                    $sql = 'INSERT INTO images(image_name, image_type, image_content, image_size, created_at)
                            VALUES (:image_name, :image_type, :image_content, :image_size, now())';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
                    $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
                    $stmt->bindValue(':image_content', $content, PDO::PARAM_STR);
                    $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
                    $stmt->execute();
                } catch(Exception $error){
                    echo "failed to upload file" . $error->getMessage();
                    exit();
                }
            }
        }

        header('Location:/home');
        exit();
    } 
}
?>