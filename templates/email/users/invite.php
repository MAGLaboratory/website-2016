<!DOCTYPE html>
<html>
<head>
<meta content='width=device-width' name='viewport'>
<meta content='text/html; charset=UTF-8' http-equiv='Content-Type'>
<title>MagLaboratory Email</title>
</head>
<body bgcolor='#f6f6f6' style='height:100%;width:100%;'>
<table style='width:100%;padding:20px;'>
<tr>
<td></td>
<td bgcolor='#fafafa' class='container' style='clear: both !important; display: block !important; max-width: 600px !important; margin: 0 auto; padding: 20px; border: 1px solid #efefef;'>
<div style='display:block;max-width:600px;margin: 0 auto;'>
<table bgcolor='#fafafa' style='width:100%;'>
<tr>
<td>
<h1>Invitation to join MAGLaboratory Members Section</h1>
<br>
<p>
<?php echo filter_text($inviter->first_name . ' ' . $inviter->last_name , true); ?>
has invited you to join MAGLaboratory's members section.
</p>
<br>
<p>
<a href='<?php echo $invite_url; ?>'>Click here</a>
setup your account password.
</p>
<br>
<p>
If the link above doesn't work, copy and paste this into your browser:
<?php echo $invite_url; ?>
</p>

</td>
</tr>
</table>
</div>
</td>
</tr>
</table>
<table style='clear: both !important; width:100%;'>
<tr>
<td></td>
<div style='display: block; max-width: 600px;margin:0 auto;'>
<table style='width: 100%;'>
<tr>
<td align='center'>
</td>
</tr>
</table>
</div>
</tr>
</table>
</body>
</html>
