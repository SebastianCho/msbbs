<?
/*//////////////////////////////////////////////////////////////
//이 아래는 무단링크를 막기 위함이나 삭제해도 상관없습니다.
$result=explode(",",$HTTP_REFERER);
if(!eregi("view.php",$result[0]) and !eregi("gallery.php",$result[0])) go("",$result[0]."올바른 경로로 접근하세요");
//////////////////////////////////////////////////////////////*/

$d=mysql_fetch_array(mysql_query("SELECT w_d,h2,fn,fx,ft,fs FROM ".$table1." WHERE no='".$no."'"));
$d[2]=explode(",",$d[2]);
$d[3]=explode(",",$d[3]);
$d[4]=explode(",",$d[4]);
$d[5]=explode(",",$d[5]);

$d[1]=explode(",",$d[1]);
$d[1][$i]+=1;
$d[1]=implode(",",$d[1]);
mysql_query("UPDATE ".$table1." SET h2='".$d[1]."' WHERE no='".$no."'") or exit;

if($dbconn) {
	mysql_close($dbconn);
	unset($dbconn);
}

$file="./data/".$d[0]."/".$d[2][$i].".".$d[3][$i];

IF(!is_readable($file)) {
	go("javascript:window.close()"," READ FILE ERROR! ".$file);
}

if($act=="non") {
	Header("Content-type: ".$d[4][$i]);
	Header("Content-Disposition: attachment; filename=".$d[2][$i].".".$d[3][$i]);
	Header("Content-Description: PHP4 Generated Data");
	header("Pragma: no-cache");
	header("Expires: 0");

	readfile($file);

} else {
	IF( strstr($HTTP_USER_AGENT,"MSIE 6") OR strstr($HTTP_USER_AGENT,"MSIE 5") ){ 
		header("Content-Type: doesn/matter\r\n"); 
		header("Content-Disposition: filename=".$d[2][$i].".".$d[3][$i]."\r\n\r\n"); 
//		Header("Content-length: ".$d[5][$i]);
//		header("Content-Transfer-Encoding: binary\r\n"); 
//		header("Pragma: no-cache"); 
		header("Expires: 0"); 
	} ELSE {
		Header("Content-type: ".$d[4][$i]); 
		Header("Content-Disposition: attachment; filename=".$d[2][$i].".".$d[3][$i]); 
		Header("Content-Description: PHP4 Generated Data"); 
		Header("Content-length: ".$d[5][$i]);
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
	}

	readfile($file);
}

exit;

?>