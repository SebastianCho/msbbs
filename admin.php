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

FUNCTION showtables($str1) {// $str1 �� <select> �� �̸�
	Global $table2,$table3;

	echo ('
		<SELECT size=5 style="width:360;" name="'.$str1.'[]" multiple=multiple>
			<option value="">�Խ��Ǽ����� ����ڷ�</option>');
	$result=MYSQL_QUERY("Show tables;") or DB_ERR(__FILE__."-".__LINE__);

	WHILE($d=mysql_fetch_array($result)) {
		if(eregi("msb_+",$d[0]) ) {//msbbs �� �����ִ� ���̺� ����~
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
		if(!$id) go("","�Խ��� �̸��� ������ �ּ���");

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

	if(!$id OR strlen($id)>20) go("","�Խ��� �̸��� �������� �ʾҽ��ϴ�");
	if(!$skin) go("","��Ų�� ������ �ּ���");
	if(!eregi("([\_a-z0-9])",$id)) go("","�Խ��� �̸����� �����ҹ��ڿ� ���� �׸��� ������ھ�(_)�� ����մϴ�");
	if(!eregi("([0-9])",$opr) AND $opr!="") go("","�Խ��� ������ ��ȣ�� �ùٷ� �����ϼ���");
	if(!eregi("([0-9])%$|px$",$m_wd)) go("","�Խ��� ���α��̸� �ùٷ� �����ϼ���");
	if(!eregi("([0-9])",$m_inp)) go("","�� �������� ǥ�õǴ� �Խù� ��(m_inp) �� �ùٷ� �����ϼ���");
	if(!eregi("([0-9])",$fl_mn)) go("","���ε� ������ ���� ������ �ùٷ� �����ϼ���");

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
			if(confirm('������ �����Ͻðڽ��ϱ�?\n\n������ ������ �ڷḦ �ǵ��� �� �����ϴ�')) {
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
		if(confirm('���õ� �׸��� �����Ͻðڽ��ϱ�?')) {
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
		&nbsp;&nbsp;<a href='<?=$PHP_SELF?>'><font style='font-size:9pt;color:white;font-family:����'>�Խ��ǰ���</font></a>&nbsp;|
		&nbsp;<a href='<?=$PHP_SELF?>?bo=member'><font style='font-size:9pt;color:white;font-family:����'>�������</font></a>&nbsp;|
		&nbsp;<a href='<?=$PHP_SELF?>?bo=global'><font style='font-size:9pt;color:white;font-family:����'>���&����</font></a>&nbsp;|
		&nbsp;<a href='<?=$PHP_SELF?>?bo=license'><font style='font-size:9pt;color:white;font-family:����'>���̼�������</font></a>
		</td>
		<td align=right valign=middle><a href='<?=$PHP_SELF?>?act=logout'><font style='font-size:9pt;color:white;font-family:����'>�α׾ƿ�</font></a>&nbsp;&nbsp;
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
					$z[1]="������";
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
				$z[1]="������";
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
			alert("ID �� �Է��Ͻʽÿ�");
			document.login.u_id.focus();
			return false;
		}

		if( document.login.u_pw.value=="" ) {
			alert("Password �� �Է��Ͻʽÿ�");
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

		go($HTTP_REFERER,"�۾� �Ϸ�");

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
		������ ���� ���� ��¼�� ��¼�� ���̼��� ǥ�ø� �����ϴ� �̴ϴ�.<br>
		����νø� �� ��ϴ�.
		</td>
	</tr>
	<FORM NAME=form ENCTYPE="multipart/form-data" METHOD=post ACTION='.$PHP_SELF.'>
	<input type=hidden name=bo value="license_2">
	<tr>
		<td colspan=2 align=center>
		<table border=0 cellpadding=8 cellspacing=0 width=90% style="border:solid 1 #505050">
			<tr>
				<td align=center>
				<b>���̼��� ����</td>
			</tr>
			<tr>
				<td align=center>
				<textarea name=text style="width:98%;height:150;border:solid 1 #AFAFAF;background-color:white">');
				@include("license.txt");
				echo('</textarea>
				<p align=right>delete it<input type=checkbox name=del ONCLICK="if(confirm(\'�����Ͻðڽ��ϱ�?\')){form.text.value=\'\';}"></p>
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

		if(is_uploaded_file($file) OR $query_text) {// ���� ���ε� ��
			if(strrchr($file_name,".")!=".sql") go("",".sql ������ �ƴմϴ�");

			move_uploaded_file($file,"./$file_name");
			@chmod("./$file_name","0707");

			$fp=fopen("./$file_name","r");
			$query=fread($fp,filesize("./$file_name"));
			if(get_magic_quotes_runtime()==1) $query=stripslashes($query);
			fclose($fp);

			$query.=$query_text;

			//�����۾��� �ѹ��� �� ��ɸ� ó���� �� �ֱ� ������-_-;; ������ �����������
			$query_i=split(";",$query);

			FOR($i=0;$i<count($query_i);$i++) {
				$query_i[$i]=trim($query_i[$i]);
				
				@MYSQL_QUERY("$query_i[$i]") or DB_ERR(__FILE__."-".__LINE__);
			}

			unlink("./".$file_name);
		}

		go($HTTP_REFERER,"�۾� �Ϸ�");

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
					<li>����Ͻ��� �� �����ϼ���;</li>
					<li>Ȥ�� �� ���� �ʴ´ٸ� <a href=http://byariel.com target=_blank>���Ĺ���ó</a>�� ���¸� <font color=red>�ڼ���</font> ����Ʈ ���ּ���;</li>
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
							<b>���� �۾�</td>
						</tr>
						<tr>
							<td align=right>
						�� �ȿ� ������ ���� �������Դϴ�. �ѹ��� �ϳ��� ��ɸ� �����մϴ�.<br>
						���� ���ε�� <font size=1>.sql</font> ���Ϲۿ� ������� �ʽ��ϴ�.</td>
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
							<td align=center><b>��� - <font size=1>Backup</font></td>
						</tr>
						<tr>
							<td align=center>
							<table border=0 cellpadding=0 cellspacing=0 width=400>
							<TR><TD>');

							showtables('table');
echo('
							<br>
							<input type=checkbox name=structure><a href=# onclick="if(backup.structure.checked==true){backup.structure.checked=false}else{backup.structure.checked=true;return true}"><font size=1>Structure</font></a> <input type=checkbox name=data><a href=# onclick="if(backup.data.checked==true){backup.data.checked=false}else{backup.data.checked=true}"><font size=1>Data</font></a><br>
							<input type=checkbox name=drop><a href=# onclick="if(backup.drop.checked==true){backup.drop.checked=false}else{backup.drop.checked=true}"><font size=1>Structure</font> üũ�� <font size=1>"Drop Table"</font> �߰�</a><br><br>

							<input type=radio checked name=dt value=1><font size=1>data</font>�� ������ <font color=red>������ ���ξ���</font> ���°� �ǵ��� �մϴ�<br>
							<input type=radio name=dt value=2><font size=1>data</font>�� ������ <font color=red>���ξ���(�߰�)</font> ���°� �ǵ��� �մϴ�<br>
							<input type=radio name=dt value=3><font size=1>data</font>�� ������ <font color=red>�����(����)</font> ���°� �ǵ��� �մϴ�<br><br>

							<li>�Խ��� ���� �����ÿ��� ���� �Խ����� ������ ����� �մϴ�</li>
							<li>�� ���� ������ ������� �ʴ� �κе� �ֽ��ϴ�</li>
							<li>�� data ������ �������� Primary Key �� ���� �κ��Դϴ�</li>
							
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

		if(!$id) go("","ID �� �Է��� �ּ���");
		if(!$nm) go("","�̸�(Ȥ�� �г���)�� �Է��� �ּ���");
		if(!$eml) go("","Email �ּҸ� �Է��� �ּ���\\n\\n��й�ȣ ã�⿡ �� �ʿ��մϴ�");
		if($pw!=$pw2) go("","��й�ȣ�� �ùٷ� �Է��ϼ���");
		if(strlen($id) < 3 or strlen($id) > 12) go("","���̵� ���ڼ��� �ʹ� �۰ų� ��ϴ�");
		if(!eregi("([_0-9a-zA-Z])",$id)) go("","���̵�� ���ڿ� �������� �������� �̷������ �մϴ�");
		if(!eregi("([^[:space:]]+)", $nm)) go ("","�̸��� �ùٷ� �Է��� �ּ���");
		if(!eregi("([^[:space:]]+)" , $eml) && !eregi("([_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)" , $eml)) { go("","E-mail �ּҸ� �ùٷ� �Է��ϼ���"); }
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

			if(!$no) go("","��� ��ȣ�� ������ �ּ���");
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
				alert("�̸��� �ùٷ� �Է��ϼ���");
				join.nm.focus();
				return false;
			}
			if(join.eml.value=="") {
				alert("���� �ּҸ� �ݵ�� ��������");
				join.eml.focus();
				return false;
			}
			if(join.pw.value!=join.pw2.value) {
				alert("��й�ȣ�� �ùٷ� �Է��ϼ���");
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
			<td colspan=2 align=center>ȸ�� ��������
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
			<td><input type=checkbox name=i_o align=absmiddle> <a href=# onclick="if(join.i_o.checked==true){join.i_o.checked=false}else{join.i_o.checked=true}">��������</font> <font color=red>*</font></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td align=right></td>
			<td><input type=submit value=" Confirm " style="background-color:#EEEEEE;width:80;height:30;border:solid 1 #AAAAAA;font-family:tahoma,verdana;font-size:8pt;color:black"> <input type=button value=" Close " onclick="window.close()" style="background-color:#EEEEEE;width:80;height:30;border:solid 1 #AAAAAA;font-family:tahoma,verdana;font-size:8pt;color:black"></td>
		</tr>
		<tr bgcolor=#EEEEEE>
			<td colspan=2><br>
			&nbsp;&nbsp;<font color=red>*</font> ��й�ȣ�� ������ �ÿ��� �Է��� �ּ���.<br>&nbsp;
			</td>
		</tr>
		</form>
	</table>
	<BR>');

	exit;
	CASE (optimize):

		if(!$id) go("","�Խ��� �̸��� �����ϼ���");
		@MYSQL_QUERY("OPTIMIZE TABLE msb_".$id);
		@MYSQL_QUERY("OPTIMIZE TABLE msbc_".$id);
		@MYSQL_QUERY("OPTIMIZE TABLE msbi_".$id);
		go($PHP_SELF,"");

	exit;
	CASE (repair):

		if(!$id) go("","�Խ��� �̸��� �����ϼ���");
		@MYSQL_QUERY("REPAIR TABLE msb_".$id);
		@MYSQL_QUERY("REPAIR TABLE msbc_".$id);
		@MYSQL_QUERY("REPAIR TABLE msbi_".$id);
		go($PHP_SELF,"");

	exit;
	CASE ("rename"):

		IF(!$id) go("","�ٲ� �Խ��� �̸��� �����ϼ���");
		HEAD_ADMIN();

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."'"))<1) {
			go("","�ٲ� �Խ����� �������� �ʽ��ϴ�");
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
			�ٲ� �Խ��� �̸�(�ְ� 20��)�� �����ϼ���.<BR>
			�Խ��� �̸����� �����ҹ��ڿ� ���� �׸��� ������ھ�(_)�� ����մϴ�
			</TD>
		</TR>
		<TR height=20><TD></TD></TR>
		<TR>
			<TD width=45% align=right><b>'.$id.'</b></TD>
			<TD WIDTH=10% align=center>��</TD>
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

		if(!$id and strlen($id)>20 ) { go("","�Խ��� �̸��� ������ �ȵǾ��ų� �ʹ� ��ϴ�"); }
		if(!$target or strlen($target)>20 ) { go("","�ٲ� �̸��� ���� �ȵǾ��ų� �ʹ� ��ϴ�"); }
		
		if( !eregi("([\_a-z0-9])",$id) ) { go("","���� �Խ��� �̸����� �����ҹ��ڿ� ���� �׸��� ������ھ�(_)�� ����մϴ�"); }
		if( !eregi("([\_0-9a-z])",$target) ) { go("","�ٲ� �Խ��� �̸����� �����ҹ��ڿ� ���� �׸��� ������ھ�(_)�� ����մϴ�"); }

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$id."'"))<1) {
			go("","�ٲ� �Խ����� �������� �ʽ��ϴ�");
		}
		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$target."'"))>0) {
			go("","�̹� �Ȱ��� �Խ����� �����մϴ�");
		}

		MYSQL_QUERY("UPDATE ".$table2." SET id='".$target."' WHERE id='".$id."'") or DB_ERR(__FILE__."-".__LINE__);// �Խ��� �������� �̸� �ٲٱ�
		MYSQL_QUERY("RENAME TABLE msb_".$id." TO msb_".$target) or DB_ERR(__FILE__."-".__LINE__);// �Խ��� �̸� �ٲٱ� - ��ü
		MYSQL_QUERY("RENAME TABLE msbi_".$id." TO msbi_".$target) or DB_ERR(__FILE__."-".__LINE__);// �Խ��� �̸� �ٲٱ� - ����
		MYSQL_QUERY("RENAME TABLE msbc_".$id." TO msbc_".$target) or DB_ERR(__FILE__."-".__LINE__);// �Խ��� �̸� �ٲٱ� - �ڸ�Ʈ

		set_time_limit(0);

		MYSQL_QUERY("UPDATE ".$table3." SET bid='".$target."' WHERE bid='".$id."'") or DB_ERR(__FILE__."-".__LINE__);

		go($PHP_SELF,"���� �Ϸ�");

	exit;

	CASE (copy1_ok) :

		if(!$id) go("","�Խ��� �̸��� ������ �� �Ǿ����ϴ�");
		if(!$target or strlen($target)>20) go("","������ �Խ��� �̸��� ���� �ȵǾ��ų� �ʹ� ��ϴ�");

		if( !eregi("([\_0-9a-z])",$target) ) { go("","�ٲ� �Խ��� �̸����� �����ҹ��ڿ� ���� �׸��� ������ھ�(_)�� ����մϴ�"); }

		$result=MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."'") or DB_ERR(__FILE__."-".__LINE__);
		if(mysql_num_rows($result)<1) {
			go("","�ٲ� �Խ����� �������� �ʽ��ϴ�");
		}
		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$target."'"))>0) {
			go("","�̹� �Ȱ��� �Խ����� �����մϴ�");
		}

		$result=MYSQL_FETCH_ARRAY($result);

		MYSQL_QUERY("INSERT INTO $table2
		values('$target','$result[1]','$result[2]','$result[3]','$result[4]','$result[5]','$result[6]','$result[7]','$result[8]','$result[9]','$result[10]','$result[11]','$result[12]','$result[13]','$result[14]','$result[15]','$result[16]','$result[17]','$result[18]','$result[19]','$result[20]','$result[21]','$result[22]','$result[23]','$result[24]','$result[25]','$result[26]','$result[27]','$result[28]','$result[29]','$result[30]','$result[31]','$result[32]','$result[33]','$result[34]','$result[35]','$result[36]','$result[37]','$result[38]','$result[39]','$result[40]','$result[41]','$result[42]','$result[43]','$result[44]','$result[45]','$result[46]','$result[47]','$result[48]')
		
		") or DB_ERR(__FILE__."-".__LINE__);

		$id=$target;

		REQUIRE_ONCE("./scheme.sql");

		MYSQL_QUERY("$board_table") OR DB_ERR(__FILE__."-".__LINE__,"���� �Խ��� ���� ����");;
		MYSQL_QUERY("$first_article") OR DB_ERR(__FILE__."-".__LINE__,"����Խù� ���� ����");
		MYSQL_QUERY("$idx_table") OR DB_ERR(__FILE__."-".__LINE__,"���� ���̺� ���� ����");
		MYSQL_QUERY("$second_article") OR DB_ERR(__FILE__."-".__LINE__,"����Խù� ���� ����");
		MYSQL_QUERY("$comment_table") OR DB_ERR(__FILE__."-".__LINE__,"�ڸ�Ʈ ���̺� ���� ����");

		go("admin.php","");

	exit;

	CASE (copy2_ok) :

		go("","���� �������� �ʴ� ����Դϴ�");

		if(!$id) go("","�Խ��� �̸��� ������ �� �Ǿ����ϴ�");
		if(!$target or strlen($target)>20) go("","������ �Խ��� �̸��� ���� �ȵǾ��ų� �ʹ� ��ϴ�");

		if( !eregi("([\_0-9a-z])",$target) ) { go("","�ٲ� �Խ��� �̸����� �����ҹ��ڿ� ���� �׸��� ������ھ�(_)�� ����մϴ�"); }

		$result=@MYSQL_QUERY("SELECT * FROM ".$table2." WHERE id='".$id."' LIMIT 1") or DB_ERR(__FILE__."-".__LINE__);
		if( MYSQL_NUM_ROWS($result)<1) {
			go("","������ �Խ����� �������� �ʰų� �Խù��� �������� �ʽ��ϴ�");
		}
		if( MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$target."' LIMIT 1"))<1 ) {
			go("","���繰�� ������ �Խ����� �������� �ʽ��ϴ�");
		}

		$result=MYSQL_QUERY("SELECT * FROM $table1 ORDER BY no asc");

