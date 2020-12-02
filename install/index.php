<?php 
    session_start();
    if (isset($_GET['logout'])) {
        unset($_SESSION['api_login']);
        header("location:index.php");
    }
    define('BASEPATH', __DIR__);

    include_once 'function.php';
    include_once 'version.php';
    require  "../application/config/database.php";

    $base_url = base_path('/install');
    $SCRIPT_VERSION = SCRIPT_VERSION;
    $root_url = root_url();

    if(checkIsInstall()){
        header("location:../index.php");die;
    }
    /*$res = api("api/check",array(
        "installed_version" => $SCRIPT_VERSION,
        "sa"                => true,
        "product_id"        => 1,
        "root_url"          => rawurlencode($root_url),
        "base_url"          => rawurlencode($base_url),
    ));

    if(isset($res['success']['code']) && isset($db["default"]["database"])){
        header("location:../index.php");die;
    } */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Installation Proccess</title>

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <link href="assets/css.css" rel="stylesheet">
    <script type="text/javascript" src="assets/js.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,800,900,900i" rel="stylesheet">

</head>
<body>
    <div class="container">
        <h4 class="text-center website-title">Welcome to Affiliate Pro</h4>
        <div id="main">
            <div class="loading text-center">
                <br><br><br><br>
                <button class="btn btn-primary btn-loading">Loading...</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        console.log("<?= isset($_GET['error']) ? $_GET['error'] : '' ?>")
        $.ajax({
            url:'proccess.php',
            type:'POST',
            dataType:'json',
            data:{page:'step1'},
            success:function(json){
                $("#main").html(json['html']);
            },
        })
    </script>
</body>
</html>