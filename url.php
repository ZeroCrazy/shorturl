<?php
    require 'core.php';
    if($_GET['url']){
        $url = htmlspecialchars($_GET['url']);
        $sql_url = "SELECT * FROM urls WHERE url='$url' LIMIT 1";$result_url = $conn->query($sql_url);
        if ($result_url->num_rows > 0) {
        foreach ($result_url as $u) {
            header("Location: ". $u['cut_url'] ."");
            mysqli_query($conn, "UPDATE urls SET views=views+1 WHERE id=". $u['id'] ."");
        }
        } else {
            header("Location: $site");
        }
    } else {
        header("Location: $site");
    }
?>
