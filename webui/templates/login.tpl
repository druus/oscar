{% include 'header.tpl' %}

{% include 'navbar_top.tpl' %}

<link href="css/login.css" rel="stylesheet" type="text/css">
<div id="main">
  <div id="login">
  <h2>Login</h2>
  <form action="" method="post">
  <input type="hidden" name="cmd" value="login"/>
    <label>Username:</label>
    <input id="name" name="username" placeholder="username" type="text">
    <label>Password:</label>
    <input id="password" name="password" placeholder="**********" type="password">
    <input name="submit" type="submit" value=" Login ">
    <span>{{ error_msg }}</span>
  </form>
  </div>
</div>

{% include 'footer.tpl' %}

