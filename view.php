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
		go(""," �� ��ȣ�� �ùٷ� �����ϼ��� ");
	}

	if($HTTP_COOKIE_VARS[msbbs_no]!=$no) {
		mysql_query('UPDATE '.$table1.' SET h1=h1+1 WHERE no="'.$no.'"') or DB_ERR(__FILE__."-".__LINE__);
	}
	SETCOOKIE("msbbs_no",$no,time()+18600,"/");

	return $d;

}

Function get_mm() { // ���ѿ� ���� ��ũ����⳪ �±����ŵ��..... ���� ����
	Global $member;
}

Function get_cm() {// get_mm() �� �Ϲ� �Խù� get_cm() �� �ڸ�Ʈ $bo �� ���� mm ó���ϴ°� ���°��� ����
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
		if($d[21]>0) {//����� ���� ��б��϶�
			go("","���� ������ �����ϴ�");
		} else {//������ ���� ��б��϶�
			if($member[4]!=1 AND $member[0]!=$set[2]) {
				INCLUDE($set[49]."/password.php");
			}
		}
	} ELSE {
		IF($d[23]==1 AND $member[4]!=1 AND $member[0]!=$set[2] AND ( $d[21]!=$member[0] OR $member[0]<1 ) ) {// ��б� ������ ����� �Է����� �� �����ڵ� �ƴϰ� �Խ��� �����ڵ� �ƴϰ� �۾��̵� �ƴ� ��
			IF(!$pw) go(""," ��б��̹Ƿ� ��й�ȣ�� �ʿ��մϴ� ");
			$pw=MYSQL_FETCH_ROW(MYSQL_QUERY("Select password('$pw')"));
			IF($d[12]!=$pw[0]) go(""," ��й�ȣ�� ���� �ʽ��ϴ� ");
		}

		$d[7]=erg(stripslashes($d[7])); /* �̸� */
		$d[10]=erg(cut_size(stripslashes($d[10].$space),$set[40])); /* ���� */
		$d[11]=stripslashes($d[11]); /* ���� */

		/* HTML �ؼ� �ϴ��� �����ĸ� �������� �κ� ���� */
		if($d[21]) {
			$d_member=MYSQL_FETCH_ROW(MYSQL_QUERY("Select no,nm,eml,hm,lv from ".$table3." Where no='".$d[21]."'"));
			if($d_member[4]<1) $d_member[4]=5;
			if($d_member[0]<1) $d_member[0]="0";

			$d[8]=$d_member[2];
			$d[9]=$d_member[3];

			if( ($d_member[4]==1 OR $d_member[0]==$set[2]) AND $d[27]==1 ) {//Author = �����ڳ� �Խ��ǰ������� ��
				$d[11]=makeimg($d[11]);
			} elseif( $d_member[4]<=$set[10] AND $d[27]==1 ) {//Author = html �� �� �� �ִ� ������ ���� ���� ��
				$d[11]=makeimg($d[11]);
			} else {
				$d[11]=makeimg(erg($d[11]));
			}
		} else {
			if( $set[10]==9 ) {//�Խ��� �����߿��� html ��뿩�ΰ� ���Ϸ����� ��
				$d[11]=makeimg($d[11]);
			} else {
				$d[11]=makeimg(erg($d[11]));
			}
		}
		/* HTML �ؼ� �ϴ��� �����ĸ� �������� �κ� �� */

		return $d;
	}
}

//��ũ�� ����°�
Function makelink($str1) {
	$str1=explode("\n",$str1);
	$str1=implode("\n ",$str1);
	// URL ġȯ
	$str1=" ".$str1;
	$str1=eregi_replace( ">http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", "><a href=http://\\1 target=_blank>http://\\1</a>", $str1);
	$str1=eregi_replace( "\(http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)\)", "(<a href=http://\\1 target=_blank>http://\\1</a>)", $str1);
	$str1=eregi_replace( "&nbsp;&nbsp;http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", "&nbsp;&nbsp;<a href=http://\\1 target=_blank>http://\\1</a>", $str1);
	$str1=eregi_replace( " http://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", " <a href=http://\\1 target=_blank>http://\\1</a>", $str1);
	$str1=eregi_replace( " \thttp://([a-z0-9\_\-\.\/\~\@\?\=\;\&\#\-]+)", " <a href=http://\\1 target=_blank>http://\\1</a>", $str1);

	// ���� ġȯ
	$str1=eregi_replace(" ([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", "<a href=mailto:\\1@\\2>\\1@\\2</a>", $str1);
	$str1=eregi_replace(" \t([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", "<a href=mailto:\\1@\\2>\\1@\\2</a>", $str1);
	$str1=eregi_replace(" \([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", "<a href=mailto:\\1@\\2>\\1@\\2</a>", $str1);

	return $str1;
}

//���伣 �±�
Function makeimg($str1) {
	$str1=explode("\n",$str1);
	$str1=implode("\n ",$str1);
	// URL ġȯ
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
	$result=MYSQL_FETCH_ROW(MYSQL_QUERY("SELECT no FROM ".$table1." WHERE idx='".$result[0]."' ORDER BY sno DESC, rid ASC LIMIT 1"));// �� ó�� �� ����

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
	��� �޸� ������ ���ϱ� ��Ǯ;
	*/
	if( $d[25]==1 ) return 0;

	return 1;
}


REQUIRE_ONCE($set[49]."/view.php");

?>