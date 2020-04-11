<?

IF($act=="login") {

	login($u_id,$u_pw);

	IF($otherway!="" AND eregi("http://",$otherway) AND !eregi("id",$otherway) ) {// 다른 경로로 온 결과가 있으면
		HEADER("Location:".$otherway);
	} ELSE {// ..., 없으면
		HEADER("Location:./?id=".$id."&page=".$page."&".$url);
	}

} ELSE {

	IF( (int)$member[4]<9) {// 로그아웃

		IF(!$id) go("","게시판 이름을 지정해 주세요");

		logout();

		IF($otherway!="" AND eregi("http://",$otherway) AND eregi("id",$otherway) ) {
			HEADER("location:".$otherway);
		} ELSE {
			HEADER("Location:./?id=".$id);
		}

	} ELSE {// 로그인

		IF($member[4]<9) go("","이미 로그인 하셨습니다");

		REQUIRE_ONCE($set[49]."/login.php");

	}

}
?>