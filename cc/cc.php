<?php 
    $urlPg = $_SERVER['REQUEST_URI'];
    ini_set('max_execution_time', '0');
    if(@$_GET["simulare"]){
        $start=microtime(TRUE);
        for ($i=0; $i < 50; $i++) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://localhost/cc/cc.php?ruleaza=Gaseste");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $t = curl_exec($ch);
            curl_close($ch);
        }
        $end=microtime(TRUE);
        echo ' '.($end-$start); 
        //adaugam rezultatul in fisierul log
        $log='Request = '.$urlPg.'" ; Time = "'.($end-$start).'"'.PHP_EOL;
        file_put_contents('./log.txt',$log,FILE_APPEND); 

        echo '<br><br><a href="home.html">Inapoi</a>';

        exit;
    }elseif(@$_GET["ruleaza"]){
        $start=microtime(TRUE);
        echo '<h1><img src="cat.png" style="width: 50px;"/> Raspuns</h1><br><br>';
        $url = 'https://api.adviceslip.com/advice';
        $url2 = 'https://catfact.ninja/fact';
        
        $ch = curl_init();
        $ch2 = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_URL, $url2);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);

        $output = curl_exec($ch);
        $output2 = curl_exec($ch2);

        curl_close($ch);
        curl_close($ch2);

        $advice = json_decode($output);
        $advice = $advice->slip->advice;
        //$advice = substr($advice,0, 20);
        $advice1 = urlencode(strtolower($advice));

        $fact = json_decode($output2)->fact;
        //$fact = substr($fact,0, 20);
        $fact1 = urlencode(strtolower($fact));
        echo 'Fact: '.$fact.'<br>';

        echo 'Advice: '.$advice.'<br>';

        $url3 = 'https://api.dandelion.eu/datatxt/sim/v1/?text1='.$advice1.'&text2='.$fact1.'&token=108dd20997734fe594df3c3d6218e933';
        $ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL, $url3);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE);
        $output3 = curl_exec($ch3);
        curl_close($ch3);

        $similarity = json_decode($output3)->similarity;

        if($similarity==0){
            $rasp = 'Nu sunt similaritati intre texte';
        }else{
            $rasp = 'Sunt similaritati intre texte';
        }
        echo 'Similarity: '.$similarity.' ('.$rasp.')'.'<br>';

        $end=microtime(TRUE);
        //adaugam in fisierul log
        echo 'Time: '.($end-$start);
        $log='Request = "'.$urlPg.';Response= '.$rasp.'; Time = "'.($end-$start).'"'.PHP_EOL;
        file_put_contents('./log.txt',$log,FILE_APPEND);

        echo '<br><br><a href="home.html">Inapoi</a>';
        exit;
    }elseif(@$_GET["log"]){
        echo '<h1><img src="cat.png" style="width: 50px;"/>LOG</h1><br><br>';
        $log=file_get_contents('./log.txt');
        echo '<br>'.$log;
        echo '<br><br><a href="home.html">Inapoi</a>';
        exit;
    }

?>

    