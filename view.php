<?

Function get_view_result() {
	Global $table1,$no,$cart,$bo,$pw,$member,$HTTP_COOKIE_VARS;

	if( isset($cart) && is_array($cart) ) {
		while( list($value,$label)=each($cart) ) {
			$temp[]='no="'.$label.'"';
		}

		$d=mysql_query('SELECT * FROM '.$table1.' WHERE '.implode(' or ',$temp).' ORDER BY no desc;') or DB_ERR(__FILE__."-".__LINE__);
	} elseif($no) {
		$d=mysql_query('SELECT * FROM '.$table1.' WHERE no="'.$no.'" LIMIT 1') or DB_ERR(__FILE__."-".__LINE__);
	} else {
		go(""," 글 번호를 올바로 선택하세요 ");
	}

	if($HTTP_COOKIE_VARS[msbbs_no]!=$no) {
		mysql_query('UPDATE '.$table1.' SET h1=h1+1 WHERE no="'.$no.'"') or DB_ERR(__FILE__."-".__LINE__);
	}
	SETCOOKIE("msbbs_no",$no,time()+18600,"/");

	return $d;

}

Function get_mm() { // 권한에 따른 링크만들기나 태그제거등등..... 만들 예정
	Global $member;
}

Function get_cm() {// get_mm() 은 일반 게시물 get_cm() 은 코멘트 $bo 에 따라서 mm 처리하는가 마는가도 결정
}


Function auto_img_link() {
	Global $member,$set,$d;

	if($member[4]<=$set[26]) {
		if($d[26]!=1) {

			if(count($d[30])>0) {
				if($d[30][0]!="") {
					if(	eregi($d[30][0],"jp")
						OR eregi($d[30][0],"gif")
						OR eregi($d[30][0],"png")
						OR eregi($d[30][0],"bmp")) {

						$result[0]=1;
					} else $result[0]="0";

				} else $result[0]="0";
				if($d[30][1]!="") {
					if(	eregi($d[30][1],"jp")
						OR eregi($d[30][1],"gif")
						OR eregi($d[30][1],"png")
						OR eregi($d[30][1],"bmp")) {
						$result[1]=1;
					} else $result[0]="0";
				} else $result[0]="0";
			}
		}
	}
	return $result;
}

Function get_pn_main($str1,$str2=false) {
	Global $table1,$no;

	if($no>0) {

		if($str1==1) {//
			$str1=mysql_fetch_array(mysql_query("SELECT * FROM ".$table1." WHERE no>".$no." and rid='a' ORDER BY bn desc,sno desc,rid desc LIMIT 1"));

			$str1[10]=erg(stripslashes($str1[10]));

			return $str1;
		} else {
			$str1=mysql_fetch_array(mysql_query("SELECT * FROM ".$table1." WHERE no<".$no." and rid='a' ORDER BY bn asc,sno asc,rid asc LIMIT 1"));

			$str1[10]=erg(stripslashes($str1[10]));

			return $str1;
		}

	} else {
		return 0;
	}

}

Function get_mainv($d) {
	Global $id,$bo,$act,$set,$table1,$table3,$member,$url,$pw;

	IF($d[23]==1 AND $act!='sview' AND $member[4]!=1 AND $member[0]!=$set[2] AND $d[21]!=$member[0] ) {
		if($d[21]>0) {//멤버가 적은 비밀글일때
			go("","읽을 권한이 없습니다");
		} else {//비멤버가 적은 비밀글일때
			if($member[4]!=1 AND $member[0]!=$set[2]) {
				INCLUDE($set[49]."/password.php");
			}
		}
	} ELSE {
		IF($d[23]==1 AND $member[4]!=1 AND $member[0]!=$set[2] AND ( $d[21]!=$member[0] OR $member[0]<1 ) ) {// 비밀글 보려고 비번을 입력했을 시 관리자도 아니고 게시판 관리자도 아니고 글쓴이도 아닐 때
			IF(!$pw) go(""," 비밀글이므로 비밀번호가 필요합니다 ");
			$pw=MYSQL_FETCH_ROW(MYSQL_QUERY("Select password('$pw')"));
			IF($d[12]!=$pw[0]) go(""," 비밀번호가 맞지 않습니다 ");
		}

		$d[7]=erg(stripslashes($d[7])); /* 이름 */
		$d[10]=erg(cut_size(stripslashes($d[10].$space),$set[40])); /* 제목 */
		$d[11]=stripslashes($d[11]); /* 내용 */

		/* HTML 해석 하느냐 마느냐를 구분짓는 부분 시작 */
		if($d[21]) {
			$d_member=MYSQL_FETCH_ROW(MYSQL_QUERY("Select no,nm,eml,hm,lv from ".$table3." Where no='".$d[21]."'"));
			if($d_member[4]<1) $d_member[4]=5;
			if($d_member[0]<1) $d_member[0]="0";

			$d[8]=$d_member[2];
			$d[9]=$d_member[3];

			if( ($d_member[4]==1 OR $d_member[0]==$set[2]) AND $d[27]==1 ) {//Author = 관리자나 게시판관리자일 때
				$d[11]=makeimg($d[11]);
			} elseif( $d_member[4]<=$set[10] AND $d[27]==1 ) {//Author = html 을 쓸 수 있는 레벨로 글을 썼을 때
				$d[11]=makeimg($d[11]);
			} else {
				$d[11]=makeimg(erg($d[11]));
			}
		} else {
			if( $set[10]==9 ) {//게시판 세팅중에서 html 허용여부가 최하레벨일 때
				$d[11]=makeimg($d[11]);
			} else {
				$d[11]=makeimg(erg($d[11]));
			}
		}
		/* HTML 해석 하느냐 마느냐를 구분짓는 부분 끝 */

		return $d;
	}
}

