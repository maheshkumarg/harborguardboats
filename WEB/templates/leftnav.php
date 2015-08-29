<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element">
					<span> <img alt="image" class="img-circle" src="images/blank-user.gif" /> </span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?=$_SESSION['firstName'] . " " . $_SESSION['lastName'] ?></strong> </span> <span class="text-muted text-xs block"><?=$_SESSION['userType'] ?><b class="caret"></b></span> </span> </a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<li>
							<a href="profile.php">Profile</a>
						</li>
					</ul>
				</div>
				<div class="logo-element">
					IN+
				</div>
			</li>
			<li <?php
			if ($current == 'materials') {echo ' class="active"';
			}
 				?>>
				<a href="materials.php"><i class="fa fa-th-large"></i> <span class="nav-label">Materials</span></a>
			</li>
			<li <?php
			if ($current == 'process') {echo ' class="active"';
			}
 				?>>
				<a href="process.php"><i class="fa fa-tasks"></i> <span class="nav-label">Process</span></a>
			</li>
			<li <?php
			if ($current == 'processgrps') {echo ' class="active"';
			}
 				?>>
				<a href="processgrps.php"><i class="fa fa-sitemap"></i> <span class="nav-label">Process Groups</span></a>
			</li>
			<li <?php
			if ($current == 'boats') {echo ' class="active"';
			}
 				?>>
				<a href="boats.php"><i class="fa fa-th-large"></i> <span class="nav-label">Boats</span></a>
			</li>
			<li <?php
			if ($current == 'users') {echo ' class="active"';
			}
 				?>>
				<a href="users.php"><i class="fa fa-users"></i> <span class="nav-label">Users</span></a>
			</li>
		</ul>
	</div>
</nav>