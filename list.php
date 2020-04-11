<?

FUNCTION get_main($d) {
	Global $set,$table1,$table3,$member,$url;

	$d[7]=erg(stripslashes($d[7]));##이름
	$d[10]=erg(cut_size(stripslashes($d[10].$space),$set[40]));##제목

	if($d[21]) {
		$d_member[0]=MYSQL_FETCH_ROW(MYSQL_QUERY("Select lv from ".$table3." Where no='".$d[21]."'"));
		if($d_member[0]<1) $d_member[0]="9";
	}

	if($d[22]==1) {//공지사항
		$d[34]=5;
	} elseif($d[23]==1) {//비밀글
		$d[34]=6;
	} elseif(strlen($d[3])>1) {//답글임
		if((time()-$d[14])<round($set[38]*3600)) $d[34]=3;//최신글
		else $d[38]=4;
	} else {//답글아님
		if((time()-$d[14])<round($set[38]*3600)) $d[34]=1;//최신글
		else $d[38]=2;
	}

	return $d;
}

if(!$page OR $page<1) $page=1;

if($act=="search" AND ((($sn=="on" OR $ss=="on" OR $sc=="on") AND $keyword!='') || $cg>=0 ) ) {
	if($sn=="on") $result[]="nm like '%".preg_replace("/[\x20]+/","%' or nm like '%",$keyword)."%'";
	if($ss=="on") $result[]="tt like '%".preg_replace("/[\x20]+/","%' or tt like '%",$keyword)."%'";
	if($sc=="on") $result[]="mm like '%".preg_replace("/[\x20]+/","%' or mm like '%",$keyword)."%'";;
	if($cg>-1) $result[]="cg='".$cg."'";

	$trec=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM ".$table1." WHERE idx<901"));

	$result=MYSQL_QUERY("SELECT * FROM ".$table1." WHERE idx<901 AND ( ".implode(" OR ",$result)." ) ORDER BY sno ASC, rid ASC LIMIT ".(($page-1)*$set[41]).",".$set[41]);

} else {
	$tmp=($page-1)*$set[41];
	$tmp4=$tmp;
	$tmps=0;

	WHILE($d=@MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT * FROM ".$table5." ORDER BY idx asc"))) {

		if($d[0]>0) {
			$r=(10010-$d[1])-$d[2];

			$tmps+=$r;

			if($r<$tmp AND $r>0) {
				$tmp-=$r;
			} else {
				$idx[0]=$d[0];
				$idx[1]=$d[1];
				$idx[2]=$d[2];
				break;
			}
		}
	}

	if( ((10010-$idx[1])-$idx[2])-$tmp<$set[41] ) {
		$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT max(idx) FROM ".$table5." WHERE idx<901 LIMIT 1"));
		$r=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT min(idx) FROM ".$table5." WHERE idx>$idx[0] and idx<901 LIMIT 1"));

		if( ($idx[0]+1)>$result[0] || $r[0]>$result[0] ) {// 마지막 페이지
			$result=MYSQL_QUERY("SELECT * FROM ".$table1." WHERE idx='".$idx[0]."' LIMIT ".$tmp.",".(10010-($idx[1])-$idx[2]-$tmp) );
		} else {// idx 가 두개여야 함..... ex) idx 455 에서 마지막 1개 글하고 idx 466 에서 처음 9개 글을 뽑아야 할 경우
			$r=@MYSQL_FETCH_ROW(@MYSQL_QUERY("SELECT idx FROM ".$table5." WHERE idx>".$idx[0]." LIMIT 1"));
			$tmp2=MYSQL_FETCH_ROW(MYSQL_QUERY('SELECT * FROM '.$table5.' WHERE idx='.$r[0].' LIMIT 1'));

			$result=((10010-$tmp2[1])-$tmp2[2])+(((10010-$idx[1])-$idx[2]))-$tmp4;
			
			IF($result<$set[41]) {
				$result=MYSQL_QUERY("SELECT * FROM ".$table1." WHERE idx='".$idx[0]."' OR idx='".($r[0])."' LIMIT ".$tmp4.",".$result);
			} ELSE {
				$result=MYSQL_QUERY("SELECT * FROM ".$table1." WHERE idx='".$idx[0]."' OR idx='".($r[0])."' LIMIT ".$tmp4.",".$set[41]);
			}

		}
	} else {
		$result=MYSQL_QUERY("SELECT * FROM ".$table1." WHERE idx='".$idx[0]."' LIMIT ".$tmp.",".$set[41]);
	}

	$trec=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT count(*) FROM ".$table1." WHERE idx<901"));
}

if($trec<1) $trec=0; else $trec=$trec[0];
$tpage=@ceil($trec/$set[41]);
$i=$trec-(($page-1)*$set[41]);

$url=url();

REQUIRE_ONCE($set[49]."/".$bo.".php");

?>