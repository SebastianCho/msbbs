<?
IF($act=="loges") { // 멤버 정보 저장

	if( !$nm ) go("","이름(혹은 닉네임)을 입력해 주세요");
	if( !$eml ) go("","Email 주소를 입력해 주세요\\n\\n비밀번호 찾기에 꼭 필요합니다");

	if( $u_pw!=$u_pw2 ) {
		go("","비밀번호가 서로 다릅니다");
	} elseif( $u_pw!="" AND $u_pw2!="" ) {
		$u_pws="pw=password('".$u_pw."'),";
	}

	if( !eregi("([^[:space:]]+)", $nm) ) go ("","이름을 올바로 입력해 주세요");

	if( $eml&&(!eregi("([^[:space:]]+)" , $eml) && !eregi("([_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)" , $eml))) {
		go("","E-mail 주소를 올바로 입력하세요");
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

	$result=MYSQL_QUERY('Select lv from '.$table3.' Where no="'.$member[0].'"') or DB_ERR(__FILE__.'-'.__LINE__,'해지 할 수 없습니다');
	$result=MYSQL_FETCH_ARRAY($result);
	$result[1]=MYSQL_RESULT(MYSQL_QUERY('Select count(*) from '.$table3.' Where lv="1"'),0,0);

	if($result[0]==1 and $result[1]<2) go('','해지할 수 없습니다');

	MYSQL_QUERY('Delete from '.$table3.' Where no="'.$member[0].'"');
	go('javascript:window.close()','해지가 완료되었습니다');

	exit;
} ELSE {
	REQUIRE_ONCE($set[49]."/loged.php");
}
?>