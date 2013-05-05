<div id="loginField">
	{if $user.isGuest}
		<a href="{pageLink page='login'}" id="loginLink">
			<i class="icon-signin"></i>&nbsp;&nbsp;{'system.page.login.login.title'|lang}
		</a>
	{else}
		<a href="{pageLink page='login'}" style="margin-right: 5px;">
			<i class="icon-user"></i>&nbsp;&nbsp;{$user.name|escape}
		</a>
		<span class="icon">&mdash;</span>
		<a href="{pageLink page='login' arguments=['logout']}" style="margin-left: 5px;">
			<i class="icon-signout"></i>&nbsp;&nbsp;{'system.page.login.logout.title'|lang}
		</a>
	{/if}
</div>

{if $user.isGuest}
	<div id="loginBox">
		<form action="{pageLink page='login' arguments=['refer']}" method="post">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-user"></i></span>
				<input type="text" required="required" name="username" id="username" placeholder="{'system.page.login.username'|lang}" />
			</div>
			<div class="input-prepend">
				<span class="add-on"><i class="icon-key"></i></span>
				<input type="password" required="required" name="password" id="password" placeholder="{'system.page.login.password'|lang}" />
			</div>
		<span class="float-left">
			<label for="longSession">{'system.page.login.login.longSession'|lang} </label><input type="checkbox" name="longSession" id="longSession" />
		</span>
			<input type="submit" name="login" id="login" value="{'system.page.login.login.login'|lang}" class="float-right" />

			<div class="clear"></div>
		</form>
	</div>
{/if}
