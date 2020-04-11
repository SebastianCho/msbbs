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

/*�Խ��� ��ü*/
$board_table="CREATE TABLE msb_".$id." (
	no	int(8) UNSIGNED default'1' NOT NULL auto_increment,	/* 0. �Խù��� ���� ��ȣ */
	idx	smallint(3) UNSIGNED NOT NULL,				/* 1. �Խù� ������ ���� �׷��� ��ȣ */
	main	smallint(5) UNSIGNED NOT NULL,				/* 2. �׷쳻������ ���� ��ȣ */
	rid	varchar(30) NOT NULL,					/* 3. ����� ���̰� ���� ����. */
	sno	int(8) default'0' NOT NULL,				/* 4. �˻��� �Խù� ������ ����-_-;
									����ó�� sno �� rid �� ����? �ε����� �Ἥ �ϴ°� ������;
									sno �� ������ �ʵ�� ����� sno �� ���� �ε����� �Ἥ
									�����ϴ°� ������ �׽�Ʈ �غ�����..
									*/
	cg	tinyint(2) default'0' NOT NULL,				/* 5. ī�װ� */
	cn	smallint(4) UNSIGNED default'0' NOT NULL,		/* 6. ���� �ڸ�Ʈ�� ���� */

	nm	char(20) NOT NULL,					/* 7. �۾��� �̸� */
	eml	char(150),						/* 8. �۾����� Email �ּ� */
	hm	char(150),						/* 9. �۾����� Ȩ������ �ּ� */
	tt	char(150) NOT NULL,					/* 10. ���� */
	mm	text NOT NULL,						/* 11. �� ���� */
	pw	char(16) NOT NULL,					/* 12. ��й�ȣ */

	w_i	varchar(20) NOT NULL,					/* 13. �۾����� IP �ּ� */
	w_d	int(10) default'0' NOT NULL,				/* 14. �۾� �ð� */
	m_i	varchar(20) NOT NULL,					/* 15. �ֱٿ� �� ������ IP �ּ� */
	m_d	int(10) default'0' NOT NULL,				/* 16. �ֱٿ� �� ������ �ð� */

	h1	int(11) default'0' NOT NULL,				/* 17. �� ��ȸ�� */
	h2	text,							/* 18. ������ �ٿ�ε� Ƚ�� */

	a1	tinytext,						/* 19. ��ũ1 */
	a2	tinytext,						/* 20. ��ũ2 */

	bm	char(1) default'0' NOT NULL,				/* 21. ����� �� ���̶�� ����� ������ȣ */
	bn	char(1) default'0' NOT NULL,				/* 22. ���������̶�� 1 */
	bs	char(1) default'0' NOT NULL,				/* 23. ��б��̶�� 1 */
	br	char(1) default'0' NOT NULL,				/* 24. ��� ���� ����ϸ� 1 */
	brc	char(1) default'0' NOT NULL,				/* 25. ��� ���ް� �Ϸ��� 1 */
	bg	char(1) default'0' NOT NULL,				/* 26. ������Ÿ���̸� 1 */
	bh	char(1) default'0' NOT NULL,				/* 27. html */
	bv	char(1) default'0' NOT NULL,				/* 28. ���� �̹��� ��ũ ����ϸ� 1 */

	fn	text,							/* 29. ���ε� �� ������ �̸� */
	fx	text,							/* 30. Ȯ���� */
	ft	text,							/* 31. ���ε� �� ������ Ÿ�� */
	fs	text,							/* 32. ���ε� �� ������ ũ�� */

	PRIMARY KEY(no),
	INDEX pos(idx,main,rid),					/* �Ϲ� ���� */
	INDEX sno(sno,rid)						/* �˻��� ���� �ε��� */
) TYPE=MyISAM;";

/*�ڸ�Ʈ ���̺�*/
$comment_table="Create Table msbc_".$id." (
	no	int(9) UNSIGNED default'1' NOT NULL auto_increment,	/* 0. �� ���� ��ȣ */
	tn	int(8) UNSIGNED default'1' NOT NULL,			/* 1. �θ���� ��ȣ */
	nm	varchar(20) NOT NULL,					/* 2. �۾��� �̸� */
	mm	text NOT NULL,						/* 3. �� ���� */
	w_i	varchar(20) NOT NULL,					/* 4. �۾��� IP */
	w_d	int(10) NOT NULL,					/* 5. �۾� �ð� */
	bm	int(8) default'0' NOT NULL,				/* 6. ����� �� ���̶�� ����� ������ȣ */
	pw	varchar(16),						/* 7. ��й�ȣ */

	PRIMARY KEY(no),
	KEY no(no)
) TYPE=MyISAM;";


/*������̺�*/
$member_table="Create Table msbmem (
	no	int(9) UNSIGNED default'1' NOT NULL auto_increment,	/* 0. ��� ������ȣ ! Important */
	bid	char(20) NOT NULL,					/* 1. ������ �Խ��� */
	id	char(15) NOT NULL,					/* 2. ���̵� */
	pw	char(16) NOT NULL,					/* 3. ��� */
	lv	char(1) default'8' NOT NULL,				/* 4. ���� */
	nm	char(20) NOT NULL,					/* 5. �̸� */
	eml	char(150) NOT NULL,					/* 6. E-mail �ּ� */
	hm	char(150),						/* 7. Ȩ������ */
	icq	char(10),						/* 8. icq */
	msn	char(150),						/* 9. msn */
	birth	char(8),						/* 10. ���� */
	r_d	int(10) UNSIGNED NOT NULL,				/* 11. ������ ��¥ */
	mm	char(250),						/* 12. �Ҹ�; */
	i_o	char(1) default'1',					/* 13. ���� ����� */

	PRIMARY KEY(no),
	KEY pos(id(1),id)
) TYPE=MyISAM;";