//		mysql_query("LOCK TABLES ".$table1." WRITE, ".$table4." WRITE, ".$table5." WRITE, msb_".$target." WRITE, msbi_".$target." WRITE, msbc_".$target." WRITE") or DB_ERR(__FILE__."-".__LINE__);

		WHILE( $d=MYSQL_FETCH_ARRAY($result) ) {
			IF( $d[0] > 0 ) {

				IF($d[22]==1) {// ���������� ��

					$result=@MYSQL_RESULT(@MYSQL_QUERY("SELECT count(*) FROM ".$table5." WHERE idx='1'"),0,0);
					if($result>0) {// ���������� ����.. ��, idx='1' �� ���� �����Ҷ�
						$result=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT idx,main FROM ".$table5." WHERE idx='1'"));
						$result[1]-=1;

						MYSQL_QUERY("UPDATE ".$table5." set idx='1', main='".$result[1]."' WHERE idx='1'");
					} else {// idx='1' �� �Խù��� �������� ���� ��
						MYSQL_QUERY("INSERT into ".$table5." values('1','10010','0')") or DB_ERR(__FILE__."-".__LINE__);
						$result[0]=1;
						$result[1]=1;
					}

				} ELSE {// ������� ��

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

				// ������ �ű��
				// ������ �ڷ���� ��ο� �̸� ��� ���Ծ���

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




		IF( $how==2 ) {// �̵��̸� �� ����
		}
	
	exit;

	CASE (copy1) : /* �Խ��� ���� ���� */

		if(!$id) go("","�Խ��� �̸��� ������ �ּ���");

		HEAD_ADMIN();

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$id."'"))<1) {
			go("","������ �Խ��� ������ �������� �ʽ��ϴ�");
		}
		?>
		<BR><BR><BR><BR><BR><BR>
		<FORM NAME=admin ACTION="<?=$PHP_SELF?>" METHOD=post>
		<input type=hidden name=id value="<?=$id?>">
		<input type=hidden name=bo value="copy1_ok">
		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=center width=400>
			<TR>
				<TD align=center colspan=3 style='font-size:18pt;color:555555'><b>COPY ��</b>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN=3 align=center>
				������ �Խ��� ������ �̸�(�ְ� 20��)�� �����ϼ���.<BR>
				�Խ��� �̸����� �����ҹ��ڿ� ���� �׸��� ������ھ�(_)�� ����մϴ�
				</TD>
			</TR>
			<TR height=20><TD></TD></TR>
			<TR>
				<TD width=45% align=right><b><?=$id?></b></TD>
				<TD WIDTH=10% align=center>��</TD>
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

	CASE (copy2) : /* �Խù� ���� */

		if(!$id) go("","�Խ��� �̸��� ������ �ּ���");

		HEAD_ADMIN();

		if(mysql_num_rows(MYSQL_QUERY("SELECT * FROM $table2 WHERE id='".$id."'"))<1) {
			go("","������ �Խù��� �������� �ʽ��ϴ�");
		}
		?>
		<BR><BR><BR><BR><BR><BR>
		<FORM NAME=admin ACTION="<?=$PHP_SELF?>" METHOD=post>
		<input type=hidden name=id value="<?=$id?>">
		<input type=hidden name=bo value="copy2_ok">
		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=center width=400>
			<TR>
				<TD align=center colspan=3 style='font-size:18pt;color:555555'><b>COPY ��</b>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN=3 align=center>
				�Խù��� �ٿ����� �Խ����� �����ϼ���.
				</TD>
			</TR>
			<TR height=20><TD></TD></TR>
			<TR>
				<TD width=45% align=right><b><?=$id?></b></TD>
				<TD WIDTH=10% align=center>��</TD>
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
					<input type=radio name=how value=1 checked>����
					<input type=radio name=how value=2>�̵� ( ���� + �����Խù� ���� )
				</TD>
			</TR>
			<TR>
				<TD colspan=3 align=center>
				<input type=checkbox name=how2> �ڸ�Ʈ ���� ����
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

		if(!$id) go("","�Խ����� ������ �ּ���");

		if($p_list>9 OR $p_list<1) go("","�Խù� ��� ���� ������ �ùٷ� �����ϼ���");
		if($p_l>2 OR $p_l<0) go("","��ũ ��뿩�θ� �ùٷ� �����ϼ���");
		if($p_al!="0" AND $p_al!=1) go("","�����̹��� ��ũ ��� ������ �ùٷ� �����ϼ���");
		if($p_h>9 OR $p_h<0) go("","HTML ��� ������ �ùٷ� �����ϼ���");
		if($p_vl!="0" AND $p_vl!=1) go("","���뺸�� �� ����Ʈ ��뿩�θ� �ùٷ� �����ϼ���");
		if($p_cg!="0" AND $p_cg!=1) go("","ī�װ� ��뿩�θ� �ùٷ� �����ϼ���");
		if($p_rm!="0" AND $p_rm!=1) go("","��� ���� ��� ������ �ùٷ� �����ϼ���");
		if($p_reply>9 OR $p_reply<1) go("","��� �ޱ� ������ �ùٷ� �����ϼ���");
		if($p_vr!="0" AND $p_vr!=1) go("","��۰��� ���� ������ �ùٷ� �����ϼ���");
		if($p_write>9 OR $p_write<1) go("","�Խù� ���� ������ �ùٷ� �����ϼ���");
		if($p_gl>9 OR $p_gl<0) go("","������ ��� ������ �ùٷ� �����ϼ���");
		if($p_modify>9 OR $p_modify<1) go("","�� ���� ������ �ùٷ� �����ϼ���");
		if($p_delete>9 OR $p_delete<1) go("","�� ���� ������ �ùٷ� �����ϼ���");
		if($p_view>9 OR $p_view<1) go("","�Խù� ���� ������ �ùٷ� �����ϼ���");
		if($p_cw>9 OR $p_cw<0) go("","�ڸ�Ʈ ���� ������ �ùٷ� �����ϼ���");
		if($p_cd>9 OR $p_cd<0) go("","�ڸ�Ʈ ��� ������ �ùٷ� �����ϼ���");
		if($p_np!="0" AND $p_np!=1) go("","�ձ� �ޱ� �� �� �ִ� ������ �ùٷ� �����ϼ���");
		if($p_st>9 OR $p_st<0) go("","Ư�� �±� ��� ������ �ùٷ� �����ϼ���");
		if($p_sc>9 OR $p_sc<0) go("","��б� ��� ������ �ùٷ� �����ϼ���");
		if($p_up>9 OR $p_up<0) go("","���ε� ��� ������ �ùٷ� �����ϼ���");
		if($p_dn>9 OR $p_dn<0) go("","���� �ٿ�ε� ������ �ùٷ� �����ϼ���");
		if($p_nt>9 OR $p_nt<0) go("","�������� �ۼ� ������ �ùٷ� �����ϼ���");
		if($p_join!="0" AND $p_join!=9) go("","ȸ������ ������ �ùٷ� �����ϼ���");
		if($p_login>9 OR $p_login<2) go("","ȸ���α� ������ �ùٷ� �����ϼ���");
		if($p_loged>9 OR $p_loged<0) go("","ȸ�� �������� ��� ������ �ùٷ� �����ϼ���");
		if($p_print>9 OR $p_print<0) go("","�� ����Ʈ ������ �ùٷ� �����ϼ���");
		if($p_find>9 OR $p_find<0) go("","ȸ�� ��й�ȣ ã�� ��� ������ �ùٷ� �����ϼ���");
		if($p_copy>90 OR $p_login<0) go("","�� ���� ������ �ùٷ� �����ϼ���");

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

		if($res[0]>0) {//�̹� �� ������ �ǵ�������
			go("","�̹� ���� �̸��� �Խ����� �����մϴ�");
		} else {//������ ���� ���

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

		MYSQL_QUERY("$board_table") OR DB_ERR(__FILE__."-".__LINE__,"���� �Խ��� ���̺� ��������");
		MYSQL_QUERY("$first_article") OR DB_ERR(__FILE__."-".__LINE__,"����Խù� ���� ����");
		MYSQL_QUERY("$idx_table") OR DB_ERR(__FILE__."-".__LINE__,"���� ���̺� ���� ����");
		MYSQL_QUERY("$second_article") OR DB_ERR(__FILE__."-".__LINE__,"����Խù� ���� ����");
		MYSQL_QUERY("$comment_table") OR DB_ERR(__FILE__."-".__LINE__,"�ڸ�Ʈ ���̺� ���� ����");

		go($PHP_SELF,"");

	exit;
	CASE (deleteall):

		$count=count($cart);
		if($count<1) {
			go("","������ �׸��� �����ϴ�");
		} else {
			while(list($value,$label)=each($cart)) {

				$z=mysql_fetch_array(MYSQL_QUERY("SELECT count(*) FROM msb_".$label." WHERE fn!=''"));//���ε�� ������ �ִ� ���ڵ� ������ ����

				if($z[0]>0) {//������ 1�� �̻��̸� ���ε� �����Ͱ� �����Ƿ� �����.
					$c=MYSQL_QUERY("SELECT w_d,fn,fx FROM msb_".$label." WHERE fn!='' ORDER BY no asc");//���ε�� ������ �ִ� ���ڵ带 ����
					FOR($x=0;$x<$z[0];$x++) {
						$v=mysql_fetch_array($c);
						$v[1]=explode(",",$v[1]);
						$v[2]=explode(",",$v[2]);
						if(count($v[1])>1) {//upload �ʵ� ������ �����Ͱ� ����
							FOR($n=0;$n<count($v[1]);$n++) {
								unlink("./data/".$v[0]."/".$v[1][$n].".".$v[2][$n]);
							}
							rmdir("./data/".$v[0]);
						} else {//upload �ʵ忡 �Ѱ��� �����Ͱ� ����
							unlink("./data/".$v[0]."/".$v[1][0].".".$v[2][0]);
						}
						rmdir("./data/".$v[0]);
					}
				}

				MYSQL_QUERY("DELETE FROM $table2 WHERE id='".$label."'") OR go("","�Խ��� ���� �����߿� ������ �߻��߽��ϴ�");
				MYSQL_QUERY("DROP table msb_".$label) OR go("","msb_".$label."(�Խ��� ��ü)���̺��� Drop �ϴ� �߿� ������ �߻��߽��ϴ�");
				MYSQL_QUERY("DROP table msbi_".$label) OR go("","msbi_".$label."(���� ���̺�)���̺��� Drop �ϴ� �߿� ������ �߻��߽��ϴ�");
				MYSQL_QUERY("DROP table msbc_".$label) OR go("","msbc_".$label."(�ڸ�Ʈ ���̺�)�� Drop �ϴ� �߿� ������ �߻��߽��ϴ�");
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
		else go("","�Խ��� ���� ������Ʈ �߿� ������ �߻��߽��ϴ�");

	exit;
	CASE ("empty"):

		if(!$id) go("","�Խ��� �̸��� �����ϼ���");
		echo ("
			<SCRIPT>
			if(!confirm('������ ����ðڽ��ϱ�?')) {
				history.back();
			} else {
				location.href='".$PHP_SELF."?id=$id&bo=emptyp'
			}
			</SCRIPT>");

	exit;
	CASE (emptyp):

		if(!$id) go("","�Խ��� �̸��� �����ϼ���");

		$result=MYSQL_QUERY("SELECT w_d,fn,fx,ft FROM $table1 WHERE fn!='' and ft!='' and fs!=''");//���ε�� ������ �ִ� ���ڵ� ������ ����
		$result2=mysql_affected_rows();


		echo $result2;
		if($result2>0) {//������ 1�� �̻��̸� ���ε� �����Ͱ� �����Ƿ� �����.

			FOR($i=0;$i<$result2;$i++) {
				$d=MYSQL_FETCH_ARRAY($result);
				$path="./data/".$d[0]."/";
				$d[1]=explode(",",$d[1]);
				$d[2]=explode(",",$d[2]);
				if(count($d[1])>1) {//upload �ʵ� ������ �����Ͱ� ����
					FOR($n=0;$n<count($d[1]);$n++) {
						chmod($path.$d[1][$n].".".$d[2][$n],"707");
						unlink($path.$d[1][$n].".".$d[2][$n]);
					}
				} else {//upload �ʵ忡 �Ѱ��� �����Ͱ� ����

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
		
		MYSQL_QUERY("DROP TABLE msb_".$id) OR go("","msb_".$id."(�Խ��Ǻ�ü)���̺� ���� ����\\n���� �̸�:".$PHP_SELF." LINE :".__LINE__);
		MYSQL_QUERY("DROP TABLE msbi_".$id) OR go("","msbi_".$id."(�������̺�)���̺� ���� ����\\n���� �̸�:".$PHP_SELF." LINE :".__LINE__);
		MYSQL_QUERY("DROP TABLE msbc_".$id) OR go("","msbc_".$id."(�ڸ�Ʈ���̺�)���̺� ���� ����\\n���� �̸�:".$PHP_SELF." LINE :".__LINE__);

		REQUIRE_ONCE("./scheme.sql");

		MYSQL_QUERY("$board_table") OR go(__FILE__."-".__LINE__,"msb_".$id."(�Խ��Ǻ�ü)���̺� ���� ����");
		MYSQL_QUERY("$first_article") OR go(__FILE__."-".__LINE__,"����Խù� ���� ����");
		MYSQL_QUERY("$idx_table") OR go(__FILE__."-".__LINE__,"msbi_".$id."(�������̺�)���̺� ���� ����");
		MYSQL_QUERY("$second_article") OR DB_ERR(__FILE__."-".__LINE__,"����Խù� ���� ����");
		MYSQL_QUERY("$comment_table") OR go(__FILE__."-".__LINE__,"msbc_".$id."(�ڸ�Ʈ���̺�)���̺� ���� ����");

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
//					if(confirm(' ī�װ��� ����Ͻðڽ��ϱ�? ')) {//
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
//				alert('���ε带 �Ͻ÷��� ���ε� ���Ѱ� ���ε� ������ �����ϼ���');
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
		�Խ����� ���Դϴ�.
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>

			<tr>
				<td align=right><font size=1 title='�Խ��� ������' alt='�Խ��� ������'>BBS <b>Administrator</b></font>&nbsp;</td>
				<td><input type=text name=opr class=input size=16 style="font-weight:bold" value='<?=$result[2]?>'><br>
		����ѹ��� �����ø� �˴ϴ�.<br>
		�� �� �����Ͻ� �� �ֽ��ϴ�.
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>

			<tr>
				<td align=right><font size=1>Title</font>&nbsp;</td>
				<td><input type=text class=input name=h_tt size=50 value='<?=$result[33]?>'><br>
		������ ������ �����մϴ�.
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
		&lt;body> �� ���� �Ӽ��� �����մϴ�.<br>
		�� ) background=image/bg.gif bgcolor=olivegreen</td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Header <b>BodyAddress</b></font>&nbsp;</td>
				<td><input type=text class=input name=h_bd_a size=40 align=absmiddle value='<?=$result[36]?>'> ( ������������ )</td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Header <b>Body</b></font>&nbsp;</td>
				<td>������<br>
			<textarea name=h_bd style="width:350;height:200;border:solid 1 #333333" cols=30 rows=5><?=$result[37]?></textarea></td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Footer <b>BodyAddress</b></font>&nbsp;</td>
				<td><input type=text class=input name=f_bd_a size=40 align=absmiddle value='<?=$result[42]?>'> ( ������������ )</td>
			</tr>

			<tr height=1 bgcolor=#DFDFDF><td colspan=2></td></tr>

			<tr bgcolor=#EEEEEE>
				<td align=right><font size=1>Footer <b>Body</b></font>&nbsp;</td>
				<td>Ǫ�ͱ��<br>
		<textarea name=f_bd style="width:350;height:200;border:solid 1 #333333" cols=30 rows=5><?=$result[43]?></textarea>
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>

			<tr>
				<td align=right><font size=1>Category</font>&nbsp;</td>
				<td><textarea name=cg style="width:350;height:50;border:solid 1 #333333"><?=$result[3]?></textarea><br>
			�������� ��� �޸�(,) �� �����մϴ�.
				</td>
			</tr>

			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Width</font>&nbsp;</td>
				<td><input type=text class=input name=m_wd size=8 maxlength=5 value='<?=$result[38]?>'><br>
		�Խ��� ��ü�� ���α��̸� �����մϴ�.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Articles per a page</font>&nbsp;</td>
				<td><input type=text class=input name=m_inp size=8 value='<?=$result[41]?>'><br>
		�� ��Ͽ� ��Ÿ�� �Խù��� ������ �����մϴ�.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Length</font>&nbsp;</td>
				<td><input type=text class=input name=m_lth size=5 value='<?=$result[40]?>'><br>
		������ ���̰� �ʹ� �涧 �����ϴ� �����Դϴ�.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Time Line</font>&nbsp;</td>
				<td><input type=text class=input name=m_new size=5 value='<?=$result[39]?>'> hours<br>
		�űԱ��̳� ������ ���� �����ϴ� �ð��Դϴ�.<br>
		�۾��� �󸶳� ������ �� ������ ������ �����մϴ�.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Letter <b>filter</b></font>&nbsp;</td>
				<td>����ϰ� ���� ���� ���� �����մϴ�. ( �޸�, �� �����մϴ� )<br>
					<textarea name=ft style="width:350;height:200;border:solid 1 #333333" cols=30 rows=5><?=$result[47]?> </textarea><p>
					�� ���� ������ �Է��� ��� ��ü�� �޼����� �����մϴ�.<br>
					������ ��� ��� �����޼����� ��ϴ�. ( "***�� �Է��Ͻ� �� ���� ���Դϴ�" )<br>
					<input type=text class=input name=ft_msg size=20 maxlength=100 align=absmiddle value='<?=$result[48]?>'>
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Max <b>FileSize</b></font>&nbsp;</td>
				<td><input type=text class=input name=fl_ms size=10 value='<?=$result[44]?>'> Bytes<br>
		���ε�� ���� ������ ũ�⸦ �����ϴ� ��ġ�Դϴ�.<br>
		<font size=1>1MB = 1048576 Bytes , 2MB = 2097152 Bytes</font>
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Max <b>FileNum</b></font>&nbsp;</td>
				<td><input type=text class=input name=fl_mn size=10 value='<?=$result[45]?>'><br>
		���ε��� ���� ���� 0 ���� �ϸ� ���ε带 ���� �ʽ��ϴ�.
				</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right><font size=1>Allow <b>FileFormat</b></font>&nbsp;</td>
				<td><input type=text class=input name=fl_awf size=30 value='<?=$result[46]?>'><br>
		���ε��� �� ����� ���� Ȯ���ڸ� �����մϴ�.<br>
		�޸�(<b>,</b>)�� �����մϴ�.<br>
		������� JPG ���ϰ� GIF ���ϸ��� �ް� ���� ����<br>
		( jpg,gif ) ��� �����ֽø� �˴ϴ�.<br>
		��, �������� ���ܵξ��� �� Ȯ���� �˻縦 ���� �ʽ��ϴ�.
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
	CASE (permission): // �Խ��� ���� ����

		$result=gboard();
		HEAD_ADMIN();

	echo('<table border=0 cellpadding=2 cellspacing=0 width=100% style="border:solid 1 #333333">
			<tr bgcolor=333333>
				<td colspan=2 align=center style="font-size:20pt;colOR:#DDDDDD"><b>'.$act.' The BOARD</td>
			</tr>
			<tr>
				<td colspan=2 align=center style="font-size:11px"><b>Setting Permission : '.$id.'</b><br><br>
		1(�ְ������)���� 8������ ������ ȸ���̸� 9�� �������� ���� �Խ�Ʈ�Դϴ�.<br>&nbsp;
				</td>
			</tr>
			<FORM action='.$PHP_SELF.' method="post">
			<input type=hidden name=id value='.$id.'>
			<input type=hidden name=bo value="permission_ok">
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� ��� ����&nbsp;</td>
				<td>');
			showg("p_list",9,$result[6]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� ���� ����&nbsp;</td>
				<td>');
			showg("p_view",9,$result[7]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>���� �̹��� ��뿩��&nbsp;</td>
				<td>');
			showg("p_al",2,$result[8],"No,Yes");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��ũ ��뿩��&nbsp;</td>
				<td>');
			showg("p_l",2,$result[9],"������,1��,2��");
			echo('<br></td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>HTML ��뿩��&nbsp;</td>
				<td>');
			showg("p_h",9,$result[10]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�ձ� ������ ����&nbsp;</td>
				<td>');
			showg("p_np",2,$result[13],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��۰��� ����&nbsp;</td>
				<td>');
			showg("p_vr",2,$result[12],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� ���뺼 �� �ؿ� ����Ʈ&nbsp;</td>
				<td>');
			showg("p_vl",2,$result[11],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�ڸ�Ʈ ����&nbsp;</td>
				<td>');
			showg("p_cw",9,$result[14]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�ڸ�Ʈ �����&nbsp;</td>
				<td>');
			showg("p_cd",9,$result[15]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>ī�װ� ��� ����&nbsp;</td>
				<td>');
			showg("p_cg",2,$result[5],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� �ۼ��ϱ�&nbsp;</td>
				<td>');
			showg("p_write",9,$result[16]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��� ���� ���&nbsp;</td>
				<td>');
			showg("p_rm",2,$result[17],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��� �ޱ�&nbsp;</td>
				<td>');
			showg("p_reply","9",$result[18]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>������ ���&nbsp;</td>
				<td>');
			showg("p_gl",9,$result[19]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� �����ϱ�&nbsp;</td>
				<td>');
			showg("p_modify",9,$result[20]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� ����&nbsp;</td>
				<td>');
			showg("p_delete",9,$result[21]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>Ư�� �±� ���&nbsp;</td>
				<td>');
			showg("p_st",9,$result[22]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��б� ����&nbsp;</td>
				<td>');
			showg("p_sc",9,$result[23]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��������&nbsp;</td>
				<td>');
			showg("p_nt",9,$result[24]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� ����Ʈ&nbsp;</td>
				<td>');
			showg("p_print",2,$result[30],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�� ����&nbsp;</td>
				<td>');
			showg("p_copy",9,$result[32]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>���ε� ���&nbsp;</td>
				<td>');
			showg("p_up",9,$result[25]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>�ٿ�ε� ���&nbsp;</td>
				<td>');
			showg("p_dn",9,$result[26]);
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>������� ���&nbsp;</td>
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
				<td align=right>������� �α� ���&nbsp;</td>
				<td>');
			showg("p_login",9,$result[28]);
			echo(' ���� �α��� ����</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��� ��������&nbsp;</td>
				<td>');
			showg("p_loged",2,$result[29],"Not use,Use");
			echo('</td>
			</tr>
			<tr height=1 bgcolor=#DDDDDD><td colspan=2></td></tr>
			<tr>
				<td align=right>��� ���ã��&nbsp;</td>
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
	CASE (backup): // �Խ��� ���

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

		FUNCTION get_insert_data($str1) {//$str1 �� ���̺� �̸�
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

		FUNCTION get_update_data($str1,$str2=false) {//$str1 �� ���̺� �̸�
			Global $result;

			MYSQL_QUERY("LOCK TABLES ".$str1." WRITE") OR go("","Lock tables ����");

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
			go("","���ϴ� data ������ ������ �ּ���");
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

			if($id!="") {// �Խ��� �̸��� �����Ǿ����Ƿ� �Խù� ����� ����
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
			go("","������ �׸��� �����ϴ�");
		} else {
			while(list($value,$label)=each($cart)) {
				$level=@mysql_result(MYSQL_QUERY("SELECT count(*) FROM $table3 WHERE lv='1'"),0,0);
				$userinfo=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT nm,lv FROM $table3 WHERE no='$label'"));
				if($userinfo[1]=="1" and $level[0]<"2") { go("",$userinfo[0]."���� ������ �� �����ϴ�"); }
				MYSQL_QUERY("DELETE FROM $table3 WHERE no='$label'") or DB_ERR(__FILE__."-".__LINE__);
			}
		}
		go($PHP_SELF."?bo=member","�۾� �Ϸ�");

	exit;
	CASE (grade_chg):

		$count=count($cart);
		if($count<1) {
			go("","������ �׸��� �����ϴ�");
		} else {
			while(list($value,$label)=each($cart)) {
				$level=@mysql_result(MYSQL_QUERY("SELECT count(*) FROM $table3 WHERE lv='1'"),0,0);
				$userinfo=mysql_fetch_array(MYSQL_QUERY("SELECT * FROM $table3 WHERE no='$label'"));

				if($userinfo[3]=="1" and $level[0]<"2" and $grade>"1") { go("",$userinfo[5]."���� ������ ���� �� �����ϴ�"); }

				MYSQL_QUERY("UPDATE $table3 SET lv='$grade' Where no='$label'") or DB_ERR(__FILE__."-".__LINE__);
			}
		}
		go($PHP_SELF."?bo=member","�۾� �Ϸ�");

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
		alert("�̸��� �Է��ϼ���")
		return false;
	}
	if(!document.send.from.value) {
		document.send.from.focus()
		alert("���� �ּҸ� �Է��ϼ���")
		return false;
	}
	if(!document.send.subject.value) {
		document.send.subject.focus()
		alert("������ �Է��ϼ���")
		return false;
	}
	if(!document.send.memo.value) {
		document.send.memo.focus()
		alert("������ �Է��ϼ���")
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
		<font color=white>���ϸ�����Ʈ�� ���÷��� ������ Sendmail �� �����ؾ��մϴ�</font>
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
						<option value=1>����</option>
						<option value=2>�̻�</option>
						<option value=3>���÷���</option>
					</SELECT>&nbsp;
					��ü �����(����������) : '.$result.'
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
		FROM PHPSCHOOL.com ������������ ToT;;
		**************************************************************/

		/* ��ũ��Ʈ ���� ���� 0����.. */
		@set_time_limit(0);

		if(!$fromname) {
			go("","�̸��� �Է��ϼ���");
		}
		if(!$from) {
			go("","E-mail �ּҸ� �Է��ϼ���");
		}
		if(!$subject) {
			go("","������ �Է��ϼ���");
		}
		if(!$memo) {
			go("","������ �Է��ϼ���");
		} else {
			$memo=nl2br($memo);
		}

		$body=$memo;

		if($updown=="1") $updown="<=";//����
		elseif($updown="2") $updown=">=";//�̻�
		else $updown="=";//���÷���

		$result=MYSQL_QUERY("SELECT eml FROM $table3 WHERE lv".$updown."'".$level."'") or DB_ERR(__FILE__."-".__LINE__);

		if(mysql_affected_rows()<1) go("","���õ� ����� �����ϴ�");

		REQUIRE_ONCE("./scripts/sendmail.php");

		WHILE($d=mysql_fetch_array($result)) {
			if(eregi("\@",$d[email])) {
				if(!@nmail($d[email],$subject,$body,$headers)) {
					go("","Sendmail �� �������� �ʴ� �� �մϴ�");
				} else {
					echo ".";
				}
			}
			$d="";
		}

		go("javascript:window.close()","������ �� ���½��ϴ�.");

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

				if($d[4]==1) $d[15]="blue";//������ ����
				elseif($d[4]==9) $d[15]="red";
				else $d[15]="black";

echo('
	<tr bgcolor=white>
		<td NOWRAP align=center><input type=checkbox name=cart['.$i.'] value='.$d[0].'></td>
		<td NOWRAP align=center><font size=1>'.$d[0].'</td>
		<td NOWRAP><a href='.$PHP_SELF.'?bo=member&act=zoom&level='.$d[4].'><font color='.$d[15].'>'.$d[4].'</font></a>: '.$d[2].'</td>
		<td NOWRAP>'.$d[3].'</td>
		<td><a href='.$PHP_SELF.'?bo=member&act=zoom&r_d='.$d[5].'><font size=1>'.date("m/d",$d[5]).'</td>
		<td align=center><a href='.$PHP_SELF.'?bo=member&bid='.$d[1].'><font title="������ �Խ���">'.$d[1].'</font></a></td>
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
					<option value=9>9 - ����</option>
<!--				<option value=10>���þ���</option>-->
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
				<option value=10 selected>���þ���</option>
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
	$res=MYSQL_QUERY('SELECT id FROM '.$table2.' LIMIT '.(($page-1)*10).',10') or DB_ERR(__FILE__.'-'.__LINE__,'�Խ��� ��� �ε��� ����');

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
					<td width=25 align=center><font title="�Խ��� ���� ����" alt="�Խ��� ���� ����" size=1>Copy1</font></td>
					<td width=25 align=center><font title="�Խù� ����" alt="�Խù� ����" size=1>Copy2</font></td>
				</tr>');

	//print list
	while($d=mysql_fetch_array($res)) {
		$dc=@mysql_result(MYSQL_QUERY('SELECT count(*) FROM msb_'.$d[0]),0,0)-1;

echo('
			<tr>
				<td align=center><input type=checkbox name=cart['.$d[0].'] value='.$d[0].'></td>
				<td><a href=./?id='.$d[0].'>'.$d[0].'</a></td>
				<td align=center><font size=1>'.$dc.'['.(int)$d[6].']</td>
				<td align=center><a href="'.$PHP_SELF.'?bo=empty&id='.$d[0].'"><font title="�Խù� ���� ����" size=1>Empty</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=optimize&id='.$d[0].'"><font title="��Ƽ������" size=1>Optimize</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=repair&id='.$d[0].'"><font title="�������� �����ϱ�" size=1>Repair</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=edit&act=modify&id='.$d[0].'"><font title="���� �����ϱ�" size=1>Modify</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=permission&act=modify&id='.$d[0].'"><font title="���� �����ϱ�" size=1>Permission</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=rename&id='.$d[0].'"><font title="�̸��ٲٱ�" size=1>Rename</font></a></td>
				<td align=center><a href="'.$PHP_SELF.'?bo=copy1&id='.$d[0].'"><font title="�Խ��� ���� ����" size=1>Copy</font></a></td>
				<td align=center><!--<a href="'.$PHP_SELF.'?bo=copy2&id='.$d[0].'"><font title="�Խù� ����" size=1>Copy</font></a>--></td>
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