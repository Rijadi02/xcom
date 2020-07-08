<?php
            require('../../db.php');
            if(isset($_POST['submit'])){

                $lloji = $_POST['lloji'];
                $kategoria = $_POST['kategoria'];
                

                $query = "INSERT INTO  lloji(lloji,kategoria) VALUES('$lloji','$kategoria')";
                
                mysqli_query($conn, $query);
                    
            }
        ?>