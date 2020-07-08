<?php
	
	$lloji = htmlspecialchars($_POST['lloji']);
	$bar = htmlspecialchars($_POST['bar']);
    $imagedata = htmlspecialchars($_POST['image']);

	include 'EasyARCloudSdk.php';
	

	$sdk = new EasyARClientSdkCRS($appKey, $appSecret, $appHost);

	$rs = $sdk->ping();

	$params = [
		'name' => $bar,
		'active' => '1',
		'size' => '1',
		'meta' => base64_encode($bar.'#'.$lloji),
		'image' => $imagedata,
	];





    require('xcom/db.php');

    $idcheckquery = "SELECT barkodi FROM xcom WHERE barkodi ='". $bar ."'";

    $idcheck = mysqli_query($conn,$idcheckquery) or die("2: Barkodi check failed");

    if(mysqli_num_rows($idcheck) != 1)
    {
        echo "5: Barkodi doesnt exists or it exists twice";
        exit();
    }

    $updatequery = "UPDATE xcom SET lloji = '".$lloji."' WHERE barkodi = '".$bar."';";

    mysqli_query($conn,$updatequery) or die("7: Update Query Failed");
    
    


    $imgcheckquery = "SELECT barkodi FROM image WHERE barkodi ='". $bar ."'";

    $imgcheck = mysqli_query($conn,$imgcheckquery) or die("2: Img check failed");

    if(mysqli_num_rows($imgcheck) > 0)
    {
        echo "5: Img already exists";
        exit();
    }

    $query = "INSERT INTO  image(barkodi,img) VALUES('$bar','$imagedata')";

    if(mysqli_query($conn, $query)){
       // echo "0";
    }else{
        echo "ERROR: ". mysqli_error($conn);
    }

    

	$rs = $sdk->targetAdd($params);
	
	if ($rs->statusCode == 0) {
		echo "Produkti u regjistrua me sukses";
        //print_r($rs);
	} else {
		//echo "Ka problem me te dhenat, ose ka produkt te ngjajshem ne databaz";
        print_r($rs->result);
	}

    

?>

