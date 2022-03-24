<html>
<head>
	<title>Janice Beauty Online Shop</title>
	<script src="admin/js/jquery.min.js"></script>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="topNavBar">
		<nav>
			<ul id="topNavBar">
				<li>
					<div class="cart">
						<img src="/admin/lib/images/cart.ico"/>
					</div>
				</li>
	  		<li><a href="HomePage.php"><img id="banner-pic" src="/admin/lib/images/logo.png"/></a></li>
				<li><img id=menuBtn src="/admin/lib/images/menu.png"></li>
			</ul>
		</nav>
	</div>
	<section id="banner">
	<div class="login-container">
	<div class="login-text">
		<form method="POST" action="auth-process.php?action=user_login" enctype="multipart/form-data">
			<label for="username">Email:</label><br>
			<input type="email" id="email" name="email" pattern="/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6}$/"><br>
			<label for="pwd">Password:</label><br>
			<input type="password" id="password" name="pwd" pattern="/^[\w@#$%\^\&\*\-]+$/" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"><br/>
			<input type="submit" value="Submit">
		</form>
		</br></br>
		<form action="admin-process.php?action=guest_login">
			<label for="guest">No account? Sign in as guest!</label><br>
			<input type="button" value="Guest">
		</form>
	</div>
	</div>
</body>
</html>