//링크를 만드는거
Function makelink($str1) {
	$str1=explode("\n",$str1);
	$str1=implode("\n ",$str1);
	// URL 치환
	$str1=" ".$str1;
	$str1=eregi_replace( ">http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", "><a href=http://\\1 target=_blank>http://\\1</a>", $str1);
	$str1=eregi_replace( "\(http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)\)", "(<a href=http://\\1 target=_blank>http://\\1</a>)", $str1);
	$str1=eregi_replace( "&nbsp;&nbsp;http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", "&nbsp;&nbsp;<a href=http://\\1 target=_blank>http://\\1</a>", $str1);
	$str1=eregi_replace( " http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", " <a href=http://\\1 target=_blank>http://\\1</a>", $str1);
	$str1=eregi_replace( " \thttp://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", " <a href=http://\\1 target=_blank>http://\\1</a>", $str1);

	// 메일 치환
	$str1=eregi_replace(" ([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", "<a href=mailto:\\1@\\2>\\1@\\2</a>", $str1);
	$str1=eregi_replace(" \t([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", "<a href=mailto:\\1@\\2>\\1@\\2</a>", $str1);
	$str1=eregi_replace(" \([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", "<a href=mailto:\\1@\\2>\\1@\\2</a>", $str1);

	return $str1;
}

//스페샬 태그
Function makeimg($str1) {
	$str1=explode("\n",$str1);
	$str1=implode("\n ",$str1);
	// URL 치환
	$str1=" ".$str1;
	$str1=eregi_replace(" >img:([a-z0-9\_\-\.\/\~\@\?\=\:\;\&\ \#\-]+)", " ><img src='http://\\1'>", $str1);
	$str1=eregi_replace(" \(img:([a-z0-9\_\-\.\/\~\@\?\=\:\;\&\ \#\-]+)\)", " (<img src='http://\\1'>)", $str1);
	$str1=eregi_replace(" &nbsp;&nbsp;img:([a-z0-9\_\-\.\/\:\~\@\?\ \=\;\&\#\-]+)", "&nbsp;&nbsp;<img src='http://\\1'>", $str1);
	$str1=eregi_replace(" img:([a-z0-9\_\-\.\/\~\@\?\=\:\;\&\#\ \-]+)", " <img src='http://\\1'>", $str1);
	$str1=eregi_replace(" >embed:([a-z0-9\_\-\.\/\~\@\?\=\:\;\&\#\ \-]+)", " <embed src='\\1' autostart=false>", $str1);
	$str1=eregi_replace(" \(embed:([a-z0-9\_\-\.\/\~\@\?\=\;\:\&\#\ \-]+)\)", " (<embed src='\\1' autostart=false>)", $str1);
	$str1=eregi_replace(" &nbsp;&nbsp;embed:([a-z0-9\_\-\.\/\~\@\:\ \?\=\;\&\#\-]+)", " &nbsp;&nbsp;<embed src='\\1' autostart=false>", $str1);
	$str1=eregi_replace( " embed:([a-z0-9\_\-\.\/\~\@\?\=\;\&\:\ \#\-]+)", " <embed src='\\1' autostart=false>", $str1);

	return $str1;
}

Function no_reply() {
	Global $d,$table1,$table5,$member,$set;

	if( $member[4]==1 || $member[0]==$set[2] ) return 1;
	if( $d[25]==1 ) return 0;

	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT max(idx) FROM ".$table5));
	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT no FROM ".$table1." WHERE idx='".$result[0]."' ORDER BY sno DESC, rid ASC LIMIT 1"));// 맨 처음 글 구함

	if( $d[22]!=1 || $result[0]!=$d[0] ) return 1;

	return 0;
}

Function no_modify() {
	Global $d,$member,$set;

	if( $member[4]==1 || $member[0]==$set[2] ) return 1;
	if( $d[21]>0 && $member[0]==$d[21] && $set[20]>=$member[4] ) return 1;
	if( $d[21]<1 ) return 1;

	return 0;
}

FUNCTION no_delete() {
	GLOBAL $d,$member,$set,$table1;

	if( $member[4]==1 || $member[0]==$set[2] ) return 1;
	if( $d[21]>0 && $member[0]==$d[21] && $set[20]>=$member[4] ) return 1;

	$result=MYSQL_QUERY( 'SELECT * FROM '.$table1.' WHERE sno="'.$d[4].'" and length(rid)>'.strlen($d[3]) );
	if(MYSQL_AFFECTED_ROWS()>0) return 0;

	/*
	답글 달린 글인지 구하기 씨풀;
	*/
	if( $d[25]==1 ) return 0;

	return 1;
}


REQUIRE_ONCE($set[49]."/view.php");

?>