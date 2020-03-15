<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test";

    $url = $_SERVER['REQUEST_URI'];
    $urlSeg = explode('/',$url);

    $urlGet = $urlSeg[count($urlSeg)-1];
    $urlGet = explode('?',$urlGet);
    $urlGet = $urlGet[0];


    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        exit;
    }

    ini_set('max_execution_time', '0');

    function getMelodies($id = -1, $titlu = '', $artist = '', $album = ''){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "test";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            exit;
        }
        $sql = "select * from `melodii` ";
        $ok = 0;
        if($id!= -1){
            $sql.="where `id`='".$id."'";
            $ok=1;
        }
        if($titlu != ''){
            if($ok == 1){
                $sql.=" and `titlu`='".$titlu."'";
            }else{
                $sql.="where `titlu`='".$titlu."'";
                $ok=1;
            }
        }
        if($artist != ''){
            if($ok == 1){
                $sql.=" and `artist`='".$artist."'";
            }else{
                $sql.="where `artist`='".$artist."'";
                $ok=1;
            }
        }
        if($album != ''){
            if($ok == 1){
                $sql.=" and `album`='".$album."'";
            }else{
                $sql.="where `album`='".$album."'";
                $ok=1;
            }
        }
        return mysqli_query($conn,$sql);
    }

    function insertMelody($titlu, $artist, $album, $versuri){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "test";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            exit;
        }
        if($titlu!='' && $artist!='' && $album!='' && $versuri!=''){
            $sql = "insert into `melodii` (`titlu`, `artist`, `album`, `versuri`) values ('".$titlu."', '".$artist."', '".$album."', '".$versuri."')";
            mysqli_query($conn,$sql);
            return mysqli_insert_id($conn);
        }else{
            return 0;
        }
    }

    function updateMelody($id, $titlu = '', $artist = '', $album = '', $versuri = ''){
        if($titlu!='' || $artist!='' || $album!='' || $versuri!=''){
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test";
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
                exit;
            }
            $sql = "update `melodii` set ";
            $ok=0;
            if($titlu != ''){
                $sql.="`titlu` = '".$titlu."'";
                $ok = 1;
            }
            if($artist != ''){
                if($ok == 0){
                    $sql.="`artist` = '".$artist."'";
                    $ok = 1;
                }else{
                    $sql.=", `artist` = '".$artist."'";
                }
            }
            if($album != ''){
                if($ok == 0){
                    $sql.="`album` = '".$album."'";
                    $ok = 1;
                }else{
                    $sql.=", `album` = '".$album."'";
                }
            }
            if($versuri != ''){
                if($ok == 0){
                    $sql.="`versuri` = '".$versuri."'";
                    $ok = 1;
                }else{
                    $sql.=", `versuri` = '".$versuri."'";
                }
            }
            $sql.=" where `id` = '".$id."'";
            mysqli_query($conn,$sql);
            return $id;
        }else{
            return 0;
        }
    }
    function deleteMelody($id){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "test";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            exit;
        }
        $sql = "delete from `melodii` where `id`='".$id."'";
        mysqli_query($conn,$sql);
        return $id;
    }

    if($urlGet == 'getMelodies'){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }else{
            $id = -1;
        }
        if(isset($_GET['titlu'])){
            $titlu = $_GET['titlu'];
        }else{
            $titlu= '';
        }
        if(isset($_GET['artist'])){
            $artist = $_GET['artist'];
        }else{
            $artist = '';
        }
        if(isset($_GET['album'])){
            $album = $_GET['album'];
        }else{
            $album = '';
        }
        $response = getMelodies($id, $titlu, $artist, $album);
        if(mysqli_num_rows($response) > 0){
            $res = mysqli_fetch_all($response);
            $res = json_encode($res);
            header("HTTP/1.0 200 OK");
            header('Content-Type: application/json');
            echo $res; exit;
        }else{
            $res='Nu exista nici o melodie.';
            $res = json_encode($res);
            header("HTTP/1.0 404 No Found");
            header('Content-Type: application/json');
            echo $res; exit;
        }
    }elseif($urlGet == 'insertMelody'){
        if(isset($_GET['titlu'])){
            $titlu = $_GET['titlu'];
        }else{
            $titlu= '';
        }
        if(isset($_GET['artist'])){
            $artist = $_GET['artist'];
        }else{
            $artist = '';
        }
        if(isset($_GET['album'])){
            $album = $_GET['album'];
        }else{
            $album = '';
        }
        if(isset($_GET['versuri'])){
            $versuri = $_GET['versuri'];
        }else{
            $versuri = '';
        }
        $response = getMelodies(-1, $titlu, $artist, $album);
        if(mysqli_num_rows($response) > 0){
            $res = 'Melodia exista deja';
            $res = json_encode($res);
            header("HTTP/1.0 409 Conflict");
            header('Content-Type: application/json');
            echo $res; exit;
        }else{
            $resp = insertMelody($titlu, $artist, $album, $versuri);
            if($resp == 0){
                $res='Eroare la adaugare melodie';
                $res = json_encode($res);
                header("HTTP/1.0 404 No Found");
                header('Content-Type: application/json');
                echo $res; exit;
            }else{
                $res='Melodie adaugata cu succes :)';
                $res = json_encode($res);
                header("HTTP/1.0 201 Created");
                header("Location: http://localhost/cc2/cc.php/getMelodies?id=".$resp);
                echo $res; exit;
            }
        }
    }elseif($urlGet == 'updateMelody'){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }else{
            $id = -1;
        }
        if(isset($_GET['titlu'])){
            $titlu = $_GET['titlu'];
        }else{
            $titlu= '';
        }
        if(isset($_GET['artist'])){
            $artist = $_GET['artist'];
        }else{
            $artist = '';
        }
        if(isset($_GET['album'])){
            $album = $_GET['album'];
        }else{
            $album = '';
        }
        if(isset($_GET['versuri'])){
            $versuri = $_GET['versuri'];
        }else{
            $versuri = '';
        }
        if($id!=-1){
            $response = getMelodies($id);
            if(mysqli_num_rows($response) > 0){
                $resp = updateMelody($id, $titlu, $artist, $album, $versuri);
                $res = 'Melodie modificata cu succes';
                $res = json_encode($res);
                header("HTTP/1.0 200 OK");
                header('Content-Type: application/json');
                echo $res; exit;
            }else{
                $res = 'Melodia nu exista';
                $res = json_encode($res);
                header("HTTP/1.0 404 Not Found");
                header('Content-Type: application/json');
                echo $res; exit;
            }
        }else{
            $res = 'Trebuie specificat id-ul melodiei';
            $res = json_encode($res);
            header("HTTP/1.0 405 Method Not Allowed");
            header('Content-Type: application/json');
            echo $res; exit;
        }

    }elseif($urlGet == 'deleteMelody'){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }else{
            $id = -1;
        }
        if($id!=-1){
            $response = getMelodies($id);
            if(mysqli_num_rows($response) > 0){
                $resp = deleteMelody($id);
                $res = 'Melodia a fost stearsa';
                $res = json_encode($res);
                header("HTTP/1.0 200 OK");
                header('Content-Type: application/json');
                echo $res; exit;
            }else{
                $res = 'Melodia nu exista';
                $res = json_encode($res);
                header("HTTP/1.0 404 Not Found");
                header('Content-Type: application/json');
                echo $res; exit;
            }
        }else{
            $res = 'Trebuie specificat id-ul melodiei';
            $res = json_encode($res);
            header("HTTP/1.0 405 Method Not Allowed");
            header('Content-Type: application/json');
            echo $res; exit;
        }
    }
?>