/*�Խ��� ���� ���̺�*/
$admin_table="Create Table msbadmin (
	id		char(20) NOT NULL,				/* 0. �Խ��� �̸� */
	skin		char(20) NOT NULL,				/* 1. ��Ų */
	opr		int(9) default'',				/* 2. �Խ��� ������ */
	cg		text default'',					/* 3. ī�װ� */
	cg_n		text default'',					/* 4. ī�װ��� ������ �ִ� �� �� */

	p_cg		char(1) default'0' NOT NULL,			/* 5. ī�װ� ��� */
	p_list		char(1) default'9' NOT NULL,			/* 6. �� ��� */
	p_view		char(1) default'9' NOT NULL,			/* 7. �� �б� */
	p_al		char(1) default'0' NOT NULL,			/* 8. �̹��� ���丵ũ */
	p_l		char(1) default'0' NOT NULL,			/* 9. ��ũ ����� ���� 2������ ���� */
	p_h		char(1) default'1' NOT NULL,			/* 10. HTML */
	p_vl		char(1) default'0' NOT NULL,			/* 11. �� ���� �� �� ��� */
	p_vr		char(1) default'0' NOT NULL,			/* 12. ���ñ� ���� */
	p_np		char(1) default'0' NOT NULL,			/* 13. ������ ������ ǥ�� ��� ����*/
	p_cw		char(1) default'0' NOT NULL,			/* 14. �ڸ�Ʈ ���� */
	p_cd		char(1) default'0' NOT NULL,			/* 15. �ڸ�Ʈ ���� */
	p_write		char(1) default'9' NOT NULL,			/* 16. �۾��� */
	p_rm		char(1) default'0' NOT NULL,			/* 17. ��� ���� */
	p_reply		char(1) default'9' NOT NULL,			/* 18. ��� ���� */
	p_gl		char(1) default'0' NOT NULL,			/* 19. ������ */
	p_modify	char(1) default'9' NOT NULL,			/* 20. �ۼ��� */
	p_delete	char(1) default'9' NOT NULL,			/* 21. �� ���� */
	p_st		char(1) default'9' NOT NULL,			/* 22. ����� �±�; img: embed: */
	p_sc		char(1) default'0' NOT NULL,			/* 23. ��б� */
	p_nt		char(1) default'1' NOT NULL,			/* 24. �������� */
	p_up		char(1) default'0' NOT NULL,			/* 25. ���ε� */
	p_dn		char(1) default'0' NOT NULL,			/* 26. �ٿ�ε� */
	p_join		char(1) default'9' NOT NULL,			/* 27. ���� */
	p_login		char(1) default'9' NOT NULL,			/* 28. �α��� */
	p_loged		char(1) default'0' NOT NULL,			/* 29. �������� */
	p_print		char(1) default'0' NOT NULL,			/* 30. ����Ʈ */
	p_find		char(1) default'0' NOT NULL,			/* 31. ���ã�� */
	p_copy		char(1) default'0' NOT NULL,			/* 32. �� ���� */

	h_tt		varchar(150) default'' NOT NULL,		/* 33. ��� Ÿ��Ʋ */
	h_sc		text default'',					/* 34. ��� ��ũ��Ʈ */
	h_bd_at		tinytext default'',				/* 35. ��� �߿��� <body xxxx > */
	h_bd_a		varchar(120) default'',				/* 36. ��� �ٵ� �ּ�  */
	h_bd		text default'',					/* 37. ��� �ٵ� */

	m_wd		varchar(6) default'90%' NOT NULL,		/* 38. �Խ����� ���α��� */
	m_new		tinyint(3) default'3' NOT NULL,			/* 39. ���� ���; ���� */
	m_lth		tinyint(3) default'30' NOT NULL,		/* 40. ������ ���� ���� */
	m_inp		tinyint(3) default'10' NOT NULL,		/* 41. �� �������� ���� �Խù� ���� */

	f_bd_a		tinytext,					/* 42. Ǫ�� �ٵ� �ּ� */
	f_bd		text,						/* 43. Ǫ�� �ٵ� */

	fl_ms		int(11) default'0' NOT NULL,			/* 44. �ٿ�ε�� ������ ���� ������ ���� */
	fl_mn		tinyint(2) default'0' NOT NULL,			/* 45. �ٿ�ε� ��� ���� */
	fl_awf		text,						/* 46. �ٿ�ε带 ����ϴ� Ȯ���� */

	ft		text,						/* 47. ���͸��� ���� */
	ft_msg		varchar(100),					/* 48. ���Ϳ� �ɷ��� �� ��ü�� �� */

									/* �κ�����±� �߰� ����*/
									/* 49. ./skin/��Ų���丮/ */

	PRIMARY KEY(id)
) TYPE=MyISAM;";

?>