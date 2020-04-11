<?

IF(!$no && !is_array($cart)) {
	go("","게시물을 지정하세요");
}

$cart[]=$no;
unset($no);

WHILE( list($value,$label)=each($cart) ) {

	IF($label>0) {

		$no=$label;

		$d=@MYSQL_QUERY("SELECT no,idx,main,rid,bm,pw,w_d,fn,fx FROM $table1 WHERE no='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
		IF(MYSQL_AFFECTED_ROWS()<1) go('','지정하신 게시물이 존재하지 않습니다');
		$d=mysql_fetch_array($d);
		$d[7]=explode(",",$d[7]);
		$d[8]=explode(",",$d[8]);

		$path="./data/".$d[6]."/";

		if( $d[4]>0 ) {
			$d_member=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT lv from $table3 Where no='$d[4]'"));
		} else {
			$d_member[0]=9;
		}

		if( $d_member[0]==1 ) {
			if( $member[4]!=1 ) go("","권한이 없습니다");

			//삭제
			if( count($d[7])>1 ) {//복수데이터 존재
				for($i=0;$i<count($d[7]);$i++) {
					if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
						@unlink( $path.$d[7][$i].".".$d[8][$i] );
					}
				}
				@rmdir($path);

			} elseif( count($d[7])==1 ) {//데이터가 하나만 존재
				if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
					@unlink($path.$d[7][0].".".$d[8][0]);
					@rmdir($path);
				}
			}

		/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

			if($result[0]>1) {//복수데이터 존재
				$result=MYSQL_QUERY("Select no from $table4 Where tn='$no'") or DB_ERR(__FILE__."-".__LINE__);

				MYSQL_QUERY("LOCK TABLES $table4 WRITE") or DB_ERR(__FILE__."-".__LINE__);

				while($res=mysql_result($result,0,0)) {
					MYSQL_QUERY("Delete from $table4 Where tn='$no' and no='$res'");
				}

				MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
			} elseif($result[0]=="1") {*/
			MYSQL_QUERY("LOCK TABLES $table1 WRITE,$table4 WRITE,$table5 WRITE") or DB_ERR(__FILE__."-".__LINE__);
			MYSQL_QUERY("Delete from $table4 Where tn='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
		//	MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
		//	}

		//	MYSQL_QUERY("LOCK TABLES $table1 WRITE") or DB_ERR(__FILE__."-".__LINE__);
			MYSQL_QUERY("Delete from $table1 Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);
			IF( strlen($d[3])>1 ) {
				MYSQL_QUERY("UPDATE $table5 set rid=rid+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
			} ELSE {
				MYSQL_QUERY("UPDATE $table5 set main=main+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
			}
			MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

		} elseif($d_member[0]<9) {
			if($member[4]==9) go("","권한이 없습니다");

			if($member[0]==$set[3] or $member[0]==$d[4]) {
				
				//삭제
				if( count($d[7])>1 ) {//복수데이터 존재
					for($i=0;$i<count($d[7]);$i++) {
						if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
							@unlink( $path.$d[7][$i].".".$d[8][$i] );
						}
					}
					@rmdir($path);

				} elseif( count($d[7])==1 ) {//데이터가 하나만 존재
					if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
						@unlink($path.$d[7][0].".".$d[8][0]);
						@rmdir($path);
					}
				}

			/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

				if($result[0]>1) {//복수데이터 존재
					$result=MYSQL_QUERY("Select no from $table4 Where tn='$no'") or DB_ERR(__FILE__."-".__LINE__);

					MYSQL_QUERY("LOCK TABLES $table4 WRITE") or DB_ERR(__FILE__."-".__LINE__);

					while($res=mysql_result($result,0,0)) {
						MYSQL_QUERY("Delete from $table4 Where tn='$no' and no='$res'");
					}

					MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
				} elseif($result[0]=="1") {*/
				MYSQL_QUERY("LOCK TABLES $table1 WRITE,$table4 WRITE,$table5 WRITE") or DB_ERR(__FILE__."-".__LINE__);
				MYSQL_QUERY("Delete from $table4 Where tn='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
			//	MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
			//	}

			//	MYSQL_QUERY("LOCK TABLES $table1 WRITE") or DB_ERR(__FILE__."-".__LINE__);
				IF( strlen($d[3])>1 ) {
					MYSQL_QUERY("UPDATE $table5 set rid=rid+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
				} ELSE {
					MYSQL_QUERY("UPDATE $table5 set main=main+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
				}
				MYSQL_QUERY("Delete from $table1 Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);
				MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

			} else {
				//뒤로 빠꾸;
				go("","권한이 없습니다");
			}
		} else {
			if($act=='sview') {
				//비번 체크후 삭제 여부 결정
				if(!$pw) go("","비밀번호를 입력하셔야죠");

				$pw=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT password('".$pw."')"));
				$pw=$pw[0];

				if($pw!=$d[5]) go("","비밀번호가 맞지 않습니다");

				//삭제
				if( count($d[7])>1 ) {//복수데이터 존재
					for($i=0;$i<count($d[7]);$i++) {
						if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
							@unlink( $path.$d[7][$i].".".$d[8][$i] );
						}
					}
					@rmdir($path);
				} elseif( count($d[7])==1 ) {//데이터가 하나만 존재
					if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
						@unlink($path.$d[7][0].".".$d[8][0]);
						@rmdir($path);
					}
				}

			/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

				if($result[0]>1) {//복수데이터 존재
					$result=MYSQL_QUERY("Select no from $table4 Where tn='$no'") or DB_ERR(__FILE__."-".__LINE__);

					MYSQL_QUERY("LOCK TABLES $table4 WRITE") or DB_ERR(__FILE__."-".__LINE__);

					while($res=mysql_result($result,0,0)) {
						MYSQL_QUERY("Delete from $table4 Where tn='$no' and no='$res'");
					}

					MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
				} elseif($result[0]=="1") {*/
				MYSQL_QUERY("LOCK TABLES $table1 WRITE,$table4 WRITE,$table5 WRITE") or DB_ERR(__FILE__."-".__LINE__);
				MYSQL_QUERY("Delete from $table4 Where tn='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
			//	MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
			//	}

			//	MYSQL_QUERY("LOCK TABLES $table1 WRITE") or DB_ERR(__FILE__."-".__LINE__);
				MYSQL_QUERY("Delete from $table1 Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);
				IF( strlen($d[3])>1 ) {
					MYSQL_QUERY("UPDATE $table5 set rid=rid+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
				} ELSE {
					MYSQL_QUERY("UPDATE $table5 set main=main+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
				}
				MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

			} elseif($member[4]==1 OR ($member[0]==$set[3] AND $member[0]!="0")) {

				//삭제
				if( count($d[7])>1 ) {//복수데이터 존재
					for($i=0;$i<count($d[7]);$i++) {
						if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
							@unlink( $path.$d[7][$i].".".$d[8][$i] );
						}
					}
					@rmdir($path);
				} elseif( count($d[7])==1 ) {//데이터가 하나만 존재
					if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
						@unlink($path.$d[7][0].".".$d[8][0]);
						@rmdir($path);
					}
				}

			/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

				if($result[0]>1) {//복수데이터 존재
					$result=MYSQL_QUERY("Select no from $table4 Where tn='$no'") or DB_ERR(__FILE__."-".__LINE__);

					MYSQL_QUERY("LOCK TABLES $table4 WRITE") or DB_ERR(__FILE__."-".__LINE__);

					while($res=mysql_result($result,0,0)) {
						MYSQL_QUERY("Delete from $table4 Where tn='$no' and no='$res'");
					}

					MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
				} elseif($result[0]=="1") {*/
				MYSQL_QUERY("LOCK TABLES $table1,$table4 WRITE") or DB_ERR(__FILE__."-".__LINE__);
				MYSQL_QUERY("Delete from $table4 Where tn='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
			//	MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);
			//	}

			//	MYSQL_QUERY("LOCK TABLES $table1 WRITE") or DB_ERR(__FILE__."-".__LINE__);
				MYSQL_QUERY("Delete from $table1 Where no='$no'") or DB_ERR(__FILE__."-".__LINE__);
				IF( strlen($d[3])>1 ) {
					MYSQL_QUERY("UPDATE $table5 set rid=rid+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
				} ELSE {
					MYSQL_QUERY("UPDATE $table5 set main=main+1 Where idx='$d[1]'") or DB_ERR(__FILE__."-".__LINE__);
				}
				MYSQL_QUERY("UNLOCK TABLES") or DB_ERR(__FILE__."-".__LINE__);

			} ELSE {
				IF($act!='all') {
					start();
					//비번 체크
					REQUIRE_ONCE($set[49]."/password.php");
					foot();
					exit;
				}
			}

		}
	}

	unset($no);
}

go("./?id=".$id."&page=".$page,"");

mysql_close($dbconn);

?>