<?

IF(!$no && !is_array($cart)) {
	go("","�Խù��� �����ϼ���");
}

$cart[]=$no;
unset($no);

WHILE( list($value,$label)=each($cart) ) {

	IF($label>0) {

		$no=$label;

		$d=@MYSQL_QUERY("SELECT no,idx,main,rid,bm,pw,w_d,fn,fx FROM $table1 WHERE no='".$no."'") or DB_ERR(__FILE__."-".__LINE__);
		IF(MYSQL_AFFECTED_ROWS()<1) go('','�����Ͻ� �Խù��� �������� �ʽ��ϴ�');
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
			if( $member[4]!=1 ) go("","������ �����ϴ�");

			//����
			if( count($d[7])>1 ) {//���������� ����
				for($i=0;$i<count($d[7]);$i++) {
					if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
						@unlink( $path.$d[7][$i].".".$d[8][$i] );
					}
				}
				@rmdir($path);

			} elseif( count($d[7])==1 ) {//�����Ͱ� �ϳ��� ����
				if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
					@unlink($path.$d[7][0].".".$d[8][0]);
					@rmdir($path);
				}
			}

		/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

			if($result[0]>1) {//���������� ����
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
			if($member[4]==9) go("","������ �����ϴ�");

			if($member[0]==$set[3] or $member[0]==$d[4]) {
				
				//����
				if( count($d[7])>1 ) {//���������� ����
					for($i=0;$i<count($d[7]);$i++) {
						if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
							@unlink( $path.$d[7][$i].".".$d[8][$i] );
						}
					}
					@rmdir($path);

				} elseif( count($d[7])==1 ) {//�����Ͱ� �ϳ��� ����
					if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
						@unlink($path.$d[7][0].".".$d[8][0]);
						@rmdir($path);
					}
				}

			/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

				if($result[0]>1) {//���������� ����
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
				//�ڷ� ����;
				go("","������ �����ϴ�");
			}
		} else {
			if($act=='sview') {
				//��� üũ�� ���� ���� ����
				if(!$pw) go("","��й�ȣ�� �Է��ϼž���");

				$pw=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT password('".$pw."')"));
				$pw=$pw[0];

				if($pw!=$d[5]) go("","��й�ȣ�� ���� �ʽ��ϴ�");

				//����
				if( count($d[7])>1 ) {//���������� ����
					for($i=0;$i<count($d[7]);$i++) {
						if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
							@unlink( $path.$d[7][$i].".".$d[8][$i] );
						}
					}
					@rmdir($path);
				} elseif( count($d[7])==1 ) {//�����Ͱ� �ϳ��� ����
					if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
						@unlink($path.$d[7][0].".".$d[8][0]);
						@rmdir($path);
					}
				}

			/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

				if($result[0]>1) {//���������� ����
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

				//����
				if( count($d[7])>1 ) {//���������� ����
					for($i=0;$i<count($d[7]);$i++) {
						if( file_exists( $path.$d[7][$i].".".$d[8][$i] ) ) {
							@unlink( $path.$d[7][$i].".".$d[8][$i] );
						}
					}
					@rmdir($path);
				} elseif( count($d[7])==1 ) {//�����Ͱ� �ϳ��� ����
					if( file_exists($path.$d[7][0].".".$d[8][0]) ) {
						@unlink($path.$d[7][0].".".$d[8][0]);
						@rmdir($path);
					}
				}

			/*	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM $table4 WHERE tn='$no'"));

				if($result[0]>1) {//���������� ����
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
					//��� üũ
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