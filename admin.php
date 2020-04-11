<?
REQUIRE_ONCE('./lib.php');

IF($act=='login') {
	login($u_id,$u_pw);
	HEADER('Location:./admin.php');
} ELSEIF($act=='logout') {
	setcookie('msbbs','','','/');
	HEADER('Location:./admin.php');
	exit;
}

FUNCTION showtables($str1) {// $str1 은 <select> 의 이름
	Global $table2,$table3;

	echo ('
		<SELECT size=5 style="width:360;" name="'.$str1.'[]" multiple=multiple>
			<option value="">게시판설정과 멤버자료</option>');
	$result=MYSQL_QUERY("Show tables;") or DB_ERR(__FILE__."-".__LINE__);

	WHILE($d=mysql_fetch_array($result)) {
		if(eregi("msb_+",$d[0]) ) {//msbbs 와 관련있는 테이블 조사~
			$d[0]=eregi_replace("msb_","",$d[0]);
			echo ("
			<option value='".$d[0]."'>$d[0]</option>");
		}
		unset($d);
	}
	echo ("
		</SELECT>");
}

FUNCTION gboard() {
	Global $table2,$id,$act;

	if($act=="modify") {
		if(!$id) go("","게시판 이름을 지정해 주세요");

		$result=MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."'") or DB_ERR(__FILE__."-".__LINE__);
		$result=mysql_fetch_array($result);

		$result[4]=stripslashes($result[4]);
		$result[29]=stripslashes($result[29]);
		$result[30]=stripslashes($result[30]);
		$result[31]=stripslashes($result[31]);
		$result[32]=stripslashes($result[32]);
		$result[33]=stripslashes($result[33]);


		$result[38]=stripslashes($result[38]);
		$result[39]=stripslashes($result[39]);

		$result[42]=stripslashes($result[42]);

		$result[43]=stripslashes($result[43]);
		$result[44]=stripslashes($result[44]);

		if($result[2]=="0") $result[2]="";
		if($result[4]=="0") $result[4]="";

	} else {
		$result=array("","","","","","0","9","9","0","0","1","0","0","0","0","0","9","0","9","0","9","9","9","0","1","0","0","9","9",'0','0','0','0',"","","","","","90%","3","30","10","","","1048576","0","","","");

	}

	return $result;
}

FUNCTION check_adv() {
	GLOBAL $id,$skin,$opr,$m_wd,$m_inp,$fl_mn;

	if(!$id OR strlen($id)>20) go("","게시판 이름이 지정되지 않았습니다");
	if(!$skin) go("","스킨을 지정해 주세요");
	if(!eregi("([\_a-z0-9])",$id)) go("","게시판 이름에는 영문소문자와 숫자 그리고 언더스코어(_)만 허용합니다");
	if(!eregi("([0-9])",$opr) AND $opr!="") go("","게시판 관리자 번호를 올바로 지정하세요");
	if(!eregi("([0-9])%$|px$",$m_wd)) go("","게시판 가로길이를 올바로 지정하세요");
	if(!eregi("([0-9])",$m_inp)) go("","한 페이지에 표시되는 게시물 수(m_inp) 를 올바로 지정하세요");
	if(!eregi("([0-9])",$fl_mn)) go("","업로드 가능한 파일 갯수를 올바로 지정하세요");

	return true;
}

FUNCTION HEAD_ADMIN($str1=false) {
	GLOBAL $PHP_SELF;
?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>

<HTML>

<HEAD>
	<TITLE>MSBBS Admin</TITLE>
	<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE' />
	<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE' />
	<META HTTP-EQUIV='CONTENT-TYPE' CONTENT='TEXT-HTML;CHARSET=EUC-KR' />
	<STYLE type='text/css'>
	A:link					{ text-decoration:none;color:darkred;font-family:tahoma,verdana;font-size:8pt }
	A:active				{ text-decoration:none;color:darkred;font-family:tahoma,verdana;font-size:8pt }
	A:visited				{ text-decoration:none;color:darkred;font-family:tahoma,verdana;font-size:8pt }
	A:hover					{ text-decoration:underline;color:red;font-family:tahoma,verdana;font-size:8pt }

	body,table,td,br		{ font-family:tahoma,verdana;font-size:11px;color:333333 }

	.input					{ border:solid 1 black;background-color:white;font-family:tahoma,verdana;font-size:8pt;height:22px;padding:2 }
	.confirm				{ font-size:7pt;font-family:tahoma,verdana;height:17px;border:solid 1 gray;background-color:eeeeee;color:333333;width:140;height:30 }
	.confirm_s				{ font-size:7pt;font-family:tahoma,verdana;border:solid 1 gray;background-color:eeeeee;color:333333;height:22px }
	</STYLE>
<?	if($str1!="sendmail") {?>
	<script language=Javascript>
	function mexec(str1) {
		document.list.bo.value=str1;
		document.list.submit();
	}
	function inputv(str1,str2) {
		document.form.str1.value=str2;
	}
	function mexec(str1) {
		if(str1=="deleteall") {
			if(confirm('정말로 삭제하시겠습니까?\n\n삭제시 이전의 자료를 되돌릴 수 없습니다')) {
				document.list.bo.value=str1;
				document.list.submit();
			}
		} else {
			document.list.bo.value=str1;
			document.list.submit();
		}
	}
	</SCRIPT>
<?	}?>
	<SCRIPT lANGUAGE=javascript>
	function reverse() {
		var i, chked=0;
		if(confirm('선택된 항목을 반전하시겠습니까?')) {
			for(i=0;i<document.list.length;i++) {
				if(document.list[i].type=='checkbox') {
					if(document.list[i].checked) { document.list[i].checked=false; }
					else { document.list[i].checked=true; }
				}
			}
		}

		for(i=0;i<document.list.length;i++) {
			if(document.list[i].type=='checkbox') {
				if(document.list[i].checked) chked=1;
			}
		}
	}
	function page(str1) {
		self.location.href='<?=$PHP_SELF?>?&page='+str1;
	}
	</script>
</HEAD>

<BODY LEFTMARGIN=0 TOPMARGIN=0>

<table border=0 cellpadding=0 cellspacing=0 width=600>
	<tr height=10><td colspan=3></td></tr>
	<tr height=25><td colspan=3 align=left><img src="./img/admin_logo.gif" border=0 width=99 height=25></td></tr>
<?	if($str1!="sendmail") {?>
	<tr bgcolor=#333333 height=25>
		<td align=left valign=middle>
		&nbsp;&nbsp;<a href='<?=$PHP_SELF?>'><font style='font-size:9pt;color:white;font-family:굴림'>게시판관리</font></a>&nbsp;|
		&nbsp;<a href='<?=$PHP_SELF?>?bo=member'><font style='font-size:9pt;color:white;font-family:굴림'>멤버관리</font></a>&nbsp;|
		&nbsp;<a href='<?=$PHP_SELF?>?bo=global'><font style='font-size:9pt;color:white;font-family:굴림'>백업&복원</font></a>&nbsp;|
		&nbsp;<a href='<?=$PHP_SELF?>?bo=license'><font style='font-size:9pt;color:white;font-family:굴림'>라이센스관리</font></a>
		</td>
		<td align=right valign=middle><a href='<?=$PHP_SELF?>?act=logout'><font style='font-size:9pt;color:white;font-family:굴림'>로그아웃</font></a>&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan=3>
<?	}
}

FUNCTION FOOT_ADMIN($str1=false) {
	if($str1!="sendmail") {
?>
<BR><BR><BR><BR><BR>
			</td>
	</tr>
<?	}?>
</table>

</BODY>

</HTML>
<?
}

FUNCTION showg($str1,$str2,$str3,$str4=false) {//str1 is Select`s name, str2 is option`s number AND str3 is option`s value
	if($str2<1) $str2=1;

	if($str2==2) {
		$temp=explode(",",$str4);

		if(count($temp)>2) {
			echo ('
				<select name='.$str1.'>');

			FOR($i=0;$i<=$str2;$i++) {
				$z[0]=$i;
				$z[1]=$i;

				If($i==0 AND ( $str1!="p_list" AND $str1!="p_view" AND $str1!="p_write" AND $str1!="p_delete" AND $str1!="p_reply" AND $str1!="p_modify" ) ) {
					$z[1]="사용안함";
				} ELSEIF($i==0) {
					$z[0]+=1;
					$z[1]+=1;
					$i+=1;
				}

				if($i==$str3) {
					echo ('
				<option value='.$z[0].' selected>'.$z[1].'</option>');
				} else {
					echo ('
				<option value='.$z[0].'>'.$z[1].'</option>');
				}
			}

			echo ('
				</select>');
		} else {

			if($str3==1) {
				$temp1='';
				$temp2='checked';
			} else {
				$temp1='checked';
				$temp2='';
			}

			echo ('
				<input type=radio name="'.$str1.'" value=0 '.$temp1.'>'.$temp[0]);
			echo ('
				<input type=radio name="'.$str1.'" value=1 '.$temp2.'>'.$temp[1]);
		}
	} else {
		echo ('
			<select name='.$str1.'>');

		FOR($i=0;$i<=$str2;$i++) {
			$z[0]=$i;
			$z[1]=$i;
			If($i==0 AND ( $str1!="p_list" AND $str1!="p_view" AND $str1!="p_write" AND $str1!="p_delete" AND $str1!="p_reply" AND $str1!="p_modify" ) ) {
				$z[1]="사용안함";
			} ELSEIF($i==0) {
				$z[0]+=1;
				$z[1]+=1;
				$i+=1;
			}

			if($str1=='p_login') {
				if($z[0]!=1 and $z[0]!=0) {
					echo ('
				<option value='.$z[0].' selected>'.$z[1].'</option>');
				}
			} elseif($i==$str3) {
				echo ('
				<option value='.$z[0].' selected>'.$z[1].'</option>');
			} else {
				echo ('
				<option value='.$z[0].'>'.$z[1].'</option>');
			}
		}

		echo ('
			</select>');
	}
}

$member=member();

IF($member[4]>1) {
echo('
<HTML>

<HEAD>
	<title>MSBBS Admin</title>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="TEXT-HTML;CHARSET=EUC-KR" />
	<STYLE type="text/css">
	A:link					{ text-decoration:none;color:darkred;font-family:tahoma,verdana;font-size:8pt }
	A:active				{ text-decoration:none;color:darkred;font-family:tahoma,verdana;font-size:8pt }
	A:visited				{ text-decoration:none;color:darkred;font-family:tahoma,verdana;font-size:8pt }
	A:hover					{ text-decoration:underline;color:red;font-family:tahoma,verdana;font-size:8pt }

	body,table,td,br		{ font-family:tahoma,verdana;font-size:11px;color:333333 }
	.input					{ border:solid 1 black;background-color:white;font-family:tahoma,verdana;font-size:8pt }
	</STYLE>
	<SCRIPT Language="Javascript">
	function check_login() {
		if( document.login.u_id.value=="" ) {
			alert("ID 를 입력하십시오");
			document.login.u_id.focus();
			return false;
		}

		if( document.login.u_pw.value=="" ) {
			alert("Password 를 입력하십시오");
			document.login.u_pw.focus();
			return false;
		}

		return true;
	}
	</Script>
</HEAD>

<BODY leftmargin=2 topmargin=2 bgcolor=#000000 onload="login.u_id.focus();"><br><br><br><br>

<table width=150 border=0 cellpadding=0 cellspacing=4 align=center bgcolor=#444444 style="border:solid 1 #666666">
	<form name=login action='.$PHP_SELF.' onsubmit="return check_login();" method="post">
	<input type=hidden name=act value=login>
	<tr>
		<td width=19% align=right><font style="color:white;font-family:tahoma,verdana;font-size:7pt">ID</font>
		</td>
		<td width=81%><input type=text size=14 name=u_id style="background-color:#DDDDDD;border:solid 1 #DDDDDD">
		</td>
	</tr>
	<tr>
		<td width=19% align=right><font style="color:white;font-family:tahoma,verdana;font-size:7pt">Pass</font>
		</td>
		<td width=81%><input type=password size=14 name=u_pw style="background-color:#DDDDDD;border:solid 1 #DDDDDD">
		</td>
	</tr>
	<tr>
		<td>
		</td>
		<td align=left><input type=submit value="  Login  " title=" MSBBS Loginer " class=input style="background-color:#555555;color:#DDDDDD"> <input type=button value="  Back  " onclick="history.back()" title=" Go Back " class=input style="background-color:#AAAAAA">
		</td>
	</tr>
	</form>
</table>

</BODY>

</HTML>');

} ELSE {

SWITCH ($bo) {
	CASE (license_2) :

		$fp=fopen("./license.txt","w");
		$result=fwrite($fp,$text);
		fclose($fp);

		if($del=="on" and is_file("license.txt")) {
			chmod("./","0777");
			unlink("./license.txt");
		}

		go($HTTP_REFERER,"작업 완료");

	exit;
	CASE (license):
		HEAD_ADMIN();

echo('
<table border=0 cellpadding=3 cellspacing=0 width=100%>
	<tr>
		<td colspan=2 align=center style="font-size:18pt;color:555555"><b>License</b>
		</td>
	</tr>
	<tr>
		<td colspan=2>
		가입폼 위에 뭐가 어쩌고 저쩌고 라이센스 표시를 지정하는 겁니다.<br>
		비워두시면 안 뜹니다.
		</td>
	</tr>
	<FORM NAME=form ENCTYPE="multipart/form-data" METHOD=post ACTION='.$PHP_SELF.'>
	<input type=hidden name=bo value="license_2">
	<tr>
		<td colspan=2 align=center>
		<table border=0 cellpadding=8 cellspacing=0 width=90% style="border:solid 1 #505050">
			<tr>
				<td align=center>
				<b>라이센스 관리</td>
			</tr>
			<tr>
				<td align=center>
				<textarea name=text style="width:98%;height:150;border:solid 1 #AFAFAF;background-color:white">');
				@include("license.txt");
				echo('</textarea>
				<p align=right>delete it<input type=checkbox name=del ONCLICK="if(confirm(\'삭제하시겠습니까?\')){form.text.value=\'\';}"></p>
				</td>
			</tr>
			<tr>
				<td align=right>
				<input type=submit value=" Submit " class=confirm></td></tr>
		</table>
		</td>
	</tr>
	</FORM>
</table>');

		FOOT_ADMIN();

	exit;
	CASE (query_work):

		if(is_uploaded_file($file) OR $query_text) {// 파일 업로드 됨
			if(strrchr($file_name,".")!=".sql") go("",".sql 파일이 아닙니다");

			move_uploaded_file($file,"./$file_name");
			@chmod("./$file_name","0707");

			$fp=fopen("./$file_name","r");
			$query=fread($fp,filesize("./$file_name"));
			if(get_magic_quotes_runtime()==1) $query=stripslashes($query);
			fclose($fp);

			$query.=$query_text;

			//쿼리작업이 한번에 한 명령만 처리할 수 있기 때문에-_-;; 나눠서 실행해줘야함
			$query_i=split(";",$query);

			FOR($i=0;$i<count($query_i);$i++) {
				$query_i[$i]=trim($query_i[$i]);
				
				@MYSQL_QUERY("$query_i[$i]") or DB_ERR(__FILE__."-".__LINE__);
			}

			unlink("./".$file_name);
		}

		go($HTTP_REFERER,"작업 완료");

	exit;
	CASE ("global"):

		HEAD_ADMIN();

echo('
			<TABLE border=0 cellpadding=3 cellspacing=0 width=100%>
				<tr>
					<td colspan=2 align=center style="font-size:18pt;color:555555"><b>Backup&Dump&Patch</b>
					</td>
				</tr>
				<tr>
					<td colspan=2>
					<blockquote>
					<li>백업하신후 잘 보존하세요;</li>
					<li>혹시 잘 되지 않는다면 <a href=http://byariel.com target=_blank>공식배포처</a>에 상태를 <font color=red>자세히</font> 리포트 해주세요;</li>
					</blockquote>
					</td>
				</tr>
				<FORM ENCTYPE="multipart/form-data" METHOD=post ACTION='.$PHP_SELF.'>
				<input type=hidden name=bo value="query_work">
				<tr>
					<td colspan=2 align=center>
					<table border=0 cellpadding=8 cellspacing=0 width=90% style="border:solid 1 #505050">
						<tr bgcolor=#EEEEEE>
							<td align=center>
							<b>쿼리 작업</td>
						</tr>
						<tr>
							<td align=right>
						이 안에 적으실 것은 쿼리문입니다. 한번에 하나의 명령만 실행합니다.<br>
						파일 업로드는 <font size=1>.sql</font> 파일밖에 허용하지 않습니다.</td>
						</tr>
						<tr>
							<td align=center>
							<textarea name=query_text style="width:98%;height:50"></textarea></td>
						</tr>
						<tr><td align=right><input type=file name=file style="width:95%" class=bdp_file></td>
						<tr>
							<td align=right>
							<input type=submit value=" Submit " class=bdp_confirm></td></tr>
					</table>
					</td>
				</tr>
				</FORM>

				<FORM name=backup action='.$PHP_SELF.' method=post>
				<input type=hidden name=bo value="backup">
				<tr>
					<td colspan=2 align=center>
					<table border=0 cellpadding=8 cellspacing=0 width=90% style="border:solid 1 #505050">
						<tr bgcolor=#EEEEEE>
							<td align=center><b>백업 - <font size=1>Backup</font></td>
						</tr>
						<tr>
							<td align=center>
							<table border=0 cellpadding=0 cellspacing=0 width=400>
							<TR><TD>');

							showtables('table');
echo('
							<br>
							<input type=checkbox name=structure><a href=# onclick="if(backup.structure.checked==true){backup.structure.checked=false}else{backup.structure.checked=true;return true}"><font size=1>Structure</font></a> <input type=checkbox name=data><a href=# onclick="if(backup.data.checked==true){backup.data.checked=false}else{backup.data.checked=true}"><font size=1>Data</font></a><br>
							<input type=checkbox name=drop><a href=# onclick="if(backup.drop.checked==true){backup.drop.checked=false}else{backup.drop.checked=true}"><font size=1>Structure</font> 체크시 <font size=1>"Drop Table"</font> 추가</a><br><br>

							<input type=radio checked name=dt value=1><font size=1>data</font>는 복구시 <font color=red>완전히 새로쓰기</font> 형태가 되도록 합니다<br>
							<input type=radio name=dt value=2><font size=1>data</font>는 복구시 <font color=red>새로쓰기(추가)</font> 형태가 되도록 합니다<br>
							<input type=radio name=dt value=3><font size=1>data</font>는 복구시 <font color=red>덮어쓰기(수정)</font> 형태가 되도록 합니다<br><br>

							<li>게시판 설정 복구시에는 기존 게시판의 설정을 덮어쓰기 합니다</li>
							<li>저 위의 설정이 적용되지 않는 부분도 있습니다</li>
							<li>저 data 세개의 차이점은 Primary Key 에 관한 부분입니다</li>
							
							<p align=right>
							<input type=submit value=" Backup " class=bdp_confirm>
							</p>
							</TD></TR>
							</table>
							</td>
						</tr>
						<tr height=20><td></td></tr>
					</table>
					</td>
				</tr>
				</FORM>
			</table>');

		FOOT_ADMIN();

	exit;
	CASE (m_info_save):

		if(!$id) go("","ID 를 입력해 주세요");
		if(!$nm) go("","이름(혹은 닉네임)을 입력해 주세요");
		if(!$eml) go("","Email 주소를 입력해 주세요\\n\\n비밀번호 찾기에 꼭 필요합니다");
		if($pw!=$pw2) go("","비밀번호를 올바로 입력하세요");
		if(strlen($id) < 3 or strlen($id) > 12) go("","아이디 글자수가 너무 작거나 깁니다");
		if(!eregi("([_0-9a-zA-Z])",$id)) go("","아이디는 숫자와 영문자의 조합으로 이루어져야 합니다");
		if(!eregi("([^[:space:]]+)", $nm)) go ("","이름을 올바로 입력해 주세요");
		if(!eregi("([^[:space:]]+)" , $eml) && !eregi("([_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)" , $eml)) { go("","E-mail 주소를 올바로 입력하세요"); }
		if($i_o=="on") { $i_o=1; } else { $i_o="0"; }

		$id=trim(addslashes($id));
		$nm=trim(addslashes($nm));
		$eml=trim(addslashes($eml));
		$mm=trim(addslashes($mm));

		if($birth) { $birth.=$sl; }
		else { $birth=""; }

		$res=mysql_fetch_array(MYSQL_QUERY("Select pw,lv,r_d from $table3 Where id='$id'"));

		if($pw and $pw2) {
			$res=MYSQL_QUERY("Update ".$table3." set id='$id', pw=password('$pw'), nm='$nm', eml='$eml', hm='$hm', icq='$icq', msn='$msn', birth='$birth', mm='$mm', i_o='$i_o' Where no='$no' and id='$id'") or DB_ERR(__FILE__."-".__LINE__);
		} else {
			$res=MYSQL_QUERY("Update $table3 set id='$id', nm='$nm', eml='$eml', hm='$hm', icq='$icq', msn='$msn', birth='$birth', mm='$mm', i_o='$i_o' Where no='$no' and id='$id'") or DB_ERR(__FILE__."-".__LINE__);
		}

		go("javascript:window.close()","");

	exit;
	CASE (m_info):

		FUNCTION get_m_info() {
			Global $table3,$no;

			if(!$no) go("","멤버 번호를 지정해 주세요");
			$result=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM ".$table3." Where no='".$no."'"));
			$result[5]=trim(stripslashes($result[5]));
			$result[6]=trim(stripslashes($result[6]));
			$result[9]=trim(stripslashes($result[9]));
			$result[12]=trim(stripslashes($result[12]));

			return $result;
		}
		$result=get_m_info();

echo('
	<HTML>

	<HEAD>
		<TITLE>MSBBS - Member Information</TITLE>
		<META HTTP-EQUIV=CONTENT-TYPE CONTENT="TEXT-HTML;CHARSET=euc-kr">
		<link rel=STYLESHEET href="./style.css">
		<script language=javascript>
		function check() {
			if(join.nm.value=="") {
				alert("이름을 올바로 입력하세요");
				join.nm.focus();
				return false;
			}
			if(join.eml.value=="") {
				alert("메일 주소를 반드시 적으세요");
				join.eml.focus();
				return false;
			}
			if(join.pw.value!=join.pw2.value) {
				alert("비밀번호를 올바로 입력하세요");
				return false;
			}
			return true;
		}
		</script>
	</HEAD>

	<BODY leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
	<br>
	<table border=0 width=94% align=center cellpadding=1 cellspacing=0>
		<form name="join" onsubmit="return check();" action="'.$PHP_SELF.'" method=post>
		<input type=hidden name=no value='.$no.'>
		<input type=hidden name=bo value="m_info_save">
		<input type=hidden name=id value='.$id.'>
		<input type=hidden name=page value='.$page.'>
		<tr bgcolor=#DADADA height=40>
			<td colspan=2 align=center>회원 정보수정
			</td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td width=25% align=right><font size=1><font color=red>*</font> ID&nbsp;</td>
			<td width=80%><input type=text class=input name=id size=16 maxlength=12 align=absmiddle value='.$result[2].' readonly></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td align=right><font size=1><font color=red>*</font> Name&nbsp;</td>
			<td><input type=text name=nm class=input size=16 maxlength=12 align=absmiddle value='.$result[5].'></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td align=right><font size=1><font color=red>*</font> Password&nbsp;</td>
			<td><input type=password class=input name=pw size=16 maxlength=12 align=absmiddle></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td align=right><font size=1><font color=red>*</font> Confirm&nbsp;</td>
			<td><input type=password class=input name=pw2 size=16 maxlength=12 align=absmiddle></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td align=right><font size=1><font color=red>*</font> Email&nbsp;</td>
			<td><input type=text class=input name=eml size=30 maxlength=100 align=absmiddle value='.$result[6].'></td>
		</tr>
		<tr>
			<td align=right><font size=1>Homepage&nbsp;</td>
			<td><input type=text class=input name=hm size=30 maxlength=100 align=absmiddle value='.$result[7].'></td>
		</tr>
		<tr>
			<td align=right><font size=1>Birthday&nbsp;</td>
			<td><input type=text class=input name=birth size=8 maxlength=8 align=top value='.$birth.'><font size=1>( yyyymmdd )</td>
		</tr>
		<tr>
			<td align=right><font size=1>Icq&nbsp;</td>
			<td><input type=text class=input name=icq size=16 maxlength=10 align=absmiddle value='.$result[icq].'></td>
		</tr>
		<tr>
			<td align=right><font size=1>Msn&nbsp;</td>
			<td><input type=text class=input name=msn size=30 maxlength=100 align=absmiddle value='.$result[msn].'></td>
		</tr>
		<tr>
			<td align=right><font size=1>Comment&nbsp;</td>
			<td><textarea name=mm style="border:solid 1 black;font-size:9pt;font-family:tahoma,verdana;padding:2" rows=5 cols=45 align=absmiddle>'.$result[mm].'</textarea></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td align=right></td>
			<td><input type=checkbox name=i_o align=absmiddle> <a href=# onclick="if(join.i_o.checked==true){join.i_o.checked=false}else{join.i_o.checked=true}">정보공개</font> <font color=red>*</font></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td align=right></td>
			<td><input type=submit value=" Confirm " style="background-color:#EEEEEE;width:80;height:30;border:solid 1 #AAAAAA;font-family:tahoma,verdana;font-size:8pt;color:black"> <input type=button value=" Close " onclick="window.close()" style="background-color:#EEEEEE;width:80;height:30;border:solid 1 #AAAAAA;font-family:tahoma,verdana;font-size:8pt;color:black"></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td colspan=2><br>
			&nbsp;&nbsp;<font color=red>*</font> 비밀번호는 변경할 시에만 입력해 주세요.<br>&nbsp;
			</td>
		</tr>
		</form>
	</table>
	<BR>');

	exit;
	CASE (optimize):

		if(!$id) go("","게시판 이름을 지정하세요");
		@MYSQL_QUERY("OPTIMIZE TABLE msb_".$id);
		@MYSQL_QUERY("OPTIMIZE TABLE msbc_".$id);
		@MYSQL_QUERY("OPTIMIZE TABLE msbi_".$id);
		go($PHP_SELF,"");

	exit;
	CASE (repair):

		if(!$id) go("","게시판 이름을 지정하세요");
		@MYSQL_QUERY("REPAIR TABLE msb_".$id);
		@MYSQL_QUERY("REPAIR TABLE msbc_".$id);
		@MYSQL_QUERY("REPAIR TABLE msbi_".$id);
		go($PHP_SELF,"");

	exit;
	CASE ("rename"):

		IF(!$id) go("","바꿀 게시판 이름을 지정하세요");
		HEAD_ADMIN();

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."'"))<1) {
			go("","바꿀 게시판이 존재하지 않습니다");
		}

echo('
	<BR><BR><BR><BR><BR><BR>
	<FORM NAME=admin ACTION='.$PHP_SELF.' METHOD=post>
	<input type=hidden name=id value='.$id.'>
	<input type=hidden name=bo value="rename_ok">
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=center width=400>
		<TR>
			<TD colspan=3 align=center style="font-size:18pt;color:555555"><b>RENAME</b>
			</TD>
		</TR>
		<TR>
			<TD COLSPAN=3 align=center>
			바꿀 게시판 이름(최고 20자)을 지정하세요.<BR>
			게시판 이름에는 영문소문자와 숫자 그리고 언더스코어(_)만 허용합니다
			</TD>
		</TR>
		<TR height=20><TD></TD></TR>
		<TR>
			<TD width=45% align=right><b>'.$id.'</b></TD>
			<TD WIDTH=10% align=center>→</TD>
			<TD width=45%><input type=text class=input size=15 name=target maxlength=20>
			</TD>
		</TR>
		<TR height=20><TD></TD></TR>
		<TR>
			<TD COLSPAN=3 align=center><INPUT TYPE=submit VALUE=" RENAME! " Class=input_nor>
			</TD>
		</TR>
	</TABLE>
	</FORM>');

		FOOT_ADMIN();

	exit;
	CASE (rename_ok):

		if(!$id and strlen($id)>20 ) { go("","게시판 이름이 지정이 안되었거나 너무 깁니다"); }
		if(!$target or strlen($target)>20 ) { go("","바꿀 이름이 지정 안되었거나 너무 깁니다"); }
		
		if( !eregi("([\_a-z0-9])",$id) ) { go("","기존 게시판 이름에는 영문소문자와 숫자 그리고 언더스코어(_)만 허용합니다"); }
		if( !eregi("([\_0-9a-z])",$target) ) { go("","바꿀 게시판 이름에는 영문소문자와 숫자 그리고 언더스코어(_)만 허용합니다"); }

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$id."'"))<1) {
			go("","바꿀 게시판이 존재하지 않습니다");
		}
		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$target."'"))>0) {
			go("","이미 똑같은 게시판이 존재합니다");
		}

		MYSQL_QUERY("UPDATE ".$table2." SET id='".$target."' WHERE id='".$id."'") or DB_ERR(__FILE__."-".__LINE__);// 게시판 설정에서 이름 바꾸기
		MYSQL_QUERY("RENAME TABLE msb_".$id." TO msb_".$target) or DB_ERR(__FILE__."-".__LINE__);// 게시판 이름 바꾸기 - 본체
		MYSQL_QUERY("RENAME TABLE msbi_".$id." TO msbi_".$target) or DB_ERR(__FILE__."-".__LINE__);// 게시판 이름 바꾸기 - 보조
		MYSQL_QUERY("RENAME TABLE msbc_".$id." TO msbc_".$target) or DB_ERR(__FILE__."-".__LINE__);// 게시판 이름 바꾸기 - 코멘트

		set_time_limit(0);

		MYSQL_QUERY("UPDATE ".$table3." SET bid='".$target."' WHERE bid='".$id."'") or DB_ERR(__FILE__."-".__LINE__);

		go($PHP_SELF,"설정 완료");

	exit;

	CASE (copy1_ok) :

		if(!$id) go("","게시판 이름이 지정이 안 되었습니다");
		if(!$target or strlen($target)>20) go("","복사할 게시판 이름이 지정 안되었거나 너무 깁니다");

		if( !eregi("([\_0-9a-z])",$target) ) { go("","바꿀 게시판 이름에는 영문소문자와 숫자 그리고 언더스코어(_)만 허용합니다"); }

		$result=MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."'") or DB_ERR(__FILE__."-".__LINE__);
		if(mysql_num_rows($result)<1) {
			go("","바꿀 게시판이 존재하지 않습니다");
		}
		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$target."'"))>0) {
			go("","이미 똑같은 게시판이 존재합니다");
		}

		$result=MYSQL_FETCH_ARRAY($result);

		MYSQL_QUERY("INSERT INTO $table2
		values('$target','$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]','$result[11]','$result[12]','$result[13]','$result[14]','$result[15]','$result[16]','$result[17]','$result[18]','$result[19]','$result[20]','$result[21]','$result[22]','$result[23]','$result[24]','$result[25]','$result[26]','$result[27]','$result[28]','$result[29]','$result[30]','$result[31]','$result[32]','$result[33]','$result[34]','$result[35]','$result[36]','$result[37]','$result[38]','$result[39]','$result[40]','$result[41]','$result[42]','$result[43]','$result[44]','$result[45]','$result[46]','$result[47]','$result[48]')
		
		") or DB_ERR(__FILE__."-".__LINE__);

		$id=$target;

		REQUIRE_ONCE("./scheme.sql");

		MYSQL_QUERY("$board_table") OR DB_ERR(__FILE__."-".__LINE__,"메인 게시판 생성 에러");;
		MYSQL_QUERY("$first_article") OR DB_ERR(__FILE__."-".__LINE__,"가상게시물 삽입 에러");
		MYSQL_QUERY("$idx_table") OR DB_ERR(__FILE__."-".__LINE__,"보조 테이블 생성 에러");
		MYSQL_QUERY("$second_article") OR DB_ERR(__FILE__."-".__LINE__,"가상게시물 삽입 에러");
		MYSQL_QUERY("$comment_table") OR DB_ERR(__FILE__."-".__LINE__,"코멘트 테이블 생성 에러");

		go("admin.php","");

	exit;

	CASE (copy2_ok) :

		go("","아직 지원하지 않는 기능입니다");

		if(!$id) go("","게시판 이름이 지정이 안 되었습니다");
		if(!$target or strlen($target)>20) go("","복사할 게시판 이름이 지정 안되었거나 너무 깁니다");

		if( !eregi("([\_0-9a-z])",$target) ) { go("","바꿀 게시판 이름에는 영문소문자와 숫자 그리고 언더스코어(_)만 허용합니다"); }

		$result=@MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."' LIMIT 1") or DB_ERR(__FILE__."-".__LINE__);
		if( MYSQL_NUM_ROWS($result)<1) {
			go("","복사할 게시판이 존재하지 않거나 게시물이 존재하지 않습니다");
		}
		if( MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$target."' LIMIT 1"))<1 ) {
			go("","복사물을 저장할 게시판이 존재하지 않습니다");
		}

		$result=MYSQL_QUERY("SELECT * FROM $table1 ORDER BY no asc");

//		mysql_query("LOCK TABLES ".$table1." WRITE, ".$table4." WRITE, ".$table5." WRITE, msb_".$target." WRITE, msbi_".$target." WRITE, msbc_".$target." WRITE") or DB_ERR(__FILE__."-".__LINE__);

		WHILE( $d=MYSQL_FETCH_ARRAY($result) ) {
			IF( $d[0] > 0 ) {

				IF($d[22]==1) {// 공지사항일 떄

					$result=@MYSQL_RESULT(@MYSQL_QUERY("SELECT count(*) FROM ".$table5." WHERE idx='1'"),0,0);
					if($result>0) {// 공지사항의 범위.. 즉, idx='1' 의 값이 존재할때
						$result=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT idx,main FROM ".$table5." WHERE idx='1'"));
						$result[1]-=1;

						MYSQL_QUERY("UPDATE ".$table5." set idx='1', main='".$result[1]."' WHERE idx='1'");
					} else {// idx='1' 의 게시물이 존재하지 않을 때
						MYSQL_QUERY("INSERT into ".$table5." values('1','10010','0')") or DB_ERR(__FILE__."-".__LINE__);
						$result[0]=1;
						$result[1]=1;
					}

				} ELSE {// 보통글일 때

					$result=MYSQL_QUERY("SELECT min(idx) FROM ".$table5." WHERE idx>1") or DB_ERR(__FILE__."-".__LINE__);
					$result=MYSQL_RESULT($result,0,0);

					$result=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM ".$table5." WHERE idx='".$result."'"));
					$result[1]-=1;

					if($result[1]<10) {
						$result[0]-=1;
						$result[1]="10010";
						MYSQL_QUERY("INSERT into ".$table5." values('".$result[0]."','".$result[1]."','".$result[2]."')") or DB_ERR(__FILE__."-".__LINE__);
					} else {
						MYSQL_QUERY("UPDATE ".$table5." set idx='".$result[0]."', main='".$result[1]."', rid='".$result[2]."' WHERE idx='".$result[0]."'") or DB_ERR(__FILE__."-".__LINE__);
					}

					$res=@MYSQL_QUERY("SELECT min(sno) FROM $table1 WHERE idx<901");
					$res=MYSQL_FETCH_ROW($res) or DB_ERR(__FILE__."-".__LINE__);
					$res[0]-=1;

				}

				// 데이터 옮기기
				// 물리적 자료들은 경로와 이름 모두 변함없다

				MYSQL_QUERY("INSERT INTO $table1
				(idx
				,main
				,rid
				,sno
				,cg
				,nm
				,tt
				,mm
				,pw
				,w_i
				,w_d
				,h2
				,a1
				,a2
				,bm
				,bn
				,bs
				,br
				,brc
				,bg
				,bh
				,bv
				,fn
				,fx
				,ft
				,fs)

				values('".$result[0]."'			/*idx*/
				,'".$result[1]."'				/*main*/
				,'$d[3]'						/*rid*/
				,'$res[0]'						/*sno*/
				,'$cg'							/*cg*/
				,'".addslashes($member[5])."'	/*nm*/
				,'$tt'							/*tt*/
				,'$mm'							/*mm*/
				,''
				,'".getenv("remote_addr")."'	/*w_i*/
				,'$date'						/*w_d*/
				,'$sfile[4]'					/*h2*/
				,'$a1'							/*a1*/
				,'$a2'							/*a2*/
				,'$member[0]'					/*bm*/
				,'$bn'							/*bn*/
				,'$bs'							/*bs*/
				,'$br'							/*br*/
				,'$brc'							/*brc*/
				,'$bg'							/*bg*/
				,'$bh'							/*bh*/
				,'$bv'							/*bv*/
				,'$sfile[0]'					/*fn*/
				,'$sfile[1]'					/*fx*/
				,'$sfile[2]'					/*ft*/
				,'$sfile[3]'					/*fs*/
				)") or DB_ERR(__FILE__."-".__LINE__);

			}
		}




		IF( $how==2 ) {// 이동이면 글 삭제
		}
	
	exit;

	CASE (copy1) : /* 게시판 설정 복제 */

		if(!$id) go("","게시판 이름을 지정해 주세요");

		HEAD_ADMIN();

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$id."'"))<1) {
			go("","복사할 게시판 설정이 존재하지 않습니다");
		}
		?>
		<BR><BR><BR><BR><BR><BR>
		<FORM NAME=admin ACTION="<?=$PHP_SELF?>" METHOD=post>
		<input type=hidden name=id value="<?=$id?>">
		<input type=hidden name=bo value="copy1_ok">
		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=center width=400>
			<TR>
				<TD align=center colspan=3 style='font-size:18pt;color:555555'><b>COPY Ⅰ</b>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN=3 align=center>
				복사할 게시판 설정의 이름(최고 20자)을 지정하세요.<BR>
				게시판 이름에는 영문소문자와 숫자 그리고 언더스코어(_)만 허용합니다
				</TD>
			</TR>
			<TR height=20><TD></TD></TR>
			<TR>
				<TD width=45% align=right><b><?=$id?></b></TD>
				<TD WIDTH=10% align=center>→</TD>
				<TD width=45%><input type=text class=input size=15 name=target maxlength=20>
				</TD>
			</TR>
			<TR height=20><TD></TD></TR>
			<TR>
				<TD COLSPAN=3 align=center><INPUT TYPE=submit VALUE=" COPY! " Class=input_nor>
				</TD>
			</TR>
		</TABLE>
		</FORM>
		<?
		FOOT_ADMIN();

	exit;

	CASE (copy2) : /* 게시물 복제 */

		if(!$id) go("","게시판 이름을 지정해 주세요");

		HEAD_ADMIN();

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$id."'"))<1) {
			go("","복사할 게시물이 존재하지 않습니다");
		}
		?>
		<BR><BR><BR><BR><BR><BR>
		<FORM NAME=admin ACTION="<?=$PHP_SELF?>" METHOD=post>
		<input type=hidden name=id value="<?=$id?>">
		<input type=hidden name=bo value="copy2_ok">
		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=center width=400>
			<TR>
				<TD align=center colspan=3 style='font-size:18pt;color:555555'><b>COPY Ⅱ</b>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN=3 align=center>
				게시물을 붙여넣을 게시판을 지정하세요.
				</TD>
			</TR>
			<TR height=20><TD></TD></TR>
			<TR>
				<TD width=45% align=right><b><?=$id?></b></TD>
				<TD WIDTH=10% align=center>→</TD>
				<TD width=45%>
				<select name=target>
				<?
				$result=MYSQL_QUERY("SELECT id FROM ".$table2);
				$di=0;
				while($d=MYSQL_FETCH_ARRAY($result)) {
					if($d[0]!=$id) {
						echo("
					<option value='$d[0]'>$d[0]</option>");
						$di++;
					}
				}
				?>
				</select>
				</TD>
			</TR>
			<TR>
				<TD colspan=3 align=center>
					<input type=radio name=how value=1 checked>복사
					<input type=radio name=how value=2>이동 ( 복사 + 이전게시물 삭제 )
				</TD>
			</TR>
			<TR>
				<TD colspan=3 align=center>
				<input type=checkbox name=how2> 코멘트 복사 안함
				</TD>
			</TR>
			<TR height=20><TD></TD></TR>
			<TR>
				<TD COLSPAN=3 align=center><INPUT TYPE=submit VALUE=" COPY! " Class=input_nor>
				</TD>
			</TR>
		</TABLE>
		</FORM>
		<?
		FOOT_ADMIN();

	exit;

	CASE (permission_ok):

		if(!$id) go("","게시판을 지정해 주세요");

		if($p_list>9 OR $p_list<1) go("","게시물 목록 보기 권한을 올바로 지정하세요");
		if($p_l>2 OR $p_l<0) go("","링크 사용여부를 올바로 지정하세요");
		if($p_al!="0" AND $p_al!=1) go("","오토이미지 링크 사용 권한을 올바로 지정하세요");
		if($p_h>9 OR $p_h<0) go("","HTML 사용 권한을 올바로 지정하세요");
		if($p_vl!="0" AND $p_vl!=1) go("","내용보기 및 리스트 사용여부를 올바로 지정하세요");
		if($p_cg!="0" AND $p_cg!=1) go("","카테고리 사용여부를 올바로 지정하세요");
		if($p_rm!="0" AND $p_rm!=1) go("","답글 메일 사용 권한을 올바로 지정하세요");
		if($p_reply>9 OR $p_reply<1) go("","답글 달기 권한을 올바로 지정하세요");
		if($p_vr!="0" AND $p_vr!=1) go("","답글관계 보기 권한을 올바로 지정하세요");
		if($p_write>9 OR $p_write<1) go("","게시물 쓰기 권한을 올바로 지정하세요");
		if($p_gl>9 OR $p_gl<0) go("","갤러리 사용 권한을 올바로 지정하세요");
		if($p_modify>9 OR $p_modify<1) go("","글 수정 권한을 올바로 지정하세요");
		if($p_delete>9 OR $p_delete<1) go("","글 삭제 권한을 올바로 지정하세요");
		if($p_view>9 OR $p_view<1) go("","게시물 보기 권한을 올바로 지정하세요");
		if($p_cw>9 OR $p_cw<0) go("","코멘트 쓰기 권한을 올바로 지정하세요");
		if($p_cd>9 OR $p_cd<0) go("","코멘트 사용 권한을 올바로 지정하세요");
		if($p_np!="0" AND $p_np!=1) go("","앞글 뒷글 볼 수 있는 권한을 올바로 지정하세요");
		if($p_st>9 OR $p_st<0) go("","특수 태그 사용 권한을 올바로 지정하세요");
		if($p_sc>9 OR $p_sc<0) go("","비밀글 사용 권한을 올바로 지정하세요");
		if($p_up>9 OR $p_up<0) go("","업로드 사용 권한을 올바로 지정하세요");
		if($p_dn>9 OR $p_dn<0) go("","파일 다운로드 권한을 올바로 지정하세요");
		if($p_nt>9 OR $p_nt<0) go("","공지사항 작성 권한을 올바로 지정하세요");
		if($p_join!="0" AND $p_join!=9) go("","회원가입 여부을 올바로 지정하세요");
		if($p_login>9 OR $p_login<2) go("","회원로긴 여부을 올바로 지정하세요");
		if($p_loged>9 OR $p_loged<0) go("","회원 정보수정 허용 여부을 올바로 지정하세요");
		if($p_print>9 OR $p_print<0) go("","글 프린트 여부을 올바로 지정하세요");
		if($p_find>9 OR $p_find<0) go("","회원 비밀번호 찾기 허용 여부을 올바로 지정하세요");
		if($p_copy>90 OR $p_login<0) go("","글 복사 여부을 올바로 지정하세요");

		MYSQL_QUERY("
			UPDATE $table2 set
			
			p_cg='$p_cg', p_list='$p_list'
			, p_view='$p_view', p_al='$p_al', p_l='$p_l'
			, p_h='$p_h', p_vl='$p_vl'
			, p_vr='$p_vr', p_np='$p_np'
			, p_cw='$p_cw',p_cd='$p_cd'
			, p_write='$p_write', p_rm='$p_rm'
			, p_reply='$p_reply', p_gl='$p_gl'
			, p_modify='$p_modify', p_delete='$p_delete'
			, p_st='$p_st', p_sc='$p_sc'
			, p_nt='$p_nt', p_up='$p_up'
			, p_dn='$p_dn', p_join='$p_join'
			, p_login='$p_login', p_loged='$p_loged'
			, p_print='$p_print', p_find='$p_find'
			, p_copy='$p_copy'
			
			WHERE id='".$id."'");

		go("./admin.php","");

	exit;

	CASE (create_ok):

		check_adv();

		$opr=trim($opr);
		$h_sc=trim($h_sc);
		$fl_ms=trim($fl_ms);
		$fl_awf=trim($fl_awf);
		$ft=trim($ft);
		$ft_msg=trim($ft_msg);

		$res=mysql_fetch_array(MYSQL_QUERY("SELECT count(*) FROM $table2 WHERE id='".$id."'"));

		if($res[0]>0) {//이미 값 있으면 되돌려보냄
			go("","이미 같은 이름의 게시판이 존재합니다");
		} else {//없으면 새로 등록

			MYSQL_QUERY("Insert into ".$table2."
			(id,skin
			,opr,cg
			,h_tt,h_sc
			,h_bd_at,h_bd_a
			,h_bd,m_wd
			,m_new,m_lth
			,m_inp,f_bd_a
			,f_bd,fl_ms
			,fl_mn,fl_awf
			,ft,ft_msg)

			values('$id' , '$skin' 
			, '$opr', '".addslashes($cg)."'
			, '".addslashes(trim($h_tt))."', '".addslashes($h_sc)."'
			, '".addslashes($h_bd_at)."', '".addslashes($h_bd_a)."'
			, '".addslashes($h_bd)."', '$m_wd'
			, '$m_new', '$m_lth'
			, '$m_inp' , '".addslashes($f_bd_a)."'
			, '".addslashes($f_bd)."', '$fl_ms'
			, '$fl_mn' , '$fl_awf'
			, '".addslashes($ft)."'	,'".addslashes($ft_msg)."')

			") or die(mysql_error());
		}

		REQUIRE_ONCE("./scheme.sql");

		MYSQL_QUERY("$board_table") OR DB_ERR(__FILE__."-".__LINE__,"메인 게시판 테이블 생성에러");
		MYSQL_QUERY("$first_article") OR DB_ERR(__FILE__."-".__LINE__,"가상게시물 삽입 에러");
		MYSQL_QUERY("$idx_table") OR DB_ERR(__FILE__."-".__LINE__,"보조 테이블 생성 에러");
		MYSQL_QUERY("$second_article") OR DB_ERR(__FILE__."-".__LINE__,"가상게시물 삽입 에러");
		MYSQL_QUERY("$comment_table") OR DB_ERR(__FILE__."-".__LINE__,"코멘트 테이블 생성 에러");

		go($PHP_SELF,"");

	exit;
	CASE (deleteall):

		$count=count($cart);
		if($count<1) {
			go("","선택한 항목이 없습니다");
		} else {
			while(list($value,$label)=each($cart)) {

				$z=mysql_fetch_array(MYSQL_QUERY("SELECT count(*) FROM msb_".$label." WHERE fn!=''"));//업로드된 파일이 있는 레코드 갯수를 구함

				if($z[0]>0) {//갯수가 1개 이상이면 업로드 데이터가 있으므로 지운다.
					$c=MYSQL_QUERY("SELECT w_d,fn,fx FROM msb_".$label." WHERE fn!='' ORDER BY no asc");//업로드된 파일이 있는 레코드를 구함
					FOR($x=0;$x<$z[0];$x++) {
						$v=mysql_fetch_array($c);
						$v[1]=explode(",",$v[1]);
						$v[2]=explode(",",$v[2]);
						if(count($v[1])>1) {//upload 필드 복수의 데이터가 있음
							FOR($n=0;$n<count($v[1]);$n++) {
								unlink("./data/".$v[0]."/".$v[1][$n].".".$v[2][$n]);
							}
							rmdir("./data/".$v[0]);
						} else {//upload 필드에 한개의 데이터가 있음
							unlink("./data/".$v[0]."/".$v[1][0].".".$v[2][0]);
						}
						rmdir("./data/".$v[0]);
					}
				}

				MYSQL_QUERY("DELETE FROM $table2 WHERE id='".$label."'") OR go("","게시판 설정 삭제중에 에러가 발생했습니다");
				MYSQL_QUERY("DROP table msb_".$label) OR go("","msb_".$label."(게시판 본체)테이블을 Drop 하는 중에 에러가 발생했습니다");
				MYSQL_QUERY("DROP table msbi_".$label) OR go("","msbi_".$label."(보조 테이블)테이블을 Drop 하는 중에 에러가 발생했습니다");
				MYSQL_QUERY("DROP table msbc_".$label) OR go("","msbc_".$label."(코멘트 테이블)을 Drop 하는 중에 에러가 발생했습니다");
			}
		}

		go($PHP_SELF,"");

	exit;
	CASE (modify_ok):

		check_adv();

		$opr=trim($opr);
		$h_sc=trim($h_sc);
		$fl_ms=trim($fl_ms);
		$fl_awf=trim($fl_awf);
		$ft=trim($ft);
		$ft_msg=trim($ft_msg);
		
		$res=MYSQL_QUERY("UPDATE ".$table2." SET
			id='$id', skin='$skin'
			, opr='$opr', cg='".addslashes($cg)."'
			, h_tt='".addslashes(trim($h_tt))."'
			, h_sc='".addslashes($h_sc)."', h_bd_at='".addslashes($h_bd_at)."'
			, h_bd_a='".addslashes($h_bd_a)."', h_bd='".addslashes($h_bd)."'
			, m_wd='$m_wd', m_new='$m_new'
			, m_lth='$m_lth',m_inp='$m_inp'
			, f_bd='".addslashes($f_bd)."',f_bd='".addslashes($f_bd)."'
			, fl_ms='$fl_ms',fl_mn='$fl_mn'
			, fl_awf='$fl_awf',ft='".addslashes($ft)."'
			, ft_msg='".addslashes($ft_msg)."'
			
			WHERE id='".$id."'");

		if($res) go($PHP_SELF,"");
		else go("","게시판 설정 업데이트 중에 에러가 발생했습니다");

	exit;
	CASE ("empty"):

		if(!$id) go("","게시판 이름을 지정하세요");
		echo ("
			<SCRIPT>
			if(!confirm('정말로 지우시겠습니까?')) {
				history.back();
			} else {
				location.href='".$PHP_SELF."?id=$id&bo=emptyp'
			}
			</SCRIPT>");

	exit;
	CASE (emptyp):

		if(!$id) go("","게시판 이름을 지정하세요");

		$result=MYSQL_QUERY("SELECT w_d,fn,fx,ft FROM $table1 WHERE fn!='' and ft!='' and fs!=''");//업로드된 파일이 있는 레코드 갯수를 구함
		$result2=mysql_affected_rows();


		echo $result2;
		if($result2>0) {//갯수가 1개 이상이면 업로드 데이터가 있으므로 지운다.

			FOR($i=0;$i<$result2;$i++) {
				$d=MYSQL_FETCH_ARRAY($result);
				$path="./data/".$d[0]."/";
				$d[1]=explode(",",$d[1]);
				$d[2]=explode(",",$d[2]);
				if(count($d[1])>1) {//upload 필드 복수의 데이터가 있음
					FOR($n=0;$n<count($d[1]);$n++) {
						chmod($path.$d[1][$n].".".$d[2][$n],"707");
						unlink($path.$d[1][$n].".".$d[2][$n]);
					}
				} else {//upload 필드에 한개의 데이터가 있음

					if( file_exists($path.$d[1][0].".".$d[2][0]) ) {
						chmod( $path.$d[1][0].".".$d[2][0] ,"707");
						unlink( $path.$d[1][0].".".$d[2][0] );
					}
				}

				unset($d);

				if(is_dir($path)) {
					rmdir($path);
				}

			}
		}
		
		MYSQL_QUERY("DROP TABLE msb_".$id) OR go("","msb_".$id."(게시판본체)테이블 삭제 에러\\n파일 이름:".$PHP_SELF." LINE :".__LINE__);
		MYSQL_QUERY("DROP TABLE msbi_".$id) OR go("","msbi_".$id."(보조테이블)테이블 삭제 에러\\n파일 이름:".$PHP_SELF." LINE :".__LINE__);
		MYSQL_QUERY("DROP TABLE msbc_".$id) OR go("","msbc_".$id."(코멘트테이블)테이블 삭제 에러\\n파일 이름:".$PHP_SELF." LINE :".__LINE__);

		REQUIRE_ONCE("./scheme.sql");

		MYSQL_QUERY("$board_table") OR go(__FILE__."-".__LINE__,"msb_".$id."(게시판본체)테이블 생성 에러");
		MYSQL_QUERY("$first_article") OR go(__FILE__."-".__LINE__,"가상게시물 삽입 에러");
		MYSQL_QUERY("$idx_table") OR go(__FILE__."-".__LINE__,"msbi_".$id."(보조테이블)테이블 생성 에러");
		MYSQL_QUERY("$second_article") OR DB_ERR(__FILE__."-".__LINE__,"가상게시물 삽입 에러");
		MYSQL_QUERY("$comment_table") OR go(__FILE__."-".__LINE__,"msbc_".$id."(코멘트테이블)테이블 생성 에러");

		echo $path."<br>";

		go($PHP_SELF."?page=".$page,"");

	exit;
	CASE (edit):

		$result=gboard();
		HEAD_ADMIN();
	?>

		<script language=javascript>
		function admin_check() {
//			if( document.form.cg.value=='' ) {
//				if( document.form.p_cg[1].checked==true ) {
//					if(confirm(' 카테고리를 사용하시겠습니까? ')) {//
//						document.form.cg.focus();
//						return false;
//					} else {
//						document.form.p_cg[1].checked=false;
//						document.form.p_cg[0].checked=true;
//						return true;
//					}
//				}
//			}
//
//			if( document.form.p_up.value<1 && document.form.fl_mn.value>0 ) {
//				alert('업로드를 하시려면 업로드 권한과 업로드 갯수를 지정하세요');
//				return false;
//			}

			return true;

		}
		</script>
		<form name=form onsubmit="return admin_check();" action="<?=$PHP_SELF?>" method=post>
		<input type=hidden name=bo value='<?=$act?>_ok'>
		<table border=0 cellpadding=1 cellspacing=0 width=100% style="border:solid 1 #333333">
			<tr bgcolor=333333>
				<td colspan=2 align=center style="font-size:20pt;colOR:#DDDDDD"><b><?=$act?> The BOARD</td>
			</tr>
			<tr bgcolor=#EEEEEE>
				<td width=26% align=right><b><font size=1>Board Name</font></b>&nbsp;</td>
				<td width=74%><input type=text class=input <? if($act=="modify"){echo("readonly");}?> name=id size=16 style="font-weight:bold" value='<?=$result[0]?>'>
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Skin</font>&nbsp;</td>
				<td>
					<select name=skin>
				<?
				if(!is_dir("./skin")) {
					mkdir("./skin",0755);
				}
				$handle=opendir("./skin");
				while($file=readdir($handle)) {
					if(!eregi("\.",$file)) {
						if($file==$result[1]) {
							echo ("
						<option value='$file' selected>$file</option>
								");
						} else {
							echo ("
						<option value='$file'>$file</option>
								");
						}
					}
				}
				closedir($handle);
				flush();
				?>
					</select><br>
		게시판의 옷입니다.
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>

			<tr>
				<td align=right><font size=1 title='게시판 관리자' alt='게시판 관리자'>BBS <b>Administrator</b></font>&nbsp;</td>
				<td><input type=text name=opr class=input size=16 style="font-weight:bold" value='<?=$result[2]?>'><br>
		멤버넘버를 적으시면 됩니다.<br>
		한 명만 설정하실 수 있습니다.
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>

			<tr>
				<td align=right><font size=1>Title</font>&nbsp;</td>
				<td><input type=text class=input name=h_tt size=50 value='<?=$result[33]?>'><br>
		문서의 제목을 지정합니다.
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Header <b>JavaScript</font>&nbsp;</td>
				<td><textarea name=h_sc style="width:350;height:200;border:solid 1 #333333" cols=30 rows=5><?=$result[34]?></textarea></td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Header <b>BodyAttributes</b></font>&nbsp;</td>
				<td><textarea name=h_bd_at style="width:350;height:40;border:solid 1 #333333" maxlength=200 cols=15 rows=5><?=stripslashes($result[35])?></textarea><br>
		&lt;body> 에 들어가는 속성을 지정합니다.<br>
		예 ) background=image/bg.gif bgcolor=olivegreen</td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Header <b>BodyAddress</b></font>&nbsp;</td>
				<td><input type=text class=input name=h_bd_a size=40 align=absmiddle value='<?=$result[36]?>'> ( 에러위험있음 )</td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Header <b>Body</b></font>&nbsp;</td>
				<td>헤더기능<br>
			<textarea name=h_bd style="width:350;height:200;border:solid 1 #333333" cols=30 rows=5><?=$result[37]?></textarea></td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Footer <b>BodyAddress</b></font>&nbsp;</td>
				<td><input type=text class=input name=f_bd_a size=40 align=absmiddle value='<?=$result[42]?>'> ( 에러위험있음 )</td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Footer <b>Body</b></font>&nbsp;</td>
				<td>푸터기능<br>
		<textarea name=f_bd style="width:350;height:200;border:solid 1 #333333" cols=30 rows=5><?=$result[43]?></textarea>
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>

			<tr>
				<td align=right><font size=1>Category</font>&nbsp;</td>
				<td><textarea name=cg style="width:350;height:50;border:solid 1 #333333"><?=$result[3]?></textarea><br>
			여러개일 경우 콤마(,) 로 구분합니다.
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Width</font>&nbsp;</td>
				<td><input type=text class=input name=m_wd size=8 maxlength=5 value='<?=$result[38]?>'><br>
		게시판 본체의 가로길이를 지정합니다.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Articles per a page</font>&nbsp;</td>
				<td><input type=text class=input name=m_inp size=8 value='<?=$result[41]?>'><br>
		글 목록에 나타날 게시물의 갯수를 지정합니다.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Length</font>&nbsp;</td>
				<td><input type=text class=input name=m_lth size=5 value='<?=$result[40]?>'><br>
		제목의 길이가 너무 길때 제한하는 길이입니다.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Time Line</font>&nbsp;</td>
				<td><input type=text class=input name=m_new size=5 value='<?=$result[39]?>'> hours<br>
		신규글이나 오래된 글을 구분하는 시간입니다.<br>
		글쓴지 얼마나 지났을 때 오래된 글인지 지정합니다.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Letter <b>filter</b></font>&nbsp;</td>
				<td>등록하고 싶지 않은 말을 지정합니다. ( 콤마, 로 구분합니다 )<br>
					<textarea name=ft style="width:350;height:200;border:solid 1 #333333" cols=30 rows=5><?=$result[47]?> </textarea><p>
					저 위의 낱말을 입력할 경우 대체할 메세지를 지정합니다.<br>
					비어놓을 경우 대신 에러메세지가 뜹니다. ( "***는 입력하실 수 없는 말입니다" )<br>
					<input type=text class=input name=ft_msg size=20 maxlength=100 align=absmiddle value='<?=$result[48]?>'>
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Max <b>FileSize</b></font>&nbsp;</td>
				<td><input type=text class=input name=fl_ms size=10 value='<?=$result[44]?>'> Bytes<br>
		업로드될 파일 각각의 크기를 제한하는 수치입니다.<br>
		<font size=1>1MB = 1048576 Bytes , 2MB = 2097152 Bytes</font>
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Max <b>FileNum</b></font>&nbsp;</td>
				<td><input type=text class=input name=fl_mn size=10 value='<?=$result[45]?>'><br>
		업로드할 파일 갯수 0 으로 하면 업로드를 받지 않습니다.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Allow <b>FileFormat</b></font>&nbsp;</td>
				<td><input type=text class=input name=fl_awf size=30 value='<?=$result[46]?>'><br>
		업로드할 때 허용할 파일 확장자를 지정합니다.<br>
		콤마(<b>,</b>)로 구분합니다.<br>
		예를들어 JPG 파일과 GIF 파일만을 받고 싶을 때는<br>
		( jpg,gif ) 라고 적어주시면 됩니다.<br>
		단, 공백으로 남겨두었을 시 확장자 검사를 하지 않습니다.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td></td>
				<td><input type=submit value=Submit class=confirm>&nbsp;&nbsp;<input type=button class=confirm onclick="history.back()" value=Back>
				</td>
			</tr>
		</table>
		</form>

	<?
		FOOT_ADMIN();

	exit;
	CASE (permission): // 게시판 권한 설정

		$result=gboard();
		HEAD_ADMIN();

	echo('<table border=0 cellpadding=2 cellspacing=0 width=100% style="border:solid 1 #333333">
			<tr bgcolor=333333>
				<td colspan=2 align=center style="font-size:20pt;colOR:#DDDDDD"><b>'.$act.' The BOARD</td>
			</tr>
			<tr>
				<td colspan=2 align=center style="font-size:11px"><b>Setting Permission : '.$id.'</b><br><br>
		1(최고관리자)부터 8까지는 가입한 회원이며 9는 가입하지 않은 게스트입니다.<br>&nbsp;
				</td>
			</tr>
			<FORM action='.$PHP_SELF.' method="post">
			<input type=hidden name=id value='.$id.'>
			<input type=hidden name=bo value="permission_ok">
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 목록 보기&nbsp;</td>
				<td>');
			showg("p_list",9,$result[6]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 내용 보기&nbsp;</td>
				<td>');
			showg("p_view",9,$result[7]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>오토 이미지 허용여부&nbsp;</td>
				<td>');
			showg("p_al",2,$result[8],"No,Yes");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>링크 사용여부&nbsp;</td>
				<td>');
			showg("p_l",2,$result[9],"사용안함,1개,2개");
			echo('<br></td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>HTML 허용여부&nbsp;</td>
				<td>');
			showg("p_h",9,$result[10]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>앞글 이전글 보기&nbsp;</td>
				<td>');
			showg("p_np",2,$result[13],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>답글관계 보기&nbsp;</td>
				<td>');
			showg("p_vr",2,$result[12],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 내용볼 때 밑에 리스트&nbsp;</td>
				<td>');
			showg("p_vl",2,$result[11],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>코멘트 쓰기&nbsp;</td>
				<td>');
			showg("p_cw",9,$result[14]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>코멘트 지우기&nbsp;</td>
				<td>');
			showg("p_cd",9,$result[15]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>카테고리 사용 여부&nbsp;</td>
				<td>');
			showg("p_cg",2,$result[5],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 작성하기&nbsp;</td>
				<td>');
			showg("p_write",9,$result[16]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>답글 메일 사용&nbsp;</td>
				<td>');
			showg("p_rm",2,$result[17],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>답글 달기&nbsp;</td>
				<td>');
			showg("p_reply","9",$result[18]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>갤러리 사용&nbsp;</td>
				<td>');
			showg("p_gl",9,$result[19]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 수정하기&nbsp;</td>
				<td>');
			showg("p_modify",9,$result[20]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 삭제&nbsp;</td>
				<td>');
			showg("p_delete",9,$result[21]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>특수 태그 사용&nbsp;</td>
				<td>');
			showg("p_st",9,$result[22]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>비밀글 쓰기&nbsp;</td>
				<td>');
			showg("p_sc",9,$result[23]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>공지사항&nbsp;</td>
				<td>');
			showg("p_nt",9,$result[24]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 프린트&nbsp;</td>
				<td>');
			showg("p_print",2,$result[30],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>글 복사&nbsp;</td>
				<td>');
			showg("p_copy",9,$result[32]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>업로드 허용&nbsp;</td>
				<td>');
			showg("p_up",9,$result[25]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>다운로드 허용&nbsp;</td>
				<td>');
			showg("p_dn",9,$result[26]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>멤버가입 허용&nbsp;</td>
				<td>');
	if($result[27]==9) $check[0]='checked';
	else $check[1]='checked';
			echo('
		<input type=radio name=p_join value=0 '.$check[0].'>Not use
		<input type=radio name=p_join value=9 '.$check[1].'>Use
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>멤버들의 로긴 허용&nbsp;</td>
				<td>');
			showg("p_login",9,$result[28]);
			echo(' 이하 로그인 제한</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>멤버 정보수정&nbsp;</td>
				<td>');
			showg("p_loged",2,$result[29],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>멤버 비번찾기&nbsp;</td>
				<td>');
			showg("p_find",2,$result[31],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td></td>
				<td><input type=submit value=Submit class=confirm>&nbsp;&nbsp;<input type=button class=confirm onclick="history.back()" value=Back>
				</td>
			</tr>
			</form>
		</table>');

		FOOT_ADMIN();


	exit;
	CASE (backup): // 게시판 백업

		FUNCTION get_field_name($str1) {//STR1 is the table name

			$result = MYSQL_QUERY("DESCRIBE ".$str1); 
			$end = mysql_num_rows($result);

			$result = MYSQL_QUERY("SHOW FIELDS FROM ".$str1) or die("Query Error");

			for($i=0;$i<$end;$i++) {
				$d=mysql_fetch_array($result);

				$result2 .= $d[0];

				if($i+1!=$end) {
					$result2 .=",";
				}
			}

			return $result2;
		}

		FUNCTION get_insert_data($str1) {//$str1 은 테이블 이름
			Global $result,$dt;

			$result2=MYSQL_QUERY("SELECT * FROM ".$str1) or DB_ERR(__FILE__."-".__LINE__);

			WHILE($d=mysql_fetch_array($result2)) {
				echo ("INSERT INTO ".$str1." (".get_field_name($str1).") VALUES(");

				FOR($i=0;$i<mysql_num_fields($result2);$i++) {
					$d[$i]=addslashes($d[$i]);
					$d[$i]=eregi_replace("\n","\\n",$d[$i]);
					$d[$i]=eregi_replace("\r","\\r",$d[$i]);
					$d[$i]=eregi_replace("\t","\\t",$d[$i]);
					$d[$i]=eregi_replace(",$","",$d[$i]);

					if( $dt=="2" AND eregi("primary_key",mysql_field_flags($result2,$i)) ) {
						$d[$i]="";
					}

					echo "'".$d[$i]."'";

					if($i+1!=mysql_num_fields($result2)) {
						echo ",";
					}
				}
							
				echo (" );");
				echo ("\r\n");
			}
		}

		FUNCTION get_update_data($str1,$str2=false) {//$str1 은 테이블 이름
			Global $result;

			MYSQL_QUERY("LOCK TABLES ".$str1." WRITE") OR go("","Lock tables 에러");

			if($str1=="msbadmin" AND $str2!="") {
				$result2=MYSQL_QUERY("SELECT * FROM msbadmin WHERE id='test'") or DB_ERR(__FILE__."-".__LINE__);
			} else {
				$result2=MYSQL_QUERY("SELECT * FROM ".$str1) or DB_ERR(__FILE__."-".__LINE__);
			}

			WHILE($d=MYSQL_FETCH_ARRAY($result2)) {
				echo ("UPDATE ".$str1." SET ");

				for($i=0;$i<mysql_num_fields($result2);$i++) {
					$d[$i]=addslashes($d[$i]);
					$d[$i]=eregi_replace("\n","\\n",$d[$i]);
					$d[$i]=eregi_replace("\r","\\r",$d[$i]);
					$d[$i]=eregi_replace("\t","\\t",$d[$i]);
					$d[$i]=eregi_replace(",$","",$d[$i]);

					echo mysql_field_name($result2,$i)."='".$d[$i]."'";

					if($i+1!=mysql_num_fields($result2)) {
						echo ",";
					}
				}
							
				echo (" WHERE id='".$d[0]."' ;");
				echo ("\r\n");
			}

			MYSQL_QUERY("UNLOCK TABLES");
		}

		if(!$dt) {
			go("","원하는 data 형태을 선택해 주세요");
			exit;
		}

		header("Content-disposition: filename=msb_".date("ymd").".sql");
		header("Content-type: application/octetstream");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo ("
## MSBBS ".date("Ymd")."\n\n\n");

		WHILE(list($value,$label)=@each($table)) {
			$id=$label;

			if($id!="") {// 게시판 이름이 지정되었으므로 게시물 백업을 실행
				MYSQL_QUERY("LOCK TABLES msb_".$id." WRITE, msbi_".$id." WRITE, msbc_".$id." WRITE") or go("",mysql_error());
				if($drop=="on" and $structure=="on") {
					echo("

DROP TABLE IF EXISTS msb_".$id.";");
					echo("
DROP TABLE IF EXISTS msbi_".$id.";");
					echo("
DROP TABLE IF EXISTS msbc_".$id.";
");
				}

				if($structure=="on") {
					REQUIRE_ONCE("scheme.sql");
					echo("
$board_table

$idx_table

$comment_table
");
				}

				if($structure=="" and $data=="on") {
					echo("
$first_article

$second_article
");
				}

				get_update_data("msbadmin","$id");

				if($data=="on") {
					echo ("
## Table Name : msb_".$id."

");
					if($dt=="3") {
						get_update_data("msb_".$id);
					} else {
						get_insert_data("msb_".$id);
					}
					echo ("
## Table Name : msbc_".$id."

");
					if($dt=="3") {
						get_update_data("msbc_".$id);
					} else {
						get_insert_data("msbc_".$id);
					}
					echo ("
## Table Name : msbi_".$id."

");
					if($dt=="3") {
						get_update_data("msbi_".$id);
					} else {
						get_insert_data("msbi_".$id);
					}
				}
				MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
			} else {
				MYSQL_QUERY("LOCK TABLES msbadmin WRITE, msbmem WRITE") or DB_ERR(__FILE__."-".__LINE__);

				echo ("
## Table Name : msbadmin

");
				if($dt=="3") {
					get_update_data("msbadmin");
				} else {
					get_insert_data("msbadmin");
				}
				echo ("
## Table Name : msbmem

");
				if($dt=="3") {
					get_update_data("msbmem");
				} else {
					get_insert_data("msbmem");
				}
				MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
			}

			echo ("\n\n\n");
		}

	exit;
	CASE (m_delete):

		$count=count($cart);
		if($count<1) {
			go("","선택한 항목이 없습니다");
		} else {
			while(list($value,$label)=each($cart)) {
				$level=@mysql_result(MYSQL_QUERY("SELECT count(*) FROM $table3 WHERE lv='1'"),0,0);
				$userinfo=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT nm,lv FROM $table3 WHERE no='$label'"));
				if($userinfo[1]=="1" and $level[0]<"2") { go("",$userinfo[0]."님을 삭제할 수 없습니다"); }
				MYSQL_QUERY("DELETE FROM $table3 WHERE no='$label'") or DB_ERR(__FILE__."-".__LINE__);
			}
		}
		go($PHP_SELF."?bo=member","작업 완료");

	exit;
	CASE (grade_chg):

		$count=count($cart);
		if($count<1) {
			go("","선택한 항목이 없습니다");
		} else {
			while(list($value,$label)=each($cart)) {
				$level=@mysql_result(MYSQL_QUERY("SELECT count(*) FROM $table3 WHERE lv='1'"),0,0);
				$userinfo=mysql_fetch_array(MYSQL_QUERY("SELECT * FROM $table3 WHERE no='$label'"));

				if($userinfo[3]=="1" and $level[0]<"2" and $grade>"1") { go("",$userinfo[5]."님의 레벨을 낮출 수 없습니다"); }

				MYSQL_QUERY("UPDATE $table3 SET lv='$grade' Where no='$label'") or DB_ERR(__FILE__."-".__LINE__);
			}
		}
		go($PHP_SELF."?bo=member","작업 완료");

	exit;
	CASE (sendmail):

		$result=mysql_result(MYSQL_QUERY("SELECT count(*) FROM $table3"),0,0);

		HEAD_ADMIN("sendmail");

		REQUIRE_ONCE("./scripts/sendmail.php");

echo('
<script language=javascript>
function check() {
	if(!document.send.fromname.value) {
		document.send.fromname.focus()
		alert("이름을 입력하세요")
		return false;
	}
	if(!document.send.from.value) {
		document.send.from.focus()
		alert("메일 주소를 입력하세요")
		return false;
	}
	if(!document.send.subject.value) {
		document.send.subject.focus()
		alert("제목을 입력하세요")
		return false;
	}
	if(!document.send.memo.value) {
		document.send.memo.focus()
		alert("내용을 입력하세요")
		return false;
	}
	return true;
}
</script>
<form name=send onsubmit="return check();" method=post action="'.$PHP_SELF.'">
<input type=hidden name=bo value=sendmail_go>
<tr bgcolor=#333333>
	<td colspan=2>
	<table border=0 cellpadding=5 cellspacing=0>
	<tr>
		<td>
		<font color=white>메일링리스트를 쓰시려면 서버가 Sendmail 을 지원해야합니다</font>
		</td>
	</tr>
	</table>
	</td>
</tr>
<TR>
	<TD align=center>
		<TABLE border=0 cellpadding=3 cellspacing=0 width=100%>
			<tr>
				<td width=20% align=right>Name</td>
				<td><input class=input type=text name=fromname value='.$member[5].'></td>
			</tr>
			<tr>
				<td width=20% align=right>Your E-mail</td>
				<td><input type=text name=from value='.$member[6].'>
				</td>
			</tr>
			<tr>
				<td width=20% align=right>To</td>
				<td><SELECT name=level>
						<option value=1>1</option>
						<option value=2>2</option>
						<option value=3>3</option>
						<option value=4>4</option>
						<option value=5>5</option>
						<option value=6>6</option>
						<option value=7>7</option>
						<option value=8>8</option>
						<option value=9>9</option>
					</SELECT>
					<SELECT name=updown>
						<option value=1>이하</option>
						<option value=2>이상</option>
						<option value=3>선택레벨</option>
					</SELECT>&nbsp;
					전체 멤버수(관리자포함) : '.$result.'
				</td>
			</tr>
			<tr>
				<td align=right>To(Selected Few)</td>
				<td><input type=text name=toselect class=input></td>
			</tr>
			<tr>
				<td width=20% align=right>Subject</td>
				<td><input type=text name=subject size=40 class=input></td>
			</tr>
			<tr>
				<td colspan=2 align=center><textarea cols=80 rows=18 name=memo></textarea>
				</td>
			</tr>
			<tr>
				<td align=right>Attachment</td>
				<td><input type=file name=userfile size=40 class=input></td>
			</tr>
			<tr><td></td>
				<td><input type=submit value=" Submit " class=confirm>
				<input class=confirm type=button value=" Close " onclick=window.close()></td>
			</tr>
		</table>
	</td>
</tr>
</FORM>
</table>');

		FOOT_ADMIN("sendmail");

	exit;
	CASE (sendmail_go):

		/**************************************************************
		FROM PHPSCHOOL.com ㅎㅎㅎㅎㅎㅎ ToT;;
		**************************************************************/

		/* 스크립트 실행 제한 0으로.. */
		@set_time_limit(0);

		if(!$fromname) {
			go("","이름을 입력하세요");
		}
		if(!$from) {
			go("","E-mail 주소를 입력하세요");
		}
		if(!$subject) {
			go("","제목을 입력하세요");
		}
		if(!$memo) {
			go("","내용을 입력하세요");
		} else {
			$memo=nl2br($memo);
		}

		$body=$memo;

		if($updown=="1") $updown="<=";//이하
		elseif($updown="2") $updown=">=";//이상
		else $updown="=";//선택레벨

		$result=MYSQL_QUERY("SELECT eml FROM $table3 WHERE lv".$updown."'".$level."'") or DB_ERR(__FILE__."-".__LINE__);

		if(mysql_affected_rows()<1) go("","선택된 멤버가 없습니다");

		REQUIRE_ONCE("./scripts/sendmail.php");

		WHILE($d=mysql_fetch_array($result)) {
			if(eregi("\@",$d[email])) {
				if(!@nmail($d[email],$subject,$body,$headers)) {
					go("","Sendmail 을 지원하지 않는 듯 합니다");
				} else {
					echo ".";
				}
			}
			$d="";
		}

		go("javascript:window.close()","메일을 다 보냈습니다.");

	exit;
	CASE (member):

		HEAD_ADMIN();

		if($page=='') $page=1;
		if(!$level OR !isset($level)) $level="10";
		if($act=="search" OR $act=="zoom") {
			if($si=="on") {
				$temp[]="id like '%$keyword%'";
				$check[1]="checked";
			}
			if($sn=="on") {
				$temp[]="name like '%$keyword%'";
				$check[2]="checked";
			}
			if($se=="on") {
				$temp[]="email like '%$keyword%'";
				$check[3]="checked";
			}
		}
		if($level!="" AND $level<"10") {
			$temp[]="lv='".$level."'";
		}
		if($r_d!="" AND $r_d>0) {
			$result=explode(",",date("Y,m,d",$r_d));
			$result2[0]=mktime("0","0","0",$result[1],$result[2],$result[0]);
			$result2[1]=mktime("0","0","0",$result[1],($result[2]+1),$result[0]);
			unset($result);

			$temp[]="r_d>$result2[0] and r_d<$result2[1]";
		}
		if($bid!="") {
			$temp[]="bid like '%".$bid."%'";
		}
		if($desc!="desc" and $desc!="asc") {
			$desc="desc";
		}
		if($order!="") {
			if($order!="join" and $order!="id" and $order!="name") {
				$order="ORDER BY no ".$desc;
			} else {
				$order="ORDER BY $order ".$desc;
			}
		} else {
			$order="ORDER BY no ".$desc;
		}

		if(count($temp)>0) {
			$temp=" WHERE ( ".implode(" OR ",$temp)." ) ";
		} else {
			$temp="";
		}

		$temp.=" ".$order;
		unset($order);

		$result=MYSQL_QUERY("SELECT no,bid,id,nm,lv,r_d FROM ".$table3." ".$temp." LIMIT ".(($page-1)*10).",10") or DB_ERR(__FILE__."-".__LINE__);

		//total record
		$trec=@MYSQL_AFFECTED_ROWS();
		//total page
		$tpage=@ceil($trec/10);

echo('
<script language=javascript>
function sendmail() {
	window.open("'.$PHP_SELF.'?bo=sendmail","mswin","left=0,top=0,marginwidth=0,marginheight=0,width=616,height=500,scrollbars=yes,status=yes");
}
</script>
<table width=600 border=0 cellpadding=2 cellspacing=0>
	<form action='.$PHP_SELF.' method=post name=list>
	<input type=hidden name=bo value="">
	<tr>
		<td colspan=8 align=center style="font-size:17pt"><b>MEMBER MANAGEMENT</b></td>
	</tr>
	<tr>
		<td align=left colspan=3><font size=1>Total Number of Members : '.$trec.'</font></td>
		<td align=right colspan=5><input type=button value=" Sendmail " class=confirm onclick=sendmail()></td>
	</tr>
	<tr bgcolor=#EFEFEF>
		<td width=20 align=center><a href=# onclick=reverse();>C</a></td>
		<td width=60 align=center><font size=1>No</td>
		<td width=155><font size=1>lv: Id</td>
		<td width=255><font size=1>Name</td>
		<td width=20 align=center><font size=1>Join</td>
		<td width=45></td>
		<td width=45></td>
	</tr>
	<tr height=1 bgcolor="#EEEEEE"><td colspan=10></td></tr>');

		//print list
		while($d=@mysql_fetch_array($result)) {
			if($d[0]!="") {
				$d[7]=stripslashes($d[6]);
				$d[6]=stripslashes($d[7]);

				if($d[4]==1) $d[15]="blue";//레벨의 색깔
				elseif($d[4]==9) $d[15]="red";
				else $d[15]="black";

echo('
	<tr bgcolor=white>
		<td NOWRAP align=center><input type=checkbox name=cart['.$i.'] value='.$d[0].'></td>
		<td NOWRAP align=center><font size=1>'.$d[0].'</td>
		<td NOWRAP><a href='.$PHP_SELF.'?bo=member&act=zoom&level='.$d[4].'><font color='.$d[15].'>'.$d[4].'</font></a>: '.$d[2].'</td>
		<td NOWRAP>'.$d[3].'</td>
		<td><a href='.$PHP_SELF.'?bo=member&act=zoom&r_d='.$d[5].'><font size=1>'.date("m/d",$d[5]).'</td>
		<td align=center><a href='.$PHP_SELF.'?bo=member&bid='.$d[1].'><font title="가입한 게시판">'.$d[1].'</font></a></td>
		<td align=center><a href=# onclick=\'window.open("'.$PHP_SELF.'?bo=m_info&no='.$d[0].'","mswin","left=0,top=0,marginwidth=0,marginheight=0,width=416,height=485,scrollbars=yes,statusbar=yes");return true;\'><font size=1>Modify</a></td>
	</tr>
	<tr height=1 bgcolor=eeeeee><td colspan=10></td></tr>');

		unset($d);
		}
	}

echo('
	<tr>
		<td align=center colspan=10>');

		if($page > 1) echo ("<a href=# onclick=page(".($page-1).")>[Prev]</a>...");

		for($i=$page-5 ; $i<=$page+5 ; $i++) {
			if($i == $page and $i>0) echo (" <b>$i</b> ");
			elseif($i>0 and $i<=$tpage) echo (" <a href=# onclick=page(".$i.")>[".$i."]</a> ");
		}

		if($page<$tpage) echo ("...<a href=# onclick=page(".($page+1).")>[Next]</a>");

echo('
		</td>
	</tr>
	<tr height=20><td></td></tr>
	<tr>
		<td colspan=8 align=right>

		<table border=0 width=250 cellpadding=1 cellspacing=0 style="border:solid 1 #BBBBBB">
			<tr>
				<td align=right>
				<input type=button onclick=mexec("m_delete") class=confirm_s value=" Delete ">
				<font size=1>Grade
				<select name=grade class=input>
					<option value=1>1</option>
					<option value=2>2</option>
					<option value=3>3</option>
					<option value=4>4</option>
					<option value=5>5</option>
					<option value=6>6</option>
					<option value=7>7</option>
					<option value=8>8</option>
					<option value=9>9 - 비멤버</option>
<!--				<option value=10>선택안함</option>-->
				</select>
				<input type=button onclick=mexec("grade_chg") class=confirm_s value=" APPLY ">
				</td>
			</tr>
			</form>
			<tr height=1 bgcolor=#BBBBBB><td></td></tr>
			<form method=post action='.$PHP_SELF.'>
			<input type=hidden name=bo value=member>
			<input type=hidden name=act value=search>
			<tr bgcolor=#EFEFEF>
				<Td align=right><font size=1>
				<input type=checkbox name=si '.$check[1].'> ID
				<input type=checkbox name=sn '.$check[2].'> Name
				<input type=checkbox name=se '.$check[3].'> Email
				<select name=level class=input>
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
				<option value=6>6</option>
				<option value=7>7</option>
				<option value=8>8</option>
				<option value=9>9</option>
				<option value=10 selected>선택안함</option>
				</select> level
				</td>
			</tr>
			<tr bgcolor=#EFEFEF>
				<td align=right>
				<input type=text name=keyword class=input size=15 value='.$keyword.'>
				<input type=submit class=confirm_s value=" Find ">
				<input type=button value=" Reset " onclick=location.href="'.$PHP_SELF.'?bo=member&act=&si=&sn=&se&keyword=&level=10&" class=confirm_s>
				</td>
			</tr>
		</table>

		</td>
		</form>
	</tr>
</table><br><br>');

		FOOT_ADMIN();

	exit;
	DEFAULT:

	HEAD_ADMIN();

	if(!$page) $page=1;
	$res=MYSQL_QUERY('SELECT id FROM '.$table2.' LIMIT '.(($page-1)*10).',10') or DB_ERR(__FILE__.'-'.__LINE__,'게시판 목록 로드중 에러');

	//total record
	$trec=mysql_result(MYSQL_QUERY('SELECT count(*) FROM '.$table2),0,0);
	//total page
	$tpage = ceil($trec/10);
	//remain page
	$rpage = ceil($trec%10);

	//piece the page
	$pif = (($page-1)*10)+1;
	$pil = $page*10;

	//virtual article number
	$artical_num=$trec-(($page-1)*10);

echo('		
		<table width=100% border=0 cellpadding=2 cellspacing=0>
			<tr>
				<td colspan=2 align=center style="font-size:18pt;color:555555"><b>BOARD MANAGEMENT</b>
				</td>
			</tr>
			<tr>
				<td align=left valign=middle><font size=1>Total Number of Boards : '.$trec.'</font></td>
				<td align=right><input type=button value=" Make A Board " class=confirm onclick=location.href="'.$PHP_SELF.'?bo=edit&act=create"></td>
			</tr>
			<tr>
				<td colspan=2 align=center>
				<table border=0 width=100% cellpadding=3 cellspacing=0>
				<form action='.$PHP_SELF.' method=post name=list>
				<input type=hidden name=bo value="">
				<tr bgcolor=#F0F0F0>
					<td width=30 align=center><a href=# onclick=reverse()><font size=1>C</a></td>
					<td><font size=1>name</td>
					<td width=70 align=center><font size=1>Total Articles</td>
					<td width=30 align=center><font size=1>Empty</td>
					<td width=35 align=center><font size=1>Optimize</font></td>
					<td width=30 align=center><font size=1>Repair</font></td>
					<td width=30 align=center><font size=1>Modify</font></td>
					<td width=45 align=center><font size=1>Permission</font></td>
					<td width=30 align=center><font size=1>Rename</font></td>
					<td width=25 align=center><font title="게시판 설정 복제" alt="게시판 설정 복제" size=1>Copy1</font></td>
					<td width=25 align=center><font title="게시물 복제" alt="게시물 복제" size=1>Copy2</font></td>
				</tr>');

	//print list
	while($d=mysql_fetch_array($res)) {
		$dc=@mysql_result(MYSQL_QUERY('SELECT count(*) FROM msb_'.$d[0]),0,0)-1;

echo('
			<tr>
				<td align=center><input type=checkbox name=cart['.$d[0].'] value='.$d[0].'></td>
				<td><a href=./?id='.$d[0].'>'.$d[0].'</a></td>
				<td align=center><font size=1>'.$dc.'['.(int)$d[6].']</td>
				<td align=center><a href="'.$PHP_SELF.'?bo=empty&id='.$d[0].'"><font title="게시물 전부 비우기" size=1>Empty</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=optimize&id='.$d[0].'"><font title="옵티마이즈" size=1>Optimize</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=repair&id='.$d[0].'"><font title="망가진거 복구하기" size=1>Repair</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=edit&act=modify&id='.$d[0].'"><font title="설정 수정하기" size=1>Modify</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=permission&act=modify&id='.$d[0].'"><font title="권한 수정하기" size=1>Permission</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=rename&id='.$d[0].'"><font title="이름바꾸기" size=1>Rename</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=copy1&id='.$d[0].'"><font title="게시판 설정 복제" size=1>Copy</font></a></td>
				<td align=center><!--<a href="'.$PHP_SELF.'?bo=copy2&id='.$d[0].'"><font title="게시물 복제" size=1>Copy</font></a>--></td>
			</tr>
			<tr height=1 bgcolor=bbbbbb><td colspan=11></td></tr>');

		$artical_num--;

	}

	echo('
		</table>');

		if($page > 1) echo ('<a href=# onclick=page('.($page-1).')>[Prev]</a>...');

		for($i=$page-5 ; $i<=$page+5 ; $i++) {
			if($i == $page and $i>0) echo (' <b>'.$i.'</b> ');
			elseif($i>0 and $i<=$tpage) echo (' <a href=# onclick=page('.$i.')>['.$i.']</a> ');
		}

		if($page<$tpage) echo ('...<a href=# onclick=page('.($page+1).')>[Next]</a>');
echo('
				</td>
			</tr>
			<tr>
				<td colspan=2 align=right>
				<input type=button value=Delete class=confirm onclick=mexec("deleteall")>
				</td>
			</tr>
			</form>
		</table><br><br>');

		FOOT_ADMIN();
}

}

MYSQL_CLOSE($dbconn);

exit;

?>