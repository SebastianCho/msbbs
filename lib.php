<?

FUNCTION DBCONN() {

/*****************************************************************
이 아래가 수정하실 부분입니다
*****************************************************************/
	
	$hostname="localhost";  // 호스트 이름
	DEFINE(db_name,"***"); // DB 이름
	$db_id = "***";  // DB 사용자 이름
	$db_pw = "***";  // DB 사용자 비밀번호

/*****************************************************************
이 아래의 내용은 수정하지 마세요
*****************************************************************/

	$dbconn=@MYSQL_CONNECT($hostname,$db_id,$db_pw) or DB_ERR(__FILE__."-".__LINE__);
	@MYSQL_SELECT_DB(db_name,$dbconn) or DB_ERR(__FILE__."-".__LINE__);

	RETURN $dbconn;
}

FUNCTION DB_ERR($str1,$str2=false) {
	GLOBAL $dbconn;

	$str1=explode("-",$str1);
	
	echo("<META HTTP-EQUIV='CONTENT-TYPE' CONTENT='TEXT-HTML;CHARSET=euc-kr' />
		<pre>

		MySQL Database Error!
		데이터베이스 오류가 났습니다.
		아이디와 비밀번호가 틀리다면 lib.php 에서 수정하세요.

		# FILE : ".$str1[0]."
		# LINE : ".$str1[1]."
		# ERROR NO : ".MYSQL_ERRNO()."
		# ERROR MSG : ".MYSQL_ERROR()."
		
		");

	if($str2) echo($str2);

	echo ("
		</pre>");

	@MYSQL_QUERY("UNLOCK TABLES");

	if($dbconn) {
		mysql_close($dbconn);
	}

	exit;
}

FUNCTION member() {
	Global $HTTP_COOKIE_VARS,$table3;

	$str1=explode("-",$HTTP_COOKIE_VARS[msbbs]);
	$str1=MYSQL_QUERY("SELECT * FROM ".$table3." WHERE no='".$str1[0]."'") or go("install.php","설치하세요");
	$str2=MYSQL_FETCH_ROW($str1);

	if(strcmp($str1[1],$str2[3])) {
		$str2[5]=stripslashes($str2[5]);
		$str2[6]=stripslashes($str2[6]);
		$str2[7]=stripslashes($str2[7]);
		$str2[12]=stripslashes($str2[12]);
		RETURN $str2;
	} else {
		$str2[0]=-1;
		$str2[4]=9;
		RETURN $str2;
	}
}

FUNCTION board($str1=false) {
	Global $table2,$id;

	if(!$id AND !$str1) go("","게시판 이름을 지정해 주세요");
	elseif(!$id AND $str1) $id=$str1;

	$result=MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."'") or DB_ERR(__FILE__."-".__LINE__);
	if(MYSQL_AFFECTED_ROWS()<1) go(""," 올바른 게시판을 지정해 주세요 ");
	$result=MYSQL_FETCH_ARRAY($result);

	$result[49]="./skin/".stripslashes($result[1]); //스킨디렉토리
	if($result[2]=="0") $result[2]="";// 게시판 관리자

	$result[3]=explode(",",stripslashes($result[3]));// 카테고리

	$result[33]=stripslashes($result[33]);
	$result[34]=stripslashes($result[34]);
	$result[35]=stripslashes($result[35]);
	$result[36]=stripslashes($result[36]);
	$result[37]=stripslashes($result[37]);
	$result[42]=stripslashes($result[42]);
	$result[43]=stripslashes($result[43]);
	$result[47]=stripslashes($result[47]);
	$result[48]=stripslashes($result[48]);

	RETURN $result;
}

FUNCTION start() {
	Global $set,$ver;

	echo ("<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>

<HTML>
<HEAD>
	<TITLE>".$set[33]."</TITLE>
	<META HTTP-EQUIV='CONTENT-TYPE' CONTENT='TEXT-HTML;CHARSET=euc-kr' />
	<LINK REL='STYLESHEET' HREF='$set[49]/style.css' />".stripslashes($set[34])."
</HEAD>

<BODY ".stripslashes($set[31])." />
");

	IF($set[35] AND !eregi("http://",$set[31]) ) { @include stripslashes($set[35]); }
	echo stripslashes($set[36])."\n";
}

FUNCTION go($str1,$str2=false) {
	GLOBAL $dbconn;

	if($str2) {
?>
<script>alert('<?=$str2?>');</script>
<?
	}
	if($str1=="") { $str1="javascript:history.back()"; }

	if($dbconn) {
		MYSQL_CLOSE($dbconn);
		unset($dbconn);
	}

?>
<META HTTP-EQUIV='REFRESH' CONTENT='0;URL=<?=$str1?>'>
<?
	exit;
}

FUNCTION login($str1,$str2) {
	Global $autolog,$table3,$set;

	if(!$str1) go("","아이디를 입력하세요");
	if(!$str2 || $str2=='') go("","비밀번호를 올바로 입력하세요");

	$str3=MYSQL_QUERY("SELECT no,pw,lv FROM $table3 WHERE id='".$str1."'");
	$result=MYSQL_AFFECTED_ROWS();
	if($result<1) go("","입력하신 아이디가 존재하지 않습니다");

	$str3=MYSQL_FETCH_ROW($str3);
	
	if(($set[26]>1 and $set[26]<$str3[2]) OR $str3[2]>8) go("","로그인을 하실 수 없습니다");

	$str4=MYSQL_FETCH_ROW(MYSQL_QUERY("Select password('".$str2."')"));

	if($str4[0]==$str3[1]) {
		if($autolog=="on") {
			Setcookie("msbbs",$str3[0]."-".$str4,time()+31536000,"/");
		}
		else {
			Setcookie("msbbs",$str3[0]."-".$str4,"","/");
		}

		RETURN $str3;
	} else {
		@Setcookie("msbbs","","0","/");
		go("","비밀번호가 맞지 않습니다");
		RETURN false;
	}
}

FUNCTION file_size($str1) {
	if($str1>=1024*1024) {
		$str1=substr($str1/1024/1024,0,4)."MB";
	} elseif($str1>1024) {
		$str1=substr($str1/1024,0,4)."KB";
	} else {
		$str1=substr($str1,0,4)."BYTES";
	}
	return $str1;
}

FUNCTION logout() {
	GLOBAL $HTTP_COOKIE_VARS;
	Setcookie("msbbs","","0","/");
}

FUNCTION foot() {
	Global $set;
@include $set[41];?>

<?=$set[42]?>

</BODY>

</HTML>

	<?
}

Function cut_size($val,$cut_len) {
	$tot_len = strlen($val);
	$cut_str = substr($val,0,$cut_len);
	$len = strlen($cut_str);

	for($i=0;$i < $len;$i++){
		if(ord($val[$i]) > 127){ $hanlen++; }
		else{ $englen++; }
	}

	$cut_gap=$hanlen%2;

	if($cut_gap == 1){ $hanlen--; }
	$length=$hanlen + $englen;

	if($tot_len > $length) {
		return substr($val,0,$length)."..";
	} else {
		return substr($val,0,$length);
	}
}

FUNCTION erg($str1) {
	$str1=eregi_replace("<","&lt;",$str1);
	$str1=eregi_replace(">","&gt;",$str1);
	$str1=eregi_replace("\ ","&nbsp;",$str1);
	RETURN $str1;
}

$ver='1.0.0';
$table1="msb_".$id; // MAIN BOARD TABLE
$table2="msbadmin"; // BOARD`s ATTRIBUTES TABLE
$table3="msbmem"; // MEMBER TABLE
$table4="msbc_".$id; // COMMENT SAVER TABLE
$table5="msbi_".$id; // INDEX TABLE
$dbconn=DBCONN();

?>