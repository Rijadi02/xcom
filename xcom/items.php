<?php

    require('db.php');
    
    $query = 'SELECT * FROM xcom';

    $result = mysqli_query($conn, $query);

    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    mysqli_close($conn)

?>
<html>

<head>
<title>Items</title>
</head>

<body>
    <a href="index.php">Add a product</a>

    <table border="1">
        <tr>
            <td>barkodi</td>
            <td>emri</td>
            <td>lloji</td>
            <td>vendi</td>
            <td>data</td>
            <td>qmimi</td>
            <br>
        </tr>
    <?php foreach($items as $item) :?>
        <tr>
            <td><?php echo $item['barkodi'] ?></td>
            <td><?php echo $item['emri'] ?></td>
            <td><?php echo $item['lloji'] ?></td>
            <td><?php echo $item['vendi'] ?></td>
            <td><?php echo $item['data'] ?></td>
            <td><?php echo $item['qmimi'] ?></td>
            <br>
        </tr>
    <?php endforeach; ?>
    </table>
</body>
</html>