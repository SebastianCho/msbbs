<?
IF($act=="loges") { // ��� ���� ����

	if( !$nm ) go("","�̸�(Ȥ�� �г���)�� �Է��� �ּ���");
	if( !$eml ) go("","Email �ּҸ� �Է��� �ּ���\\n\\n��й�ȣ ã�⿡ �� �ʿ��մϴ�");

	if( $u_pw!=$u_pw2 ) {
		go("","��й�ȣ�� ���� �ٸ��ϴ�");
	} elseif( $u_pw!="" AND $u_pw2!="" ) {
		$u_pws="pw=password('".$u_pw."'),";
	}

	if( !eregi("([^[:space:]]+)", $nm) ) go ("","�̸��� �ùٷ� �Է��� �ּ���");

	if( $eml&&(!eregi("([^[:space:]]+)" , $eml) && !eregi("([_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)" , $eml))) {
		go("","E-mail �ּҸ� �ùٷ� �Է��ϼ���");
	}

	if($i_o=="on") { $i_o="1"; } else { $i_o="0"; }

	if(!$birth) $birth="";

	MYSQL_QUERY("LOCK TABLES ".$table3." WRITE") or DB_ERR(__FILE__."-".__LINE__);

	$result=MYSQL_QUERY("UPDATE $table3 set

	".$u_pws."
	nm='".addslashes($nm)."'
	,eml='".addslashes($eml)."'
	,hm='".addslashes($hm)."'
	,icq='$icq'
	,msn='".addslashes($msn)."'
	,birth='$birth'
	,mm='".addslashes($mm)."'
	,i_o='$i_o'

	WHERE no='".$member[0]."'
	") or DB_ERR(__FILE__."-".__LINE__);

	MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

	if($u_pw!='' AND $u_pw2!='') {
		login("$member[2]","$u_pw");

		HEADER("Location:./?id=".$id);
		exit;
	} else {
		HEADER("Location:./?id=".$id);
		exit;
	}

} ELSEIF($act=='joinout') {
	$member=member();

	$result=MYSQL_QUERY('Select lv from '.$table3.' Where no="'.$member[0].'"') or DB_ERR(__FILE__.'-'.__LINE__,'���� �� �� �����ϴ�');
	$result=MYSQL_FETCH_ARRAY($result);
	$result[1]=MYSQL_RESULT(MYSQL_QUERY('Select count(*) from '.$table3.' Where lv="1"'),0,0);

	if($result[0]==1 and $result[1]<2) go('','������ �� �����ϴ�');

	MYSQL_QUERY('Delete from '.$table3.' Where no="'.$member[0].'"');
	go('javascript:window.close()','������ �Ϸ�Ǿ����ϴ�');

	exit;
} ELSE {
	REQUIRE_ONCE($set[49]."/loged.php");
}
?>