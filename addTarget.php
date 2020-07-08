<?php
//error_reporting(0);
$msg = '';
$msgClass = '';
if (filter_has_var(INPUT_POST, 'submit')) {

    $lloji = htmlspecialchars($_POST['lloji']);

    $bar = $_POST['bar'];


    include 'EasyARCloudSdk.php';


    $sdk = new EasyARClientSdkCRS($appKey, $appSecret, $appHost);

    $rs = $sdk->ping();

    $params = [
        'name' => $bar,
        'active' => '1',
        'size' => '1',
        'meta' => base64_encode($bar . '#' . $lloji),
        'image' => base64_encode(file_get_contents($_FILES['image']['tmp_name'])),
    ];




    require('xcom/db.php');

    $idcheckquery = "SELECT barkodi FROM xcom WHERE barkodi ='" . $bar . "'";

    $idcheck = mysqli_query($conn, $idcheckquery) or err("Ka problem me kyqjen ne databasë!");

    if (mysqli_num_rows($idcheck) != 1) {
        err("nuk egziston ne databsen me produkte!",0,$bar);
        exit();
    }

    $updatequery = "UPDATE xcom SET lloji = '" . $lloji . "' WHERE barkodi = '" . $bar . "';";

    mysqli_query($conn, $updatequery) or err("Ka problem me kyqjen ne databasë!");

    $imgcheckquery = "SELECT barkodi FROM image WHERE barkodi ='" . $bar . "'";

    $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem me kyqjen ne databasë!");

    if (mysqli_num_rows($imgcheck) > 0) {
        err("Foto tashmë egziston!");
    } else {
        $rs = $sdk->targetAdd($params);

        if ($rs->statusCode == 0) {
    
            $query = "INSERT INTO image(barkodi,img) VALUES('$bar','" . base64_encode(file_get_contents($_FILES['image']['tmp_name'])) . "')";
    
            if (mysqli_query($conn, $query)) {
            } else {
                err("ka problem me regjistrimin, provo përseri!",2,$bar);
            }
            err("Fotoja u regjistrua me sukses",1);
        } else {
            err("Foto është shumë e madhe, ose ka foto të ngjajshëm në databas!");
        }

    }

   
}
