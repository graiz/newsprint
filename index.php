<?php
// Don't cache this page. 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

date_default_timezone_set('Etc/GMT+4');  // adjust for server timezone

// path to paper is base url  +day of month + state + paper + .pdf
// https://cdn.freedomforum.org/dfp/pdf16/MA_BG.pdf

$news = array();
$paper['prefix']="WSJ";$paper['style']="width:98%;margin:-70px 0px 0px -15px";array_push($news,$paper);		// Wall St Journal
$paper['prefix']="MA_BG";$paper['style']="width:98%;margin:5px 10px 0px 8px";array_push($news,$paper);		// Boston Globe
$paper['prefix']="NY_NYT";$paper['style']="width:99%;margin:-88px 14px 0px 3px";array_push($news,$paper);	// New York Times
$paper['prefix']="CA_LAT";$paper['style']="width:94%;margin:-2% 0px 0px 0px";array_push($news,$paper);		// L.A. Times
$paper['prefix']="CAN_TS";$paper['style']="width:90%;margin:-70px 0px 0px 0px";array_push($news,$paper);	// Toronto Star 
$paper['prefix']="CA_SFC";$paper['style']="width:96%;margin:-20px 0px 0px 0px";array_push($news,$paper);  	// San Fran Chronical

$maxPapers = count($news) -1;

// Loop a counter without a DB.
// allows us to get a different newspaper 
// each load by asking for the counter 
function getCounter() {
	global $maxPapers;
	$fp = fopen("counter.txt", "r");
	if ($fp) {
	   $x= intval(fread($fp,1024));
	   fclose($fp);
	} else {
	   $x = 0;
	}
	if ($x > $maxPapers) { 
		$x = 0;
	}
	if (!empty($_REQUEST)) { // Override the counter if there 
	  $x = $_REQUEST['index'];	  // is a URL parameter for index
	}
	return($x);
}
function incrementCounter(int $counter){
	// increment the paper for next time
	$counter++;
	$fp = fopen("counter.txt", "w"); 
	fwrite($fp, $counter); 
	fclose($fp); 
}

// fetch a paper and cache in as a JPG. Return the path to the JPG if we found it. 
// We can pass in an offset in days to get yesterday or two days ago 
function fetchPaper($prefix, $offset=0){
	$pathToPdf = "https://cdn.freedomforum.org/dfp/pdf" . date('d',strtotime("-" . $offset . " days")) . "/" . $prefix . ".pdf"; 
	$pdffile = "archive/" . $prefix . "_" . date('Ymd',strtotime("-" . $offset . " days")) . ".pdf"; 
	$jpgfile = "archive/" . $prefix . "_" . date('Ymd',strtotime("-" . $offset . " days")) . ".jpg";
	$rootpath = getcwd() . "/";  
	// check if a jpg has already been created
	// if not we start checking for the PDF and converting
	if (!file_exists($jpgfile)){
		$file_headers = @get_headers($pathToPdf);
		if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$exists = false;
		}
		else {
			$ch = curl_init($pathToPdf);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
			curl_close($ch);
			$exists = true;
			$result = file_put_contents($pdffile, $data);	
		}
		if ($exists) { 	// convert a high-dpi image, force a white background and
		       		// resize to 1600px wide at 95% jpg quality
			$command = 'convert -density 300 -background white -alpha remove ' . $rootpath . $pdffile . 
				   ' -colorspace RGB -resize 1600 -quality 95 ' . $rootpath . $jpgfile;
			exec($command, $output, $response);	
		}
	} else {
		 $exists = true;
	}
	if ($exists) {
		return $jpgfile;
	} else {
		return false;
	}
}

$currentIndex = getCounter();
 

$imageresult = fetchPaper($news[$currentIndex]['prefix'],0);  // Fetch today
if (empty($imageresult)) {
	$imageresult =  fetchPaper($news[$currentIndex]['prefix'],1); // yesterday 
}
if (empty($imageresult)) {
	$imageresult =  fetchPaper($news[$currentIndex]['prefix'],2); // twesterday
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
  body   { text-align:center; }
  .paper {
	background-color:white;
 	<?=$news[$currentIndex]['style']?>
  }
</style>
</head>
<body>
<?php if (empty($imageresult)) {
   echo "Newspaper File Not Found. " . $imageresult. " Will keep looking. Checking again in another hour."; 
} else {
   echo "<img src='" . $imageresult . "' class='paper' >";
 } 
?>
</div>
</body>
</html>
<?php
incrementCounter($currentIndex);
?>

