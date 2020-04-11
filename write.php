<?
if($act=="write") {

	@setcookie("cookie_write","$nm",time()+2592000,"/");
	if($member[6]!='') @setcookie("cookie_eml","$eml",time()+2592000,"/");
	if($member[7]!='') @setcookie("cookie_hmpage","$hm",time()+2592000,"/");
	
	if(!$nm AND !$member[nm]) go("","이름을 입력하세요");
	if(!$tt) go("","제목을 입력하세요");
	if(!$mm) go("","내용을 입력하세요");
	if(!$pw And $member[4]>4) go("","비밀번호를 올바로 입력하세요");

	$date=time();

	if( $bs=="on" AND ($member[4]<=$set[23]) ) $bs=1; else $bs=0;
	if( $br=="on" AND ($member[4]<=$set[17]) ) $br=1; else $br=0;
	if( $brc=="on" ) $brc=1; else $brc=0;
	if( $bg=="on" AND ($member[4]<=$set[19]) ) $bg=1; else $bg=0;
	if( $bv=="on" AND ($member[4]<=$set[8]) ) $bv=1; else $bv=0;

	if( $bh=="on" AND $member[4]<=$set[10] ) $bh=1; else $bh=0;

	REQUIRE_ONCE("./scripts/write.filter.php");

	$tt=addslashes(filter($tt));
	$mm=addslashes(filter($mm));

	REQUIRE_ONCE("./scripts/write.file.php");

	$sfile=msbbs_file();

	$result=@MYSQL_QUERY("SELECT count(*) FROM ".$table1." WHERE tt='".$tt."' and mm='".$mm."' and w_d>".(time()-3600));
	$result=@MYSQL_FETCH_ROW($result);
	if($result[0]>2) go("","똑같은 글을 연속적으로 쓸 수 없습니다");

	$res=@MYSQL_QUERY("SELECT min(sno) FROM $table1 WHERE idx<901");
	$res=MYSQL_FETCH_ROW($res) or DB_ERR(__FILE__."-".__LINE__);
	$res[0]-=1;

	if(!$cg or $cg=="") $cg="0";

	unset($result);

	mysql_query("LOCK TABLES ".$table1." WRITE, ".$table5." WRITE") or DB_ERR(__FILE__."-".__LINE__);

	if( $bn=="on" AND $member[4]<=$set[24] ) {// 공지사항 체크했고 공지사항 쓸 수 있는 레벨일때
		$bn=1;
		$result=@MYSQL_RESULT(@MYSQL_QUERY("SELECT count(*) FROM ".$table5." WHERE idx='1'"),0,0);

		if($result>0) {// 공지사항의 범위.. 즉, idx='1' 의 값이 존재할때
			$result=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT idx,main FROM ".$table5." WHERE idx='1'"));
			$result[1]-=1;

			MYSQL_QUERY("UPDATE ".$table5." set idx='1', main='".$result[1]."' WHERE idx='1'");
		} else {// idx='1' 의 게시물이 존재하지 않을 때
			MYSQL_QUERY("INSERT into ".$table5." values('1','10009','0')") or DB_ERR(__FILE__."-".__LINE__);
			$result[0]=1;
			$result[1]=10009;
		}

	} else {// 공지사항 아닐 떄
		$bn="0";
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

	}

	if($member[0]>0) {

		MYSQL_QUERY("INSERT INTO $table1

		(no
		,idx
		,main
		,rid
		,sno
		,cg
		,nm
		,tt
		,mm
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

		values(''						/*no*/
		,'".$result[0]."'				/*idx*/
		,'".$result[1]."'				/*main*/
		,'a'							/*rid*/
		,'$res[0]'						/*sno*/
		,'$cg'							/*cg*/
		,'".addslashes($member[5])."'	/*nm*/
		,'$tt'							/*tt*/
		,'$mm'							/*mm*/
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

	} else {
		$nm=addslashes($nm);
		$eml=addslashes($eml);
		$hm=addslashes($hm);

		MYSQL_QUERY("INSERT INTO $table1
		(no ,idx
		,main ,rid
		,sno ,cg
		,nm ,eml
		,hm ,tt
		,mm ,pw
		,w_i ,w_d
		,h2 ,bn ,bs
		,br ,brc
		,bg ,bh
		,bv ,fn
		,fx ,ft
		,fs)

		values(''						/*no*/
		,'$result[0]'					/*idx*/
		,'$result[1]'					/*main*/
		,'a'							/*rid*/
		,'$res[0]'						/*sno*/
		,'$cg'							/*cg*/
		,'$nm'							/*nm*/
		,'$eml'							/*eml*/
		,'$hm'							/*hm*/
		,'$tt'							/*tt*/
		,'$mm'							/*mm*/
		,password('$pw')				/*pw*/
		,'".getenv("remote_addr")."'	/*w_i*/
		,'$date'						/*w_d*/
		,'$sfile[4]'					/*h2*/
		,'$bn'							/*bn*/
		,'$bs'							/*sc*/
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

	mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);


	go("./?id=".$id,"");

} else {

	REQUIRE_ONCE($set[49]."/write.php");

}

?>