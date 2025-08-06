<?php
    session_start();
    require_once 'config.php';
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('location: login.php');
        exit;
    }
?>




<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="ustyle.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <title>Unheard Genius A Forgotten Knowledge Hub</title>
</head>
<body>
    
    
    <div class="title">
        <h1>Untold Theories</h1>
        <center><hr width="150" color="#ffc900"></center>
    </div>
    <div class="big-cointainer">
        <div class="big-cont1">
            <div class="content1">
                <img src="images/pheno.png" alt="Unexplained Scientific Phenomena">
                <center><a href="html/unbranch1.html"><input type="submit" value="Explore" id="btn"></a></center>
            </div>
            <div class="content2">
                <img src="images/space.png" alt="Unexplained Scientific Phenomena">
                <center><a href="html/unbranch2.html"><input type="submit" value="Explore" id="btn"></a></center>
            </div>
            <div class="content2">
                <img src="images/forbiden.png" alt="Unexplained Scientific Phenomena">
                <center><a href="html/unbranch4.html"><input type="submit" value="Explore" id="btn"></a></center>
            </div>
        </div>
        
        <div class="big-cont2">
            <div class="content2" >
                <img src="images/time.png" alt="Unexplained Scientific Phenomena">
                <center><a href="html/unbranch3.html"><input type="submit" value="Explore" id="btn"></a></center>
            </div>
            <div class="content2">
                <img src="images/para.png" alt="Unexplained Scientific Phenomena">
                <center><a href="html/unbranch5.html"><input type="submit" value="Explore" id="btn"></a></center>
            </div>
        </div>
        <div class="link">
            <a href="dashboard.php"><input type="submit" value="Dashboard" class="btn1"></a>
        </div> 
    </div>
</body>
</html>
