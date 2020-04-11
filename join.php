<?

if($act=="checkid") {

	ECHO("<title>MSBBS 아이디 검사</title>"
		."<META HTTP-EQUIV=CONTENT-TYPE CONTENT='text-html;charset=euc-kr'>"
		."<link rel=stylesheet href='./style.css'>"
		."<center>");

	$res=MYSQL_FETCH_ROW(MYSQL_QUERY("Select count(*) from ".$table3));
	$res=$res[0];

	if(!eregi("([\_0-9a-z])",$u_id)) {
		echo ("<script>alert('ID 는 숫자와 영문 소문자와 숫자와 언더스코어의 조합으로만 이루어져야합니다');window.close()</script>");
		exit;
	} elseif(strlen($u_id) < 2 or strlen($u_id) > 20) {
		echo ("위 아이디(".$u_id.")는 너무 짧거나 깁니다.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		exit;
	} elseif($res=="" or $res=="0") {
		echo ("위 아이디(".$u_id.")는 사용하실 수 없습니다.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		exit;
	} else {
		$res=MYSQL_FETCH_ROW(MYSQL_QUERY("Select count(*) from $table3 Where id='".addslashes($u_id)."'"));

		if($res[0]>0) {
			echo ("위 아이디(".$u_id.")는 이미 사용되고 있습니다.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		} else {
			echo ("위 아이디(".$u_id.")는 사용하실 수 있습니다.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		}
	}
} ELSE {

	REQUIRE_ONCE($set[49]."/join.php");

}

?>