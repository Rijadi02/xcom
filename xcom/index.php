<html>
    
    <body>
        <?php
            require('db.php');
            if(isset($_POST['submit'])){

                $barkodi = $_POST['barkodi'];
                $emri = $_POST['emri'];
                $lloji = $_POST['lloji'];
                $vendi = $_POST['vendi'];
                $data = $_POST['data'];
                $qmimi = $_POST['qmimi'];

                $query = "INSERT INTO  xcom(barkodi,emri,lloji,vendi,data,qmimi) VALUES('$barkodi','$emri','$lloji','$vendi','$data','$qmimi')";
                
                if(mysqli_query($conn, $query)){
                    echo "Product Created";
                }else{
                    echo "ERROR: ". mysqli_error($conn);
                }
            }
        ?>

        <a href="items.php">Products</a>
        <a href="getotheritems.php">OtherOnes</a>

        <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">

        <label>kodi</label>
        <input name="barkodi">
        <br>

        <label>emri</label>
        <input name="emri" type="text">
        <br>

        <label>lloji</label>
        <input name="lloji" type="text">
        <br>

        <label>vendi</label>
        <input name="vendi" type="text">
        <br>

        <label>data</label>
        <input name="data" type="text">
        <br>
        
        <label>qmimi</label>
        <input name="qmimi" type="text">
        <input type="submit" name="submit" value="submit">

    </body>


</html>