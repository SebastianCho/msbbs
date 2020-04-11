<?

/*

Don`t modify this document.

*/

$first_article="INSERT INTO msb_".$id." 
(no,idx
,main,rid
,nm,tt
,mm,w_d)

values('1','901'
,'1',''
,'Ariel','INSTALLED'
,'ByARIEL.com'
,'".time()."');";

$second_article="INSERT into msbi_".$id." values('900','10010','0');";

$idx_table="CREATE TABLE msbi_".$id." (
	idx	smallint(3) UNSIGNED default'900' NOT NULL,
	main	smallint(5) UNSIGNED default'10010' NOT NULL,
	rid	int(9) default'0' NOT NULL,

	PRIMARY KEY(idx),
	INDEX pos(idx,main,rid)
) TYPE=MyISAM;";

/*게시판 본체*/
$board_table="CREATE TABLE msb_".$id." (
	no	int(8) UNSIGNED default'1' NOT NULL auto_increment,	/* 0. 게시물의 고유 번호 */
	idx	smallint(3) UNSIGNED NOT NULL,				/* 1. 게시물 정렬을 위한 그룹의 번호 */
	main	smallint(5) UNSIGNED NOT NULL,				/* 2. 그룹내에서의 고유 번호 */
	rid	varchar(30) NOT NULL,					/* 3. 응답글 깊이겸 정렬 수단. */
	sno	int(8) default'0' NOT NULL,				/* 4. 검색시 게시물 정렬을 위함-_-;
									현재처럼 sno 와 rid 를 복합? 인덱스를 써서 하는게 나은지;
									sno 를 정수형 필드로 만들고 sno 로 단일 인덱스로 써서
									정렬하는게 나은지 테스트 해봐야함..
									*/
	cg	tinyint(2) default'0' NOT NULL,				/* 5. 카테고리 */
	cn	smallint(4) UNSIGNED default'0' NOT NULL,		/* 6. 딸린 코멘트의 갯수 */

	nm	char(20) NOT NULL,					/* 7. 글쓴이 이름 */
	eml	char(150),						/* 8. 글쓴이의 Email 주소 */
	hm	char(150),						/* 9. 글쓴이의 홈페이지 주소 */
	tt	char(150) NOT NULL,					/* 10. 제목 */
	mm	text NOT NULL,						/* 11. 글 내용 */
	pw	char(16) NOT NULL,					/* 12. 비밀번호 */

	w_i	varchar(20) NOT NULL,					/* 13. 글쓴이의 IP 주소 */
	w_d	int(10) default'0' NOT NULL,				/* 14. 글쓴 시각 */
	m_i	varchar(20) NOT NULL,					/* 15. 최근에 글 수정한 IP 주소 */
	m_d	int(10) default'0' NOT NULL,				/* 16. 최근에 글 수정한 시각 */

	h1	int(11) default'0' NOT NULL,				/* 17. 글 조회수 */
	h2	text,							/* 18. 파일의 다운로드 횟수 */

	a1	tinytext,						/* 19. 링크1 */
	a2	tinytext,						/* 20. 링크2 */

	bm	char(1) default'0' NOT NULL,				/* 21. 멤버가 쓴 글이라면 멤버의 고유번호 */
	bn	char(1) default'0' NOT NULL,				/* 22. 공지사항이라면 1 */
	bs	char(1) default'0' NOT NULL,				/* 23. 비밀글이라면 1 */
	br	char(1) default'0' NOT NULL,				/* 24. 답글 메일 사용하면 1 */
	brc	char(1) default'0' NOT NULL,				/* 25. 답글 못달게 하려면 1 */
	bg	char(1) default'0' NOT NULL,				/* 26. 갤러리타잎이면 1 */
	bh	char(1) default'0' NOT NULL,				/* 27. html */
	bv	char(1) default'0' NOT NULL,				/* 28. 오토 이미지 링크 사용하면 1 */

	fn	text,							/* 29. 업로드 한 파일의 이름 */
	fx	text,							/* 30. 확장자 */
	ft	text,							/* 31. 업로드 한 파일의 타입 */
	fs	text,							/* 32. 업로드 한 파일의 크기 */

	PRIMARY KEY(no),
	INDEX pos(idx,main,rid),					/* 일반 정렬 */
	INDEX sno(sno,rid)						/* 검색을 위한 인덱스 */
) TYPE=MyISAM;";

/*코멘트 테이블*/
$comment_table="Create Table msbc_".$id." (
	no	int(9) UNSIGNED default'1' NOT NULL auto_increment,	/* 0. 글 고유 번호 */
	tn	int(8) UNSIGNED default'1' NOT NULL,			/* 1. 부모글의 번호 */
	nm	varchar(20) NOT NULL,					/* 2. 글쓴이 이름 */
	mm	text NOT NULL,						/* 3. 글 내용 */
	w_i	varchar(20) NOT NULL,					/* 4. 글쓴이 IP */
	w_d	int(10) NOT NULL,					/* 5. 글쓴 시각 */
	bm	int(8) default'0' NOT NULL,				/* 6. 멤버가 쓴 글이라면 멤버의 고유번호 */
	pw	varchar(16),						/* 7. 비밀번호 */

	PRIMARY KEY(no),
	KEY no(no)
) TYPE=MyISAM;";


