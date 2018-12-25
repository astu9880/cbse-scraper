<?php
$url='http://resultsarchives.nic.in/cbseresults/cbseresults2015/class12/cbse122015_all.asp';
$filename = '';
$subjects = array('ROLL NO','NAME',"MOTHER'S NAME","FATHER'S NAME",'ENGLISH CORE','MATHEMATICS','PHYSICS','CHEMISTRY','BIOLOGY','ECONOMICS','BUSINESS STUDIES','ACCOUNTANCY','COMPUTER SCIENCE','ENGLISH ELECTIVE-C','BIOTECHNOLOGY','POLITICAL SCIENCE','PSYCHOLOGY','SOCIOLOGY','INFORMATICS PRAC.','ENTREPRENEURSHIP,ENGG. GRAPHICS','MARKETING','MULTIMEDIA & WEB T','HINDI CORE','HISTORY','GEOGRAPHY','PHYSICAL EDUCATION','TAMIL','PAINTING','SANSKRIT CORE');
$start = $_GET['start'];
$end = $_GET['end'];
$filename = $start.'-'.$end.'.csv';
$file = fopen($filename, "a") or die("Unable to open file!");
for ($i=0;$i<sizeof($subjects); $i++) {
    if ($i<sizeof($subjects)-1){fwrite($file,$subjects[$i].',');}
    else {fwrite($file,$subjects[$i].PHP_EOL);
}

$increment = 1;
$srequired = array(7,13,19,25,31);
$mrequired =  array(10,16,22,28,34);

function get_data($url, $i) {
    global $file,$fdata,$subjects,$mrequired,$srequired;
    $fdata = array();
    $ch = curl_init($url);
    $rollno="regno=".$i;	         	//Define the roll number variable
    curl_setopt($ch, CURLOPT_POST, 1);         	//Enable cURL posting
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rollno);         	//Define the data to be posted
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_REFERER, 'http://resultsarchives.nic.in/cbseresults/cbseresults2015/class12/cbse122015_all.htm');
    $result = curl_exec($ch);         	//Execute cURL request
    curl_close($ch);
    if (strpos($result,'Invalid Roll No.') !== false or strpos($result,'Result Not Found') !== false) {}
    else {
        $mn = explode('<font face="Arial" size=2>', $result);
        if (sizeof($mn)>30){
            $fdata = array();
            array_push($fdata,$i);
            $v1 = explode('face="Arial" size=2> <b>', $result);
            $v2 = explode ('</b>',$v1[1]);
            $name = $v2[0];
            array_push($fdata,$v2[0]);

            $temp = explode('</font>', $mn[4]);

            array_push($fdata,$temp[0]);
            $temp = explode('</font>', $mn[5]);
            array_push($fdata,$temp[0]);

            for($i=0;$i<5;$i++){
                $temp = explode('</font>',$mn[$srequired[$i]]);
                $tem = explode('&nbsp;',$mn[$mrequired[$i]]);

                if( in_array($temp[0],$subjects)){
                $fdata[array_search($temp[0], $subjects)] =  $tem[0];
                }
            }

          for($i=0;$i<sizeof($subjects)+1;$i++){
               if(!array_key_exists($i, $fdata))
                  {
                      $fdata[$i]='-';
                  }
            }

        }
    }
    for ($i=0;$i<sizeof($fdata); $i++) {
        if ($i<sizeof($fdata)-1){fwrite($file,$fdata[$i].',');}
        else {fwrite($file,$fdata[$i].PHP_EOL);}
    }

}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
    <title>CBSE Results Analyser</title>

    <style type="text/css">
        .container{
             text-align: center;
            margin-top: 150px;
            width:300px;
            background-color: #D7DBE7;
            padding: 30px 30px 20px 30px;
            border-radius: 10px;
        }
    </style>
  </head>


  <body>
    <div class="container">
    <form>
      <fieldset class="form-group">
        <input type="text" class="form-control" id="Start" placeholder="Enter starting roll number." name="start">
      </fieldset>

      <fieldset class="form-group">
        <input type="text" class="form-control" id="End" placeholder="Enter ending roll number." name="end">
      </fieldset>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php
         if ($end>$start){
        if ($end-$start<501){
            for ($i=$start;$i<$end+1;$i=$i+$increment){
            get_data($url,$i);
            }
            echo '<a href="'.$filename.'">Download file '.$filename.'</a>';
        }
        else {
            echo "<script type='text/javascript'>alert('The range should be less than 500');</script>";
        }
    }
    else
    {
         if ($start-$end<501){
            for ($i=$end;$i<$start+1;$i=$i+$increment){
            get_data($url,$i);
            echo '<a href="'.$filename.'">Download file</a>';
            }
        }
        else {
            echo "<script type='text/javascript'>alert('The range should be less than 500');</script>";
        }
    }
    ?>

    </div>

    <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
  </body>
</html>
