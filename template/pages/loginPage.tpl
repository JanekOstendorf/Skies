<h1>{lang node='system.page.login.title'}</h1>

{if $user.isGuest}

	<p>
		{lang node='system.page.login.introduction'}
	</p>

	<fieldset class="float-left" style="width: 45%;">

		<legend>{lang node='system.page.login.login'}</legend>

		<form method="post">
			<table>
				<tr>
					<td>
						<label for="username">{lang node="system.page.login.username"}:</label>
					</td>
					<td>
						<input type="text" required="required" name="username" id="username" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password">{lang node="system.page.login.password"}:</label>
					</td>
					<td>
						<input type="password" required="required" name="password" id="password" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="login" id="login" value="{lang node="system.page.login.login"}" />
					</td>
				</tr>
			</table>
		</form>

	</fieldset>
	<fieldset class="float-right" style="width: 45%;">

		<legend>{lang node='system.page.login.sign-up'}</legend>

	</fieldset>
	<br class="clear" />

{else}
	<p>
		{lang node="system.page.login-weltome-title" userVars=["userName" => $user.name]}
	</p>
{/if}