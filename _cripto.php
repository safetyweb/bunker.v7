<?php

function short_array(){
	return array(
		"A","B","C","D","E","F","G","H","I","J",
		"K","L","M","N","O","P","Q","R","S","T",
		"U","V","W","X","Y","Z","a","b","c","d",
		"e","f","g","h","i","j","k","l","m","n",
		"o","p","q","r","s","t","u","v","w","x",
		"y","z","1","2","3","4","5","6","7","8",
		"9","@","$","*","!",".","(",")","<",">",
		"[","]","{","}","_","-","+",
	);
}

function short_delimiter(){
	return "0";
}

function short_encode($str){
	$arr = short_array();
	$max = sizeof($arr);
	$ret = "";
	$n = round(preg_replace("/[^0-9]/", "", $str));
	$ret = ($n < $max?$arr[$n]:short_encode(floor($n / $max)) . $arr[($n % $max)]);
	return $ret;
}

function short_decode($str){
	$arr = short_array();
	$max = sizeof($arr);
	$ret = 0;
	$n = $str;

	$c = str_split($n);
	$c = array_reverse($c);
	foreach($c as $k => $ch){
		$ret += ((pow($max,$k)) * array_search($ch,$arr));
	}
	return $ret;
}

function short_url_encode($str){
	$d = short_delimiter();
	$str = preg_replace("/[^0-9]/", "-", $str);
	$ret = "";
	$e = explode("-",$str);
	foreach($e as $k => $n){
		$ret .= ($k > 0?$d:"").short_encode($n);
	}
	return $ret;
}

function short_url_decode($str){
	$d = short_delimiter();
	$e = explode($d,$str);
	foreach($e as $k => $n){
		$ret .= ($k > 0?"-":"").short_decode($n);
	}
	return $ret;
}

echo "<br><br>ENCODE <b>".@$_GET["enc"]."</b> =  ".short_url_encode(@$_GET["enc"]);
echo "<br><br>DECODE <b>".@$_GET["dec"]."</b> =  ".short_url_decode(@$_GET["dec"]);
?>