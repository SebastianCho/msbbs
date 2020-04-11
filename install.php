<?
/********************************************************************************

> MSBBS (Un)Installation <

Last Modified 2001.11.01

********************************************************************************/

REQUIRE_ONCE("lib.php");

IF( $p!='uninstall' && $p!=100 && @MYSQL_RESULT(@MYSQL_QUERY('SELECT count(*) FROM msbmem'),0,0)>0 ) {
	go('./install.php?p=uninstall','');
}

Function get_betable($str1) {
	$result=mysql_list_tables(db_name) or DB_ERR(__FILE__."-".__LINE__);

	$count=mysql_num_rows($result) or DB_ERR(__FILE__."-".__LINE__);

	for($i=0;$i<$count;$i++) {
		if($str1==mysql_tablename($result,$i,0)) return 1;
	}

	return 0;
}

if($p==100) {
	$member=member();
	if($member[4]!=1) go("admin.php","최고관리자 영역입니다");
	if(!get_betable($table2)) { go("","게시판 설정테이블(".$table2.")을 찾을 수 없습니다"); }
	if(!get_betable($table3)) { go("","멤버 정보저장 테이블(".$table3.")을 찾을 수 없습니다"); }

	$result=mysql_query("SELECT count(*) FROM ".$table2);
//	if(mysql_affected_rows()>0) go("","게시판을 모두 지워주신후 실행하세요b");

	mysql_query("DROP TABLE IF EXISTS ".$table2) or DB_ERR(__FILE__."-".__LINE__,"테이블 삭제 에러");
	mysql_query("DROP TABLE IF EXISTS ".$table3) or DB_ERR(__FILE__."-".__LINE__,"테이블 삭제 에러");

	$result=mysql_list_tables($db_name);
	$count=@mysql_num_rows($result);

	for($i=0;$i<$count;$i++) {
		$temp=mysql_tablename($result,$i,0);
		if(eregi("msb_",$temp)) {
			mysql_query("DROP TABLE IF EXISTS ".$temp) or go("","테이블 삭제에러");
		} elseif(eregi("msbc_",$temp)) {
			mysql_query("DROP TABLE IF EXISTS ".$temp) or go("","테이블 삭제에러");
		} elseif(eregi("msbi_",$temp)) {
			mysql_query("DROP TABLE IF EXISTS ".$temp."") or go("","테이블 삭제에러");
		}
	}

	echo ("<script>alert('DB 에서의 흔적을 지웠습니다\\n파일들은 FTP 나 TELNET 상에서 삭제해 주세요.')</script>");

	echo ("<script>location.href='".$PHP_SELF."';</script>");
} ELSEIF($p==2) {

	INCLUDE_ONCE("scheme.sql");

	if(!@mysql_result(mysql_query("Select count(*) from msbadmin"),0,0)) {
		mysql_query($member_table) or die("<blockquote><pre><br>MySQL Query Error in ".__line__."<br><p></p><br>".mysql_error()."</pre>");
	}

	if(!@mysql_result(mysql_query("Select count(*) from msbadmin"),0,0)) {
		mysql_query($admin_table) or die("<blockquote><pre><br>MySQL Query Error in ".__line__."<br><p></p><br>".mysql_error()."</pre>");
	}

	mysql_close($dbconn);
	unset($dbconn);
?>
<HTML>

<HEAD>
<title>MSBBS Installation - MSBBS 설치</title>
<style type="text/css">
A:link { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:active { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:visited { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:hover { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }


body,br,td { font-size:12px;font-family:굴림,arial,tahoma;color:black; }
body { left:0;top:0;padding:0;bottom:0;right:0 }
.input { background-color:white;border:solid 1 #193C26;height:30px;font-family:tahoma,verdana;color:black }

.title { font-size:50px;font-family:arial,tahoma,verdana;color:#2C4E38; }
.text { background-color:white;border:solid 1 #193C26;font-size:12px;height:20px;font-family:tahoma,verdana;color:black }
</style>
</HEAD>

<BODY leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<div align=center valign=top>
<table border=0 width=550 height=90% cellpadding=3 cellspacing=0 bgcolor=#F9FFFB style="border-left:solid 1 #174226;border-right:solid 1 #174226;border-bottom:solid 1 #174226">
	<tr height=150>
		<td width=550 colspan=2 align=center valign=middle><font class=title><b>MSBBS</b> Installation</font>
		</td>
	</tr>
	<tr height=70>
		<td valign=top colspan=2 style="padding-left:20">
		현재 설치과정은 Step2 입니다.<br><br>

		게시판 설정 저장테이블(msbadmin)과 멤버정보 저장테이블(msbmem) 생성을 완료하였습니다.<br>
		( 이미 설치해있으면 넘어갑니다. )<br><br>
		</td>
	</tr>
	<tr height="*">
		<td width=500 valign=top align=right><input type=button value="    Next ( Step3 )    " onclick=location.href="<?=$PHP_SELF?>?p=3" class=input></td>
		<td width=50></td>
	</tr>
</table></div>

</BODY>
</HTML>
<?
} elseif($p==3) {
?>
<HTML>

<HEAD>
<title>MSBBS Installation - MSBBS 설치</title>
<style type="text/css">
A:link { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:active { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:visited { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:hover { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }


body,br,td { font-size:12px;font-family:굴림,arial,tahoma;color:black; }
body { left:0;top:0;padding:0;bottom:0;right:0 }
.input { background-color:white;border:solid 1 #193C26;height:30px;font-family:tahoma,verdana;color:black }

.title { font-size:50px;font-family:arial,tahoma,verdana;color:#2C4E38; }
.text { background-color:white;border:solid 1 #193C26;font-size:12px;height:20px;font-family:tahoma,verdana;color:black }
</style>
<script language=Javascript>
function check() {
	if(!admin.id.value) {
		alert('아이디를 입력해 주세요')
		admin.id.focus()
		return false
	}
	if(!admin.pw.value) {
		alert('비밀번호를 입력해 주세요')
		admin.pw.focus()
		return false
	}
	if(!admin.pw2.value) {
		alert('비밀번호 확인을 입력해 주세요')
		admin.pw2.focus()
		return false
	}
	if(admin.pass.value!=admin.pass2.value) {
		admin.pw.value="";
		admin.pw2.value="";
		alert('비밀번호가 서로 맞지 않습니다')
		admin.pw.focus()
		return false
	}
	if(!admin.nm.value) {
		alert('이름을 입력해 주세요')
		admin.nm.focus()
		return false
	}
	if(!admin.eml.value) {
		alert('E-mail 주소를 입력해 주세요')
		admin.eml.focus()
		return false
	}
	return true
}
</script>
</HEAD>
<BODY>
<div align=center valign=top>
<table border=0 width=550 height=90% cellpadding=3 cellspacing=0 bgcolor=#F9FFFB style="border-left:solid 1 #174226;border-right:solid 1 #174226;border-bottom:solid 1 #174226">
	<form name=admin onsubmit="return check();" method=post action=<?=$PHP_SELF?>>
	<input type=hidden name=p value=4>
	<tr height=150>
		<td width=550 colspan=2 align=center valign=middle><font class=title><b>MSBBS</b> Installation</font>
		</td>
	</tr>
	<tr height=70>
		<td valign=top colspan=2 style="padding-left:20">
		현재 설치과정은 Step3 입니다.<br><br>

		관리자 정보를 입력하세요.<br>
		특히 아이디와 비밀번호는 잘 기억하셔야 합니다.<br><br>
		
		<table border=0 cellpadding=5 cellspacing=0 width=320 align=right bgcolor=#F7FDFB style="border:solid 1 #black">
			<tt><td colspan=2 align=center>관리자 정보를 입력하세요</td></tt>
			<tr><td colspan=2 align=center>( 이미 관리자정보가 있으면 이작업은 무시됩니다 )</td></tr>
			<tr>
				<td align=right size=30%>ID</td>
				<td width=70%><input type=text name=id class=text size=15></td>
			</tr>
			<tr>
				<td align=right>Pass</td>
				<td><input type=password name=pass class=text size=15></td>
			</tr>
			<tr>
				<td align=right>Confirm</td>
				<td><input type=password name=pass2 class=text size=15></td>
			</tr>
			<tr>
				<td align=right>Name</td>
				<td><input type=text name=name class=text size=15></td>
			</tr>
			<tr><td></td><td><input type=submit value="    Next ( Step4 )    " class=input></td></td></tr>
		</table>
		</td>
	</tr>
	<tr height="*">
		<td width=500 valign=top align=right>&nbsp;
		<td width=50></td>
	</tr>
	</form>
</table></div>

</BODY>
</HTML>
<?
} elseif($p==4) {
	
	$member=member();

	if( !$id ) { go("","아이디를 입력하세요"); }
	if( !$pw ) { go("","비밀번호를 입력하세요"); }
	if( !$pw2 ) { go("","비밀번호를 입력하세요"); }
	if( !$nm ) { go("","이름을 입력하세요"); }
	if( $pw!=$pw2 ) { go("","비밀번호가 서로 맞지 않습니다"); }

	if(mysql_result(mysql_query("SELECT count(*) FROM msbmem WHERE lv='1'"),0,0)<1) {
		mysql_query("LOCK TABLES msbmem WRITE") or go("",mysql_error());
		mysql_query("Insert into $table3

		(id,pw,lv,nm,eml,hm,icq,msn,birth,r_d,i_o)
		
		values('".$id."',password('$pw'),'1','".addslashes($nm)."','".addslashes($eml)."','','','','','".time()."','0')") or go("","Query Error");
		mysql_query("UNLOCK TABLES");
	}

	mysql_close($dbconn);
	unset($dbconn);

	go("admin.php","설치과정이 끝났습니다");

} ELSEIF($p=='uninstall') {

	$result=MYSQL_QUERY("SELECT * FROM ".$table3);

	IF( MYSQL_AFFECTED_ROWS()>0 ) {
		IF(!get_betable($table2)) {
			go("","게시판 설정테이블(".$table2.")을 찾을 수 없습니다");
		}

		$member=member();
		if($member[4]!=1) go("","최고관리자 영역입니다");

		mysql_close($dbconn);

?>
<HTML>

<HEAD>
	<title> MSBBS UnInstallation - MSBBS 삭제 </title>
	<META HTTP-EQUIV=CONTENT-TYPE CONTENT='TEXT-HTML;CHARSET=Euc-kr'>
	<style type="text/css">
	A:link { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:none }
	A:active { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:none }
	A:visited { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:none }
	A:hover { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }

	body,br,td { font-size:12px;font-family:굴림,arial,tahoma;color:black; }
	body { left:0;top:0;padding:0;bottom:0;right:0 }
	.input { background-color:white;border:solid 1 #193C26;height:30px;font-family:tahoma,verdana;color:black }

	.title { font-size:50px;font-family:arial,tahoma,verdana;color:#2C4E38; }
	.text { background-color:white;border:solid 1 #193C26;font-size:12px;height:20px;font-family:tahoma,verdana;color:black }
	</style>
</HEAD>

<BODY bgcolor=#FFFFFF><br>

<TABLE width=500 cellpadding=20 cellspacing=0 align=center border=0 style='border:solid 1 gray'>
	<TR>
		<TD>
		<br><br>
		나중에 재설치할 수 있지만 이전의 자료는 다 날라갑니다.<BR><BR>

		데이터베이스에 있는 테이블만 삭제합니다.<BR><BR>

		파일삭제는 FTP 등에서 직접 하세요;<BR><BR>

		<a href='<?=$PHP_SELF?>?p=100'><b>>진행</b></a><BR><BR>
		</TD>
	</TR>
</TABLE>
</BODY>

</HTML>
<?

		exit;
	}
} ELSE {

?>
<HTML>

<HEAD>
<title>MSBBS Installation - MSBBS 설치</title>
<style type="text/css">
A:link { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:active { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:visited { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }
A:hover { font-size:12px;font-family:tahoma,verdana;color:#4E3C2A;text-decoration:underline }


body,br,td { font-size:12px;font-family:굴림,arial,tahoma;color:black; }
body { left:0;top:0;padding:0;bottom:0;right:0 }
.input { background-color:white;border:solid 1 #193C26;height:30px;font-family:tahoma,verdana;color:black }

.title { font-size:50px;font-family:arial,tahoma,verdana;color:#2C4E38; }
.text { background-color:white;border:solid 1 #193C26;font-size:12px;height:20px;font-family:tahoma,verdana;color:black }
</style>
</HEAD>

<BODY leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>

<div align=center valign=top>
<table border=0 width=550 height=90% cellpadding=3 cellspacing=0 bgcolor=#F9FFFB style="border-left:solid 1 #174226;border-right:solid 1 #174226;border-bottom:solid 1 #174226">
	<tr height=150>
		<td width=550 colspan=2 align=center valign=middle><font class=title><b>MSBBS</b> Installation</font>
		</td>
	</tr>
	<tr height=70>
		<td valign=top colspan=2 style="padding-left:20">
		1. <a target=_blank href="readme.txt">readme.txt</a> 의 라이센스부분을 꼭 읽어보시기 바랍니다.<br>
		2. 설치하기전에 반드시 <a target=_blank href="http://byariel.com">공식배포처</a>에서 최신 파일인지 확인하시기 바랍니다.<br><br>

		현재 설치하시려는 <font color=red>MSBBS</font> 의 버전은 <font color=red><?=$ver?></font>입니다.
		</td>
	</tr>
	<tr height="*">
		<td width=500 valign=top align=right><input type=button value="    Next ( Step2 )    " onclick=location.href="<?=$PHP_SELF?>?p=2" class=input></td>
		<td width=50></td>
	</tr>
</table></div>

</BODY>

</HTML>
<?

}

if($dbconn) {
	mysql_close($dbconn);
}
exit;
?>