<?
REQUIRE_ONCE("./lib.php");

if(!$no) go("","게시물을 지정해 주세요");

$set=board();
$member=member();

if($set[30]!=1) go("","권한이 없습니다");

FUNCTION print_url() {
	Global $id,$no,$color,$font,$size,$cellpadding;

	if(!$cellpadding) $cellpadding="3";
	if(!$size) $size="12px";
	if(!$color) $color="black";
	if(!$font) $font="굴림";

	$result.="id=".$id;

	if($no>0) $result.="&no=".$no;
	if($font>0) $result.="&font=".$font;
	if($color!="") $result.="&color=".$color;
	if($size!="") $result.="&size=".$size;
	if($cellpadding!="") $result.="&cellpadding=".$cellpadding;

	return $result;
}

$print_url=print_url();

if($bo=="main") {

	$d=mysql_query("Select * from $table1 Where no='".$no."'");
	if(MYSQL_AFFECTED_ROWS()<1) go("","글번호를 올바로 지정하세요");

	$d=mysql_fetch_array($d);

	if($d[8]) $d[8]="(".$d[8].")";
	if($d[16]<1) $hide=array("<!--","-->");

?>
<HTML>

<HEAD>
<title>Print</title>
<style type="text/css">
body,td { font-size:<?=$size?>;color:<?=$color?>;font-family:<?=$font?>; }
</style>
</HEAD>

<BODY scroll=yes>
<table border=0 cellpadding=<?=$cellpadding?> cellspacing=0 width=100%>
	<tr height=1 bgcolor=Black><td></td></tr>
	<tr>
		<td>Subject : <?=stripslashes($d[10])?>
		</td>
	</tr>
	<tr height=1 bgcolor=Black><td></td></tr>
	<tr>
		<td>Name : <?=stripslashes($d[7])?> <?=stripslashes($d[8])?>
		</td>
	</tr>
<?
	if($d[9]!="") {
?>
	<tr>
		<td>Homepage : <?=stripslashes($d[9])?>
		</td>
	</tr>
<?
	}
?>
	<tr>
		<td>
		written <?=date("Y/m/d H:i:s a l",$d[14])?><br>
		<?=$hide[0]?>modify <?=date("Y/m/d H:i:s a l",$d[16])?><?=$hide[1]?>
		</td>
	</tr>
	<tr height=1 bgcolor=Black><td></td></tr>
	<tr style="word-break:all;">
		<td><?=nl2br(stripslashes($d[11]))?>
		</td>
	</tr>
</table>

</BODY>

</HTML>
<?
} elseif($bo=="header") {
?>
<HTML>

<HEAD>
<title>Print</title>
<link rel=StyleSheet href="./style.css">
</HEAD>

<BODY leftmargin=0 topmargin=0 scroll=yes>
<table border=0 cellpadding=0 cellspacing=0 width=100% height=50 bgcolor=#DDDDDD>
	<form name=ms_print action="<?=$PHP_SELF?>" method=post target=ms_print_main>
	<input type=hidden name=bo value=main>
	<input type=hidden name=exec value=apply>
	<input type=hidden name=no value=<?=$no?>>
	<input type=hidden name=id value=<?=$id?>>
	<tr>
		<td>
		<table border=0 cellpadding=2 cellspacing=0 width=100% height=100% bgcolor=#EEEEEE style="border:solid 1 black">
			<tr>
				<td width=50 valign=middle align=center bgcolor=#DDDDDD>폰트
				</td>
				<td valign=middle>
		크기
		<input type=text name=size size=5 align=absmiddle class=input value="<?=$size?>">
		이름
		<input type=text name=font size=10 align=absmiddle class=input value="<?=$font?>">
		색깔
		<input type=text name=color size=10 align=absmiddle class=input value="<?=urldecode($color)?>">
				</td>
				<td width=155 rowspan=3 align=center><nobr><input type=submit value=" 적용(APPLY) " class=confirm style="height:30;width:80;font-size:8pt">
				<input type=button value="Print" onclick="top.ms_print_main.print()" class=input style="height:30;width:80"></nobr></td>
			</tr>
			<tr height=1 bgcolor=#777777><td></td></tr>
			<tr>
				<td align=center bgcolor=#DDDDDD>표
				</td>
				<td>내부여백 <input type=text name=cellpadding size=5 class=input value="<?=$cellpadding?>"> <input type=button value=" 기본값 복원 " class=input onclick="ms_print.size.value='12px';ms_print.font.value='굴림';ms_print.color.value='black';ms_print.cellpadding.value='3'">
				</td>
			</tr>
		</table>
		</td>
	</tr>
	</form>
</table>

</BODY>

</HTML>
<?
} else {
?>
<HTML>

<HEAD>
<title>MSBBS - Print</title>
</HEAD>

<frameset rows="52,*" border=0 framespacing=1 frameborder=1 bordercolor=brown>
	<frame name=header marginwidth=0 marginheight=0 src="<?=$PHP_SELF?>?<?=$print_url?>&bo=header">
	<frame name=ms_print_main src="<?=$PHP_SELF?>?<?=$print_url?>&bo=main">
</frameset>

</HTML>
<?
}

mysql_close($dbconn);
exit;
?>