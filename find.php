<?

IF($act=="find") {

	if(!$u_id) go("","ID �� �Է��ϼ���");
	if(!$eml) go("","E-mail �ּҸ� �ùٷ� �Է��ϼ���");

	$d=MYSQL_QUERY("Select no,pw,eml from ".$table3." WHERE id='".$u_id."' AND eml='".addslashes($eml)."'");
	if( MYSQL_AFFECTED_ROWS() < 1 ) {
		go("","�´� �����Ͱ� �����ϴ�");
	}

	$d=MYSQL_FETCH_ROW($d);

	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("Select password('$d[1]')"));

	MYSQL_QUERY("UPDATE ".$table3." set pw=password('".$result[0]."') WHERE no='".$d[0]."'") or DB_ERR(__FILE__.".".__LINE__);
	
	/******************************************************************
	From PHPSCHOOL.com ��..
	******************************************************************/

	$fromaddress=$member[6];
	$toaddress=$d[2];
	$subject ="Msbbs Password";
	$body = $result[0];

	REQUIRE_ONCE("./scripts/sendmail.php");

	@nmail($fromaddress, $toaddress, $subject, $body, $headers);

	go("","./?id=".$id);

} ELSEIF($act=="join") {

	if( !$id ) go("","ID �� �Է��� �ּ���");
	if( !$nm ) go("","�̸�(Ȥ�� �г���)�� �Է��� �ּ���");
	if( !$eml ) go("","Email �ּҸ� �Է��� �ּ���\n\n��й�ȣ ã�⿡ �� �ʿ��մϴ�");
	if( !$u_pw or !$u_pw2 ) go("","��й�ȣ�� �Է��� �ּ���");
	if( $u_pw!=$u_pw2 ) go("","��й�ȣ�� �ùٷ� �Է��ϼ���");

	if( strlen($id) > 12 ) go("","���̵� ���ڼ��� �ʹ� �۰ų� ��ϴ�");
	if( !eregi("([\_0-9A-Z])",$id) ) go("","���̵�� ���ڿ� ���� �ҹ��ڿ� ������ھ��� �������� �̷������ �մϴ�");
	if( !eregi("([^[:space:]]+)", $nm) ) go ("","�̸��� �ùٷ� �Է��� �ּ���");

	if( $eml&&(!eregi("([^[:space:]]+)" , $eml) && !eregi("([_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)" , $eml))) {
		go("","E-mail �ּҸ� �ùٷ� �Է��ϼ���");
	}

	if($i_o=="on") { $i_o="1"; } else { $i_o="0"; }

	if(!$birth) $birth="";

	$res=MYSQL_QUERY("SELECT no from ".$table3." Where id='".$u_id."'");
	if(MYSQL_AFFECTED_ROWS()>0) go("","�̹� �Ȱ��� ���̵� �����մϴ�".MYSQL_AFFECTED_ROWS());

	MYSQL_QUERY("LOCK TABLES ".$table3." WRITE") or go("",mysql_error());

	$result=MYSQL_QUERY("INSERT into $table3

	(no,bid,id,pw,lv,nm,eml,hm,icq,msn,birth,r_d,mm,i_o)

	values('','$id','$u_id',password('$u_pw'),'8','".addslashes($nm)."','".addslashes($eml)."','".addslashes($hm)."','$icq','$msn','$birth','".time()."','".addslashes($mm)."','$i_o')

	");

	MYSQL_QUERY("UNLOCK TABLES") or go("",mysql_error());

	if($result) {
		login("$u_id","$u_pw");
		mysql_close($dbconn);

		HEADER("Location:./?id=".$id);
		exit;
	} else {
		mysql_close($dbconn);
		go("","������ �߻��߽��ϴ�.����Ŀ� �ٽ� �õ��� �ֽʽÿ�.");
	}

} ELSE {

	REQUIRE_ONCE($set[49]."/find.php");

}

?>