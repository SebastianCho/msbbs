<?

IF($act=="login") {

	login($u_id,$u_pw);

	IF($otherway!="" AND eregi("http://",$otherway) AND !eregi("id",$otherway) ) {// �ٸ� ��η� �� ����� ������
		HEADER("Location:".$otherway);
	} ELSE {// ..., ������
		HEADER("Location:./?id=".$id."&page=".$page."&".$url);
	}

} ELSE {

	IF( (int)$member[4]<9) {// �α׾ƿ�

		IF(!$id) go("","�Խ��� �̸��� ������ �ּ���");

		logout();

		IF($otherway!="" AND eregi("http://",$otherway) AND eregi("id",$otherway) ) {
			HEADER("location:".$otherway);
		} ELSE {
			HEADER("Location:./?id=".$id);
		}

	} ELSE {// �α���

		IF($member[4]<9) go("","�̹� �α��� �ϼ̽��ϴ�");

		REQUIRE_ONCE($set[49]."/login.php");

	}

}
?>