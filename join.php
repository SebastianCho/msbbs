<?

if($act=="checkid") {

	ECHO("<title>MSBBS ���̵� �˻�</title>"
		."<META HTTP-EQUIV=CONTENT-TYPE CONTENT='text-html;charset=euc-kr'>"
		."<link rel=stylesheet href='./style.css'>"
		."<center>");

	$res=MYSQL_FETCH_ROW(MYSQL_QUERY("Select count(*) from ".$table3));
	$res=$res[0];

	if(!eregi("([\_0-9a-z])",$u_id)) {
		echo ("<script>alert('ID �� ���ڿ� ���� �ҹ��ڿ� ���ڿ� ������ھ��� �������θ� �̷�������մϴ�');window.close()</script>");
		exit;
	} elseif(strlen($u_id) < 2 or strlen($u_id) > 20) {
		echo ("�� ���̵�(".$u_id.")�� �ʹ� ª�ų� ��ϴ�.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		exit;
	} elseif($res=="" or $res=="0") {
		echo ("�� ���̵�(".$u_id.")�� ����Ͻ� �� �����ϴ�.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		exit;
	} else {
		$res=MYSQL_FETCH_ROW(MYSQL_QUERY("Select count(*) from $table3 Where id='".addslashes($u_id)."'"));

		if($res[0]>0) {
			echo ("�� ���̵�(".$u_id.")�� �̹� ���ǰ� �ֽ��ϴ�.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		} else {
			echo ("�� ���̵�(".$u_id.")�� ����Ͻ� �� �ֽ��ϴ�.<p></p><input type=button value=close class=confirm onclick=window.close()>");
		}
	}
} ELSE {

	REQUIRE_ONCE($set[49]."/join.php");

}

?>