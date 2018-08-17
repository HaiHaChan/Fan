<div id="fan" >
			<ul>
				<li>
					<apan><a href="<?php echo $app_path ?>home"><img src="<?php echo $app_path ?>icons/logo.png" ></a></apan>
				</li>
				<li>
					<div class="box">
					<form action="<?php echo $app_path ?>home" method="get" accept-charset="utf-8">
						<!-- <span><input type="hidden" name="action" value="search"></span> -->
						<input type="hidden" name="action" value="search">
						<span id="search"><input type="search" name="q" placeholder="search friend and group..." ></span>
					</form>
					</div>
				</li>
				<li>
						<a href="<?php echo $app_path ?>home?action=home&name=<?= $_SESSION['fan']['username'];?>">
						<img src="<?php echo $app_path."home/images/".$_SESSION['fan']['username'].'/avatar/'.$_SESSION['fan']['avatar'];?>" alt="" ></a>
				</li>
				<li>
					<div ><a href="<?php echo $app_path ?>home?action=online" ><img src="<?php echo $app_path ?>icons/group.png" alt="" ></a></div>
					<ul>
						<li><a href="<?php echo $app_path ?>home?action=create_group" >Create group</a></li>
						<li><a href="<?php echo $app_path ?>home?action=my_groups" >Groups</a></li>
					</ul>
				</li>
				<li>
					<a href="<?php echo $app_path ?>home?action=online" ><img src="<?php echo $app_path ?>icons/notifications.png" alt="" ></a>
				</li>
				<li class="li_menu">
					<a><img src="<?php echo $app_path ?>icons/arrow.png" alt="" ></a>
					<ul id="menu">
						<li><a href="<?php echo $app_path ?>home?action=edit_profile">Edit profile</a></li>
						<li><a href="<?php echo $app_path ?>home?action=reset_password_view">Reset password</a></li>
						<li><a href="<?php echo $app_path ?>home?action=logout" >Logout</a></li>
					</ul>
				</li>
		</ul>
</div>
	