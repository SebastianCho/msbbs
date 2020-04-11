<?
REQUIRE_ONCE("lib.php");

FUNCTION url($str1=false) {
	GLOBAL $bo,$id,$cg,$page,$keyword,$sn,$ss,$sc;

	$url="id=".$id;
	if( $page>0 && ( $bo!='list' || $bo=='' ) ) $url.="&page=".$page;
	if( $cg>0 && $str1==false ) $url.="&cg=".$cg;
	elseif ( $str1>=0 ) $url.=$str1;

	if(($sn or $ss or $sc) and $keyword) {
		if($sn) { $url.="&sn=on"; }
		if($ss) { $url.="&ss=on"; }
		if($sc) { $url.="&sc=on"; }

		$url.="&keyword=".$keyword;
	}

	RETURN $url;
}

$member=member();
$set=board();

if(!$bo) $bo="list";
if($member[4]>$set[p_.$bo]) go("./?id=".$id."&bo=login","권한이 없습니다");
//if($set[3]==$member[0]) $member[4]=1;
$skin=$set[49];

REQUIRE_ONCE("./".$bo.".php");

if($dbconn) { MYSQL_CLOSE($dbconn); }

echo ("
<!-----------------------------------------------------------------------------
 MSBBS $ver
 Produced by mysu ( http://mysu.net; http://byariel.com , mysu@popsmail.com )
 Skin Source by ");
 @include($set[49]."/maker.txt");
echo("
------------------------------------------------------------------------------>");
exit;
?>