<?
setcookie("cookie_write","$nm",time()+2592000,"/");

if(!$nm) go("","�̸��� �Է��ϼ���");
if(!$pw AND $member[0]==9) go("","��й�ȣ�� �Է��ϼ���");
if(!$mm) go("","������ �Է��ϼ���");

REQUIRE_ONCE("scripts/write.filter.php");

$tt=filter($tt);
$mm=filter($mm);

$pw=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT password('".$pw."')"));
$pw=$pw[0];

if($member[4]<9) { // ����� ��

	MYSQL_QUERY("LOCK TABLES ".$table4." WRITE") or DB_ERR(__FILE__."-".__LINE__);

	MYSQL_QUERY("Insert into ".$table4."
	
	(no,tn,nm,mm,w_i,w_d,bm,pw)

	values('','$no','".addslashes($nm)."','".addslashes($mm)."','".getenv("remote_addr")."','".time()."','".$member[0]."','')") or DB_ERR(__FILE__."-".__LINE__);

	MYSQL_QUERY("UNLOCK TABLES");

} else { // �մ��� ��

	MYSQL_QUERY("LOCK TABLES ".$table4." WRITE") or DB_ERR(__FILE__."-".__LINE__);

	MYSQL_QUERY("Insert into ".$table4."

	(no,tn,nm,mm,w_i,w_d,bm,pw)
			
	values('','$no','".addslashes($nm)."','".addslashes($mm)."','".getenv("remote_addr")."','".time()."','','".$pw."')") or DB_ERR(__FILE__."-".__LINE__);

	MYSQL_QUERY("UNLOCK TABLES");

}

go($HTTP_REFERER,"");

?>