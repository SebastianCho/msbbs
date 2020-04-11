<?

IF($act=="find") {

	if(!$u_id) go("","ID 를 입력하세요");
	if(!$eml) go("","E-mail 주소를 올바로 입력하세요");

	$d=MYSQL_QUERY("Select no,pw,eml from ".$table3." WHERE id='".$u_id."' AND eml='".addslashes($eml)."'");
	if( MYSQL_AFFECTED_ROWS() < 1 ) {
		go("","맞는 데이터가 없습니다");
	}

	$d=MYSQL_FETCH_ROW($d);

	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("Select password('$d[1]')"));

	MYSQL_QUERY("UPDATE ".$table3." set pw=password('".$result[0]."') WHERE no='".$d[0]."'") or DB_ERR(__FILE__.".".__LINE__);
	
	/******************************************************************
	From PHPSCHOOL.com 쩝..
	******************************************************************/

	$fromaddress=$member[6];
	$toaddress=$d[2];
	$subject ="Msbbs Password";
	$body = $result[0];

	REQUIRE_ONCE("./scripts/sendmail.php");

	@nmail($fromaddress, $toaddress, $subject, $body, $headers);

	go("","./?id=".$id);

} ELSEIF($act=="join") {

	if( !$id ) go("","ID 를 입력해 주세요");
	if( !$nm ) go("","이름(혹은 닉네임)을 입력해 주세요");
	if( !$eml ) go("","Email 주소를 입력해 주세요\n\n비밀번호 찾기에 꼭 필요합니다");
	if( !$u_pw or !$u_pw2 ) go("","비밀번호를 입력해 주세요");
	if( $u_pw!=$u_pw2 ) go("","비밀번호를 올바로 입력하세요");

	if( strlen($id) > 12 ) go("","아이디 글자수가 너무 작거나 깁니다");
	if( !eregi("([\_0-9A-Z])",$id) ) go("","아이디는 숫자와 영문 소문자와 언더스코어의 조합으로 이루어져야 합니다");
	if( !eregi("([^[:space:]]+)", $nm) ) go ("","이름을 올바로 입력해 주세요");

	if( $eml&&(!eregi("([^[:space:]]+)" , $eml) && !eregi("([_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)" , $eml))) {
		go("","E-mail 주소를 올바로 입력하세요");
	}

	if($i_o=="on") { $i_o="1"; } else { $i_o="0"; }

	if(!$birth) $birth="";

	$res=MYSQL_QUERY("SELECT no from ".$table3." Where id='".$u_id."'");
	if(MYSQL_AFFECTED_ROWS()>0) go("","이미 똑같은 아이디가 존재합니다".MYSQL_AFFECTED_ROWS());

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
		go("","오류가 발생했습니다.잠시후에 다시 시도해 주십시오.");
	}

} ELSE {

	REQUIRE_ONCE($set[49]."/find.php");

}

?>