<form>
<br><br><br>
<table border=0 cellspacing=0 cellpadding=0 width=250>
<tr>
 <td class=kissofgod-base-line></td>
</tr>
</table>
<table border=0 width=250>
<tr>
    <td align=center height=30>
      <br>
      <?echo $message;?><br><br>
<?
  if(!$url)
  {
?>

  <br>
  <center><input type=button value="Back" onclick=history.go(-1) class=kissofgod-submit>

<?
  }
  else
  {
?>

  <div align=center><input type=button value=' Move ' onclick=location.href="<?echo $url;?>" class=kissofgod-submit>

<?
  }
?>
   <br><br>
    </td>
</tr>
<tr>
 <td class=kissofgod-base-line></td>
</tr>
</form>
</table>
<br><br>
