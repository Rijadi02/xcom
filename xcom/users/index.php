<?php

    require('../db.php');
    
    $query = 'SELECT * FROM users';

    $result = mysqli_query($conn, $query);

    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    mysqli_close($conn)

?>
<html>

<head><title>it works, i think</title></head>
<body>
<table border="1">
<tr>
            <td>ID</td>
            <td>emri</td>
            <td>hash</td>
            <td>salt</td>
            <td>numri</td>
            <td>vendi</td>
            <br>
            </tr>
    <?php foreach($items as $item) :?>
        <tr>
            <td><?php echo $item['ID'] ?></td>
            <td><?php echo $item['emri'] ?></td>
            <td><?php echo $item['hash'] ?></td>
            <td><?php echo $item['salt'] ?></td>
            <td><?php echo $item['numri'] ?></td>
            <td><?php echo $item['vendi'] ?></td>
            <br>
            </tr>
        
    <?php endforeach; ?>

    </table>
</body>
</html>