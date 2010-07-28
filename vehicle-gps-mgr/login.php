<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>首页</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<form method="post" action="login_check.php" name='theForm'>
<table border="0" align="center" cellpadding="0" cellspacing="4" bgcolor="#B9B9B9" class="alltable">
      <tr>
        <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
            <tr>
              <td height="50" colspan="2" align="center" bgcolor="#616161"><span class="style1"><font color="#FFFFFF">用户登录</font></span></td>
            </tr>
            <tr>
              <td width="203" align="center" bgcolor="#FFFFFF">
                  <img src="images/cat.jpg" width="203" height="149" /></td>
              <td align="center" bgcolor="#FFFFFF"><table>
              		<tr>
                    <td>公司登录ID：</td>
                    <td><input name="companyloginid" type="text" /></td>
                  </tr>
                  <tr>
                    <td>用户名：</td>
                    <td><input name="username" type="text" /></td>
                  </tr>
                  <tr>
                    <td>用户密码：</td>
                    <td><input name="password" type="password" /></td>
                  </tr>
                  <tr>
                  	<td>&nbsp;</td>
                  	
                  </tr>
                <tr>
                  <td colspan="2" align="center"><input name="submit" type="submit" class="button  style2" value="登录" /></td>
                </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
          </tr>
        </table>
	  <input type="hidden" name="act" value="signin" />
</form>
</body>
</html>