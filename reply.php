<?
if(!$no) go("","원글을 지정해 주세요");

if($act=="reply") {

	if(!$nm and !$member[5]) go("","이름을 입력하세요");
	if(!$tt) go("","제목을 입력하세요");
	if(!$mm) go("","내용을 입력하세요");
	if(!$pw and $member[4]<5) go("","비밀번호를 올바로 입력하세요");

	@setcookie("cookie_write","$nm",time()+2592000,"/");
	if($member[6]!='') @setcookie("cookie_eml","$eml",time()+2592000,"/");
	if($member[7]!='') @setcookie("cookie_hmpage","$hm",time()+2592000,"/");

	if( $bs=="on" AND ($member[4]<=$set[23]) ) $bs=1; else $bs=0;
	if( $br=="on" AND ($member[4]<=$set[17]) ) $br=1; else $br=0;
	if( $brc=="on" ) $brc=1; else $brc=0;
	if( $bg=="on" AND ($member[4]<=$set[19]) ) $bg=1; else $bg=0;
	if( $bv=="on" AND ($member[4]<=$set[8]) ) $bv=1; else $bv=0;
	if( $bh=="on" AND $member[4]<=$set[10] ) $bh=1; else $bh=0;

	REQUIRE_ONCE("./scripts/write.filter.php");
	INCLUDE_ONCE("./scripts/write.file.php");

	$date=time();

	$tt=addslashes(filter($tt));
	$mm=addslashes(filter($mm));

	if( !$cg || $cg=='' ) {
		$cg=0;
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	//새로운 thread 값 구하는 것~ 힘드료....ㅜㅡ
	$xx=mysql_fetch_array(mysql_query("Select idx,main,rid,sno from $table1 Where no='".$no."'")) or DB_ERR(__FILE__."-".__LINE__);

	$result=mysql_query("Select rid,right(rid,1) from $table1 Where sno='".$xx[3]."' and length(rid) = length('".$xx[2]."')+1 and locate('".$xx[2]."',rid) = 1 order by rid desc limit 1") or DB_ERR(__FILE__."-".__LINE__);

	$rows=mysql_num_rows($result);

	if($rows) {
		$row=mysql_fetch_array($result);
		$thread_head=substr($row[0],0,-1);
		$thread_foot=++$row[1];
		$new_thread=$thread_head.$thread_foot;
	} else {
		$new_thread=$xx[2]."a";
	}

	mysql_query("LOCK TABLES ".$table1." WRITE, ".$table5." WRITE") or DB_ERR(__FILE__."-".__LINE__);

	MYSQL_QUERY("UPDATE ".$table5." set rid=rid-1 WHERE idx='".$xx[0]."'") or DB_ERR(__FILE__."-".__LINE__);


	if($member[0]>0) {
		$nm=addslashes($member[5]);
		$eml=addslashes($member[6]);
		$hm=addslashes($member[7]);

		REQUIRE_ONCE("./scripts/write.file.php");
		$result=msbbs_file();

		mysql_query("Insert into ".$table1."

		(idx,main
		,rid,sno
		,cg,nm
		,eml,hm
		,tt,mm
		,w_i,w_d,h2
		,bm,bs
		,br,brc
		,bg,bh
		,bv,fn
		,fx,ft
		,fs)

		values('$xx[0]','$xx[1]'
		,'$new_thread','$d[4]'
		,'$cg','$nm'
		,'$eml','$hm'
		,'$tt','$mm'
		,'".getenv("remote_addr")."','$date','$result[4]'
		,'$member[0]', '$bs'
		,'$br', '$brc'
		,'$bg','$bh'
		,'$bv','$result[0]'
		,'$result[1]','$result[2]'
		,'$result[3]')

		") or DB_ERR(__FILE__."-".__LINE__);

	} else {
		$nm=addslashes($nm);
		$eml=addslashes($eml);
		$hm=addslashes($hm);

		mysql_query("Insert into $table1

		(idx,main
		,rid,sno
		,cg,nm
		,eml,hm
		,tt,mm
		,pw,w_i
		,w_d,h2
		,bs
		,br,brc
		,bg,bh
		,bv,fn
		,fx,ft
		,fs)

		values('$xx[0]','$xx[1]'
		,'$new_thread','$d[4]'
		,'$cg','$nm'
		,'$eml','$hm'
		,'$tt','$mm'
		,'$pw','".getenv("remote_addr")."'
		,'$date','$result[4]'
		,'$bs'
		,'$br','$brc'
		,'$bg','$bh'
		,'$bv','$result[0]'
		,'$result[1]','$result[2]'
		,'$result[3]')

		") or DB_ERR(__FILE__."-".__LINE__);

	}

	mysql_query("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

	IF($dbconn) {
		mysql_close($dbconn);
		unset($dbconn);
	}
	HEADER("location:./?id=".$id,"");

} else {

	REQUIRE_ONCE($set[49]."/reply.php");

}

?>