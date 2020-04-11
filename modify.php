<?

$d=mysql_fetch_array(mysql_query("Select * from ".$table1." Where no='".$no."'"));

if($d[21]>0) {
	$d_member=MYSQL_FETCH_ROW(MYSQL_QUERY("Select lv from ".$table3." Where no='".$d[21]."'"));

	if($member[4]!=1 && (($member[4]>$d_member[0]) or $member[4]!=$d[21])) go("","권한이 없습니다");
}

if($act=="modify") {

	@setcookie("cookie_write","$nm",time()+2592000,"/");
	if($member[6]!='') @setcookie("cookie_eml","$eml",time()+2592000,"/");
	if($member[7]!='') @setcookie("cookie_hmpage","$hm",time()+2592000,"/");

	$date=$d[14];
	
	if(!$nm and !$member[5]) go("","이름을 입력하세요");
	if(!$tt) go("","제목을 입력하세요");
	if(!$mm) go("","내용을 입력하세요");
	if(!$pw and $member[4]>8) go("","비밀번호를 올바로 입력하세요");

	if( $bn=="on" AND ($member[4]<=$set[24]) ) $bn=1; else $bn=0;
	if( $bs=="on" AND ($member[4]<=$set[23]) ) $bs=1; else $bs=0;
	if( $br=="on" AND ($member[4]<=$set[17]) ) $br=1; else $br=0;
	if( $brc=="on" ) $brc=1; else $brc=0;
	if( $bg=="on" AND ($member[4]<=$set[19]) ) $bg=1; else $bg=0;
	if( $bv=="on" AND ($member[4]<=$set[8]) ) $bv=1; else $bv=0;

	if( $bh=="on" AND $member[4]<=$set[10] ) $bh=1; else $bh=0;

	REQUIRE_ONCE("./scripts/write.filter.php");

	$tt=addslashes(filter($tt));
	$mm=addslashes(filter($mm));
	$a1=addslashes($a1);
	$a2=addslashes($a2);

	if(!$cg or $cg=="") $cg="0";

	if($d_member[0]==1) {// 관리자의 글일 경우

		if($member[4]!=1) go("","관리자의 글은 관리자가 수정할 수 있습니다");
		INCLUDE_ONCE("scripts/write.file.php");
		$sresult=msbbs_file();

		mysql_query("LOCK TABLES ".$table1." WRITE") or DB_ERR(__FILE__."-".__LINE__);

		mysql_query("UPDATE ".$table1." set
		cg='$cg', nm='$member[5]'
		, eml='$member[6]', hm='$hm'
		, tt='$tt', mm='$mm'
		, m_i='".getenv("remote_addr")."', m_d='".time()."'
		, h2='$sresult[4]', a1='$a1'
		, a2='$a2', bm='$member[0]'
		, bn='$bn', bs='$bs'
		, br='$br', brc='$brc'
		, bg='$bg', bh='$bh'
		, bv='$bv', fn='$sresult[0]'
		, fx='$sresult[1]', ft='$sresult[2]'
		, fs='$sresult[3]'
		
		Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);

		mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

//		go("./?id=".$id."&bo=view&no=".$no,"");

	} elseif($d_member[0]<"5" and $d_member[0]!="") {// 멤버의 글일 경우

		if($member[4]>4) go("","권한이 없습니다".__line__);

		if($member[4]==1) {// 관리자가 수정하려고 할떄
			INCLUDE_ONCE("scripts/write.file.php");
			$sresult=msbbs_file();

			mysql_query("LOCK TABLES ".$table1." WRITE") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UPDATE ".$table1." set
			cg='$cg', nm='$member[5]'
			, eml='$member[6]', hm='$hm'
			, tt='$tt', mm='$mm'
			, m_i='".getenv("remote_addr")."', m_d='".time()."'
			, h2='$h2', a1='$a1'
			, a2='$a2', bm='$member[0]'
			, bn='$bn', bs='$bs'
			, br='$br', brc='$brc'
			, bg='$bg', bh='$bh'
			, bv='$bv', fn='$sresult[0]'
			, fx='$sresult[1]', ft='$sresult[2]'
			, fs='$sresult[3]'
			
			Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);
		} elseif($member[0]==$set[3]) {// 게시판 관리자가 수정하려고 할때
			INCLUDE_ONCE("scripts/write.file.php");
			$sresult=msbbs_file();

			mysql_query("LOCK TABLES ".$table1." WRITE") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UPDATE ".$table1." set
			cg='$cg', nm='$member[5]'
			, eml='$member[6]', hm='$hm'
			, tt='$tt', mm='$mm'
			, m_i='".getenv("remote_addr")."', m_d='".time()."'
			, h2='$h2', a1='$a1'
			, a2='$a2', bm='$member[0]'
			, bn='$bn', bs='$bs'
			, br='$br', brc='$brc'
			, bg='$bg', bh='$bh'
			, bv='$bv', fn='$sresult[0]'
			, fx='$sresult[1]', ft='$sresult[2]'
			, fs='$sresult[3]'

		Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

		} elseif($member[4]<"5") { // 멤버가 수정하려고 할때
			INCLUDE_ONCE("scripts/write.file.php");
			$sresult=msbbs_file();
			if($member[0]!=$d_member[no]) go("","권한이 없습니다");

			mysql_query("LOCK TABLES ".$table1." WRITE") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UPDATE ".$table1." set

			cg='$cg', nm='$member[5]'
			, eml='$member[6]', hm='$hm', tt='$tt', mm='$mm', m_i='".getenv("remote_addr")."'
			, m_d='".time()."', h2='$h2', a1='$a1'
			, a2='$a2', bm='$member[0]'
			, bn='$bn', bs='$bs'
			, br='$br', brc='$brc'
			, bg='$bg', bh='$bh'
			, bv='$bv', fn='$sresult[0]'
			, fx='$sresult[1]', ft='$sresult[2]'
			, fs='$sresult[3]'
			
			Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
		} else {// 게스트가 수정하려고 할때?
			go("","권한이 없습니다");
		}

//		go("./?id=".$id."&bo=view&no=".$no,"");

	} else {// 게스트의 글일 경우
		if($member[4]=="1") {// 관리자가 수정하려고 할떄
			INCLUDE_ONCE("scripts/write.file.php");
			$sresult=msbbs_file();

			mysql_query("LOCK TABLES ".$table1." WRITE") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UPDATE ".$table1." set
			cg='$cg', nm='$member[5]'
			, eml='$member[6]', hm='$hm', tt='$tt', mm='$mm', m_i='".getenv("remote_addr")."'
			, m_d='".time()."', h2='$h2', a1='$a1'
			, a2='$a2', bm='$member[0]'
			, bn='$bn', bs='$bs'
			, br='$br', brc='$brc'
			, bg='$bg', bh='$bh'
			, bv='$bv', fn='$sresult[0]'
			, fx='$sresult[1]', ft='$sresult[2]'
			, fs='$sresult[3]'
			
			Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

		} elseif($member[0]==$set[3]) {// 게시판 관리자가 수정하려고 할때
			INCLUDE_ONCE("scripts/write.file.php");
			$sresult=msbbs_file();

			mysql_query("LOCK TABLES ".$table1." WRITE") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UPDATE ".$table1." set
			cg='$cg', nm='$member[5]'
			, eml='$member[6]', hm='$hm', tt='$tt', mm='$mm', m_i='".getenv("remote_addr")."'
			, m_d='".time()."', h2='$h2', a1='$a1'
			, a2='$a2', bm='$member[0]'
			, bn='$bn', bs='$bs'
			, br='$br', brc='$brc'
			, bg='$bg', bh='$bh'
			, bv='$bv', fn='$sresult[0]'
			, fx='$sresult[1]', ft='$sresult[2]'
			, fs='$sresult[3]'
			
			Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
		} else {
			INCLUDE_ONCE("scripts/write.file.php");
			$sresult=msbbs_file();

			mysql_query("LOCK TABLES ".$table1." WRITE") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UPDATE ".$table1." set
			cg='$cg', nm='$nm'
			, eml='$member[6]', hm='$hm'
			, tt='$tt', mm='$mm'
			, pw='$pw', m_i='".getenv("remote_addr")."'
			, m_d='".time()."', h2='$h2'
			, a1='$a1', a2='$a2'
			, bn='$bn', bs='$bs'
			, br='$br', brc='$brc'
			, bg='$bg', bh='$bh'
			, bv='$bv', fn='$sresult[0]'
			, fx='$sresult[1]', ft='$sresult[2]'
			, fs='$sresult[3]' Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);

			mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
		}
	}

	go("./?id=".$id."&bo=view&no=".$no,"");

} else {

	REQUIRE_ONCE($set[49]."/modify.php");

}

?>