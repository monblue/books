<?
	set_time_limit (0);

	$_zb_path="../";

	include "../lib.php";

	$connect=dbconn();

	$member=member_info();

	if(!$member[no]||$member[is_admin]>1||$member[level]>1) Error("�ְ� �����ڸ��� ����Ҽ� �ֽ��ϴ�");

	head(" bgcolor=white");
?>
<div align=center>
<br>
<table border=0 cellspacing=0 cellpadding=0 width=98%>
<tr>
  <td><img src=../images/arrangefiles.gif border=0></td>
  <td width=100% background=../images/trace_back.gif><img src=../images/trace_back.gif border=0></td>
  <td><img src=../images/trace_right.gif border=0></td>
</tr>
<tr>
  <td colspan=3 style=padding:15px;line-height:160%>
  	�� �������� ���κ����� ÷�������� �����ϴ� ���Դϴ�.<br>
	��� �Խ����� �����Ͽ� �߸� �÷��� ÷�������� �����ϰų�, ������ �ڷ���� �ڵ����� �����մϴ�.<br>
	�Խ����� �������� ���� �ð��� �ɸ��� �Ǵ� ���������� �����Ҷ����� ��ٷ� �ֽñ� �ٶ��ϴ�.<br>
	<br>
	<font color=darkred>* ��� �Խ����� �����ϹǷ�, ����ڰ� ���� �ð��� �̿��Ͽ� �ֽñ� �ٶ��ϴ�</font>
  </td>
</tr>
</table>
</div>
<?flush()?>
<pre>
<?
	$result = mysql_query("select * from $admin_table order by name") or die(mysql_error());

	$totalfilesnum = 0;
	$ntotalfilesnum = 0;
	$existsfilesnum = 0;
	$nexistsfilesnum = 0;

	while($bbs = mysql_fetch_array($result)) {
		
		$id = $bbs[name];		

		$files1 = mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where file_name1 != ''"));
		$files2 = mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where file_name2 != ''"));

		$filesnum1 = $files1[0];
		$filesnum2 = $files1[0];

		$nfiles1 = mysql_query("select no, file_name1 , s_file_name1 from $t_board"."_$id where file_name1 !='' and file_name1 not like 'data/$id/%'");
		$nfiles2 = mysql_query("select no, file_name2 , s_file_name2 from $t_board"."_$id where file_name2 !='' and file_name2 not like 'data/$id/%'");

		$nfilesnum1 = mysql_num_rows($nfiles1);
		$nfilesnum2 = mysql_num_rows($nfiles2);

		$totalfilesnum += $filesnum1 + $filesnum2;
		$ntotalfilesnum += $nfilesnum1 + $nfilesnum2;

		// ���丮 �˻�
		if(!is_dir("../data/$id")) {
			mkdir("../data/$id",0777);
		}

		if(!is_dir("../data/$id")) die("../data/$id ���丮�� �����Ҽ��� �����ϴ�");

?>
	<b><?=$id?></b> �Խ���</b>
	 - �� �Խù� ��  : <?=$bbs[total_article]?>��
	 - �� ���ε� ���� : <?=number_format($filesnum1+$filesnum2)?> ��
	 - ��ΰ� �߸��� ÷������ �� : <?=number_format($nfilesnum1+$nfilesnum2)?> ��

<?
		while($data=mysql_fetch_array($nfiles1)) {

			// �ҽ� ������ ������ üũ
			$filename = stripslashes($data[s_file_name1]);
			$source = "../".stripslashes($data[file_name1]);
			$path = str_replace($filename, "", $source);
			$no = $data[no];

			// �ҽ� ������ ���� ��쿡�� üũ
			if(file_exists($source)) {

				$existsfilesnum ++;

				// �ű� ��� ���� ������ �����ϴ��� üũ
				if(file_exists("../data/$id/$filename")) {
					$add_dir = time();
					$target_path = "../data/$id/$add_dir";
					mkdir($target_path,0777);
					$target_path = "../data/$id/$add_dir/$filename";
					$sql = "update $t_board"."_$id set file_name1 = 'data/$id/$add_dir/$filename' where no = $no";
				} else {
					$target_path = "../data/$id/$filename";
					$sql = "update $t_board"."_$id set file_name1 = 'data/$id/$filename' where no = $no";
				}

				if(!copy($source, $target_path)) die("<center><b>$source</b><br>to<br><b>$target_path</b><br><br> ������ �����Ҽ��� �����ϴ�<br>(������ üũ�Ͻ��� �ٽ� ������ ���ֽñ� �ٶ��ϴ�)</center>");
				z_unlink($source);
				@rmdir($path);

				mysql_query($sql) or die(mysql_error());

			} else {

				$nexistsfilesnum ++;

			}
		}

		while($data=mysql_fetch_array($nfiles2)) {

			// �ҽ� ������ ������ üũ
			$filename = stripslashes($data[s_file_name2]);
			$source = "../".stripslashes($data[file_name2]);
			$path = str_replace($filename, "", $source);
			$no = $data[no];

			// �ҽ� ������ ���� ��쿡�� üũ
			if(file_exists($source)) {

				$existsfilesnum ++;

				// �ű� ��� ���� ������ �����ϴ��� üũ
				if(file_exists("../data/$id/$filename")) {
					$add_dir = time();
					$target_path = "../data/$id/$add_dir";
					mkdir($target_path,0777);
					$target_path = "../data/$id/$add_dir/$filename";
					$sql = "update $t_board"."_$id set file_name2 = 'data/$id/$add_dir/$filename' where no = $no";
				} else {
					$target_path = "../data/$id/$filename";
					$sql = "update $t_board"."_$id set file_name2 = 'data/$id/$filename' where no = $no";
				}

				if(!copy($source, $target_path)) die("<center><b>$source</b><br>to<br><b>$target_path</b><br><br> ������ �����Ҽ��� �����ϴ�<br><br>(������ üũ�Ͻ��� �ٽ� ������ ���ֽñ� �ٶ��ϴ�)</center>");
				z_unlink($source);
				@rmdir($path);

				mysql_query($sql) or die(mysql_error());

			} else {

				$nexistsfilesnum ++;

			}
		}

		flush();
	}
?>

	<b>��ü ÷������ �� :</b> <?=number_format($totalfilesnum)?>

	<b>��ü ��ΰ� �߸��� ÷������ �� :</b> <?=number_format($ntotalfilesnum)?>

	<b>���� ���� ���� :</b> <?=number_format($existsfilesnum)?>

	<b>���� ������ ���� :</b> <?=number_format($nexistsfilesnum)?> (÷������ �ʵ尡 �ٸ� �뵵�� ���Ǵ� ����ϼ��� ����)

	<font color=red><b>��� ������ �������ϴ�.

	Ȯ���� ó���� ���ؼ� �ٽ� �ѹ� �����غ��ñ� �ٶ��ϴ�.</font>

	�� ���� ������� DB�� �ٰŷ� �Ͽ� ������ �����մϴ�.

	���� �̻��� �� ������ ������ ������������ �ֽ��ϴ�.

	������ ���� ������ ���Ͻø� �Ʒ� ��ư�� �����ּ���.

	<form action=arrangefile2.php method=post>
	<input type=submit value=" ������ ���� �˻� " class=submit>
	</form>


</pre>

<?
 mysql_close($connect);
 $connect="";
?>

<br><Br><Br>

<?
 foot();
?>