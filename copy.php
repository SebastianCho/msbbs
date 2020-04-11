<?
IF( $act=='copy') {
	$cart=explode('-',$cart);
	IF( $cart[0]=='' ) go('','글 번호를 선택해 주세요');
	IF( !$target ) go('','복사할 게시판을 선택하세요');

	For($i=0;$i<count($cart);$i++) {
		$cart[$i]='no="'.$cart[$i].'"';
	}
	
	$result=MYSQL_QUERY('SELECT * FROM '.$table2.' WHERE id="'.$target.'"') or DB_ERR(__FILE__."-".__LINE__);
	IF( MYSQL_AFFECTED_ROWS()!=1 ) go('','복사할 게시판이 없습니다');

	$result_s=MYSQL_QUERY('SELECT * FROM '.$table1.' WHERE '.implode(' or ',$cart).' ORDER BY no asc') or DB_ERR(__FILE__."-".__LINE__);
	IF( MYSQL_AFFECTED_ROWS()<1 ) go('','복사할 게시물 추출중에 에러가 났습니다');

	while( $d=MYSQL_FETCH_ROW($result_s) ) {

		if($d[0]>0) {
			$date=time();

			if($d[22]==1) {// 공지사항일 떄
				$result=@MYSQL_RESULT(@MYSQL_QUERY("SELECT count(*) FROM msbi_".$target." WHERE idx='1'"),0,0);

				if($result>0) {// 공지사항의 범위.. 즉, idx='1' 의 값이 존재할때
					$result=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT idx,main FROM msbi_".$target." WHERE idx='1'"));
					$result[1]-=1;

					MYSQL_QUERY("UPDATE msbi_".$target." set idx='1', main='".$result[1]."' WHERE idx='1'");
				} else {// idx='1' 의 게시물이 존재하지 않을 때
					MYSQL_QUERY("INSERT into msbi_".$target." values('1','10009','0')") or DB_ERR(__FILE__."-".__LINE__);
					$result[0]=1;
					$result[1]=10009;
				}
			} else {
				$result=MYSQL_QUERY("SELECT min(idx) FROM msbi_".$target." WHERE idx>1") or DB_ERR(__FILE__."-".__LINE__);
				$result=MYSQL_RESULT($result,0,0);

				$result=MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM msbi_".$target." WHERE idx='".$result."'"));
				$result[1]-=1;

				if($result[1]<10) {
					$result[0]-=1;
					$result[1]="10010";
					MYSQL_QUERY("INSERT into msbi_".$target." values('".$result[0]."','".$result[1]."','".$result[2]."')") or DB_ERR(__FILE__."-".__LINE__);
				} else {
					MYSQL_QUERY("UPDATE msbi_".$target." set idx='".$result[0]."', main='".$result[1]."', rid='".$result[2]."' WHERE idx='".$result[0]."'") or DB_ERR(__FILE__."-".__LINE__);
				}
			}

			MYSQL_QUERY('LOCK TABLES msb_'.$target.' WRITE') or DB_ERR(__FILE__."-".__LINE__);

			MYSQL_QUERY('INSERT INTO msb_'.$target.'
			
				(idx,main
				,rid,sno
				,cg,cn
				,nm,eml
				,hm,tt
				,mm,pw
				,w_i,w_d
				,h1,h2
				,a1,a2
				,bm,bn
				,bs,br
				,brc,bg
				,bh,bv
				,fn,fx
				,ft,fs)

				values('.$result[0].','.$result[1].'
				,"'.$d[3].'","'.$d[4].'"
				,"'.$d[5].'","'.$d[6].'"
				,"'.$d[7].'","'.$d[8].'"
				,"'.$d[9].'","'.$d[10].'"
				,"'.$d[11].'","'.$d[12].'"
				,"'.getenv("remote_addr").'","'.$date.'"
				,"'.$d[17].'","'.$d[18].'"
				,"'.$d[19].'","'.$d[20].'"
				,"'.$d[21].'","'.$d[22].'"
				,"'.$d[23].'","'.$d[24].'"
				,"'.$d[25].'","'.$d[26].'"
				,"'.$d[27].'","'.$d[28].'"
				,"'.$d[29].'","'.$d[30].'"
				,"'.$d[31].'","'.$d[32].'")
				
				') or DB_ERR(__FILE__."-".__LINE__);

				MYSQL_QUERY('UNLOCK TABLES') or DB_ERR(__FILE__."-".__LINE__);
		}

		$d[29]=explode(",",$d[29]);
		$d[30]=explode(",",$d[30]);

		if( $d[29][0]!='' && count($d[29])>0 ) {
			@mkdir('./data/'.$date.'/',0707);

			for( $i=0 ; $i<=count($d[29]) ; $i++) {
				@copy(
				'./data/'.$d[14].'/'.$d[29][$i].'.'.$d[30][$i]
				,'./data/'.$date.'/'.$d[29][$i].'.'.$d[30][$i]);
			}
		}
	}

	go('./?id='.$id,'');


} ELSE {
	IF( !$no && !$cart ) go('','글 번호를 선택해 주세요');
	$cart[]=$no;
	REQUIRE_ONCE('./'.$set[49].'/copy.php');
}
?>