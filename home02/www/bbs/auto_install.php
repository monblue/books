<?
	include "schema.sql";

	$install = unserialize(base64_decode($install));

	$hostname		= $install[hostname];
	$user_id		= $install[user_id];
	$dbname			= $install[dbname];
	$password		= $install[password];
	$name			= $install[name];

	// DB�� Ŀ��Ʈ �ϰ� DB NAME���� select DB
	$connect = @mysql_connect($hostname,$user_id,$password);

	if (!$connect)
	{
		$hostname	= "localhost";
		$connect	= @mysql_connect($hostname,$user_id,$password);
	}

	mysql_select_db($dbname, $connect);

	// ������ ���̺� ����
	@mysql_query($admin_table_schema, $connect);

  	// �׷����̺� ����
	@mysql_query($group_table_schema, $connect);

	// ȸ������ ���̺� ����
	@mysql_query($member_table_schema, $connect);

	// �������̺�
	@mysql_query($get_memo_table_schema, $connect);
	@mysql_query($send_memo_table_schema, $connect);

	// ���Ϸ� DB ���� ����
	$file = @fopen("config.php","w");
	@fwrite($file,"<?\n$hostname\n$user_id\n$password\n$dbname\n?>\n");
	@fclose($file);
	@mkdir("data",0707);
	@mkdir("icon",0707);
	@mkdir("icon/member_image_box",0707);
	@mkdir("icon/private_icon",0707);
	@mkdir("icon/private_name",0707);
	@chmod("icon/member_image_box",0707);
	@chmod("icon/private_icon",0707);
	@chmod("icon/private_name",0707);
	@chmod("data",0707);
	@chmod("icon",0707);
	@chmod("config.php",0707);

	$temp = mysql_fetch_array(mysql_query("select count(*) from $member_table where is_admin = '1'",$connect));
	@mysql_query("INSERT INTO $member_table (user_id,password,name,is_admin,reg_date,level) values ('$user_id',password('$password'),'$name','1','".time()."','1')",$connect) or Error(mysql_error(),"");
	echo "<script>opener.location='admin.php'; self.close();</script>";
	
	mysql_close($connect);
?>