/*멤버테이블*/
$member_table="Create Table msbmem (
	no	int(9) UNSIGNED default'1' NOT NULL auto_increment,	/* 0. 멤버 고유번호 ! Important */
	bid	char(20) NOT NULL,					/* 1. 가입한 게시판 */
	id	char(15) NOT NULL,					/* 2. 아이디 */
	pw	char(16) NOT NULL,					/* 3. 비번 */
	lv	char(1) default'8' NOT NULL,				/* 4. 레벨 */
	nm	char(20) NOT NULL,					/* 5. 이름 */
	eml	char(150) NOT NULL,					/* 6. E-mail 주소 */
	hm	char(150),						/* 7. 홈페이지 */
	icq	char(10),						/* 8. icq */
	msn	char(150),						/* 9. msn */
	birth	char(8),						/* 10. 생일 */
	r_d	int(10) UNSIGNED NOT NULL,				/* 11. 가입한 날짜 */
	mm	char(250),						/* 12. 할말; */
	i_o	char(1) default'1',					/* 13. 공개 비공개 */

	PRIMARY KEY(no),
	KEY pos(id(1),id)
) TYPE=MyISAM;";


/*게시판 관리 테이블*/
$admin_table="Create Table msbadmin (
	id		char(20) NOT NULL,				/* 0. 게시판 이름 */
	skin		char(20) NOT NULL,				/* 1. 스킨 */
	opr		int(9) default'',				/* 2. 게시판 관리자 */
	cg		text default'',					/* 3. 카테고리 */
	cg_n		text default'',					/* 4. 카테고리당 가지고 있는 글 수 */

	p_cg		char(1) default'0' NOT NULL,			/* 5. 카테고리 사용 */
	p_list		char(1) default'9' NOT NULL,			/* 6. 글 목록 */
	p_view		char(1) default'9' NOT NULL,			/* 7. 글 읽기 */
	p_al		char(1) default'0' NOT NULL,			/* 8. 이미지 오토링크 */
	p_l		char(1) default'0' NOT NULL,			/* 9. 링크 사용할 갯수 2개까지 제한 */
	p_h		char(1) default'1' NOT NULL,			/* 10. HTML */
	p_vl		char(1) default'0' NOT NULL,			/* 11. 글 읽을 때 글 목록 */
	p_vr		char(1) default'0' NOT NULL,			/* 12. 관련글 보기 */
	p_np		char(1) default'0' NOT NULL,			/* 13. 이전글 다음글 표시 허용 여부*/
	p_cw		char(1) default'0' NOT NULL,			/* 14. 코멘트 쓰기 */
	p_cd		char(1) default'0' NOT NULL,			/* 15. 코멘트 삭제 */
	p_write		char(1) default'9' NOT NULL,			/* 16. 글쓰기 */
	p_rm		char(1) default'0' NOT NULL,			/* 17. 답글 메일 */
	p_reply		char(1) default'9' NOT NULL,			/* 18. 답글 쓰기 */
	p_gl		char(1) default'0' NOT NULL,			/* 19. 갤러리 */
	p_modify	char(1) default'9' NOT NULL,			/* 20. 글수정 */
	p_delete	char(1) default'9' NOT NULL,			/* 21. 글 삭제 */
	p_st		char(1) default'9' NOT NULL,			/* 22. 스페셜 태그; img: embed: */
	p_sc		char(1) default'0' NOT NULL,			/* 23. 비밀글 */
	p_nt		char(1) default'1' NOT NULL,			/* 24. 공지사항 */
	p_up		char(1) default'0' NOT NULL,			/* 25. 업로드 */
	p_dn		char(1) default'0' NOT NULL,			/* 26. 다운로드 */
	p_join		char(1) default'9' NOT NULL,			/* 27. 가입 */
	p_login		char(1) default'9' NOT NULL,			/* 28. 로그인 */
	p_loged		char(1) default'0' NOT NULL,			/* 29. 정보수정 */
	p_print		char(1) default'0' NOT NULL,			/* 30. 프린트 */
	p_find		char(1) default'0' NOT NULL,			/* 31. 비번찾기 */
	p_copy		char(1) default'0' NOT NULL,			/* 32. 글 복사 */

	h_tt		varchar(150) default'' NOT NULL,		/* 33. 헤더 타이틀 */
	h_sc		text default'',					/* 34. 헤더 스크립트 */
	h_bd_at		tinytext default'',				/* 35. 헤더 중에서 <body xxxx > */
	h_bd_a		varchar(120) default'',				/* 36. 헤더 바디 주소  */
	h_bd		text default'',					/* 37. 헤더 바디 */

	m_wd		varchar(6) default'90%' NOT NULL,		/* 38. 게시판의 가로길이 */
	m_new		tinyint(3) default'3' NOT NULL,			/* 39. 새글 헌글; 구분 */
	m_lth		tinyint(3) default'30' NOT NULL,		/* 40. 제목의 길이 제한 */
	m_inp		tinyint(3) default'10' NOT NULL,		/* 41. 한 페이지에 뽑을 게시물 갯수 */

	f_bd_a		tinytext,					/* 42. 푸터 바디 주소 */
	f_bd		text,						/* 43. 푸터 바디 */

	fl_ms		int(11) default'0' NOT NULL,			/* 44. 다운로드된 각각의 파일 사이즈 제한 */
	fl_mn		tinyint(2) default'0' NOT NULL,			/* 45. 다운로드 허용 갯수 */
	fl_awf		text,						/* 46. 다운로드를 허용하는 확장자 */

	ft		text,						/* 47. 필터링할 낱말 */
	ft_msg		varchar(100),					/* 48. 필터에 걸렸을 때 대체할 말 */

									/* 부분허용태그 추가 예정*/
									/* 49. ./skin/스킨디렉토리/ */

	PRIMARY KEY(id)
) TYPE=MyISAM;";

?>