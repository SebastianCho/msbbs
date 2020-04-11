<?

if($act=="sview") {//비번 체크후 삭제
	if(!$pw) { go("","비밀번호를 입력하세요"); }

	$d=MYSQL_QUERY("SELECT * FROM ".$table4." WHERE tn='".$no."' and no='".$n."' LIMIT 1") or DB_ERR(__FILE__."-".__LINE__);
	$d=MYSQL_FETCH_ROW($d);

	$pw=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT password('".$pw."')")) or DB_ERR(__FILE__."-".__LINE__);
	$pw=$pw[0];

	if($d[6]>0) {
		$d_member=MYSQL_RESULT(MYSQL_QUERY("SELECT lv FROM ".$table3." WHERE no='".$d[6]."'"),0,0);

		if($member[0]==0) { go("","권한이 없습니다"); }
		if($member[4]!=1 and $member[0]!=$set[3] and $member[0]!=$d[6]) {
			go("","권한이 없습니다");
		}

		MYSQL_QUERY("LOCK TABLES ".$table1." WRITE,".$table4." WRITE") or DB_ERR(__FILE__."-".__LINE__);
		MYSQL_QUERY("Delete FROM ".$table4." WHERE tn='".$no."' and no='".$n."'") or DB_ERR(__FILE__."-".__LINE__);
		MYSQL_QUERY("UPDATE ".$table1." set cn=cn-1 WHERE no='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
		MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

		go($HTTP_REFERER,"삭제 완료");

	} else {
		if($pw==$d[7]) {

			MYSQL_QUERY("LOCK TABLES ".$table4." WRITE") or DB_ERR(__FILE__."-".__LINE__);
			MYSQL_QUERY("Delete FROM ".$table4." WHERE tn='".$no."' and no='".$n."'") or DB_ERR(__FILE__."-".__LINE__);
			MYSQL_QUERY("UPDATE ".$table1." set cn=cn-1 WHERE no='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
			MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

			go("./?id=".$id."&bo=view&no=".$no,"삭제 완료");
		} else {
			go('',"비밀번호가 맞지 않습니다");
		}
	}

} elseif($act=="delete") {

	$d=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT * FROM ".$table4." WHERE tn='".$no."' and no='".$n."'"));

	if($d[6]>0) {//코멘트 쓴 사람이 멤버일 때
		$d_member=mysql_result(MYSQL_QUERY("SELECT lv FROM ".$table3." WHERE no='".$d[6]."'"),0,0);

		if($member[0]=="0") { go("","권한이 없습니다"); }
		if($member[4]!="1" and $member[0]!=$set[3] and $member[0]!=$d[6]) {
			go("","권한이 없습니다");
		}

		MYSQL_QUERY("LOCK TABLES ".$table4." WRITE") or DB_ERR(__FILE__."-".__LINE__);
		MYSQL_QUERY("Delete FROM ".$table4." WHERE tn='".$no."' and no='".$n."'") or DB_ERR(__FILE__."-".__LINE__);
		MYSQL_QUERY("UPDATE ".$table1." set cn=cn-1 WHERE no='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
		MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
		go($HTTP_REFERER,"");

	} else {

		start();
		$d[0]=$no;
		INCLUDE_ONCE($set[49]."/password.php");
		foot();
		exit;
	}

}

?>