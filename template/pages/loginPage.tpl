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
		<form method="post">
			<table>
				<tr>
					<td>
						<label for="signUpUsername">{lang node="system.page.login.username"}:</label>
					</td>
					<td>
						<input type="text" required="required" name="signUpUsername" id="signUpUsername" pattern="{$loginPage.usernamePattern}" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="mail">{lang node="system.page.login.mail"}:</label>
					</td>
					<td>
						<input type="text" required="required" name="mail" id="mail" pattern="{$loginPage.mailPattern}" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password1">{lang node="system.page.login.register.password-twice"}:</label>
					</td>
					<td>
						<input type="password" required="required" name="password1" id="password1" /><br />
						<input type="password" required="required" name="password2" id="password2" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="signUp" id="signUp" value="{lang node="system.page.login.sign-up"}" />
					</td>
				</tr>
			</table>
		</form>

	</fieldset>
	<br class="clear" />

{else}
	<p>
		{lang node="system.page.login.welcome-title" userVars=["userName" => $user.name]}
	</p>

	<fieldset class="float-left" style="width: 45%;">
		<legend>{lang node="system.page.login.change.mail.title"}</legend>

		<p class="description">
			{lang node="system.page.login.change.mail.description"}
		</p>

		<form method="post">

			<table>
				<tr>
					<td>
						<label for="changeMail">{lang node="system.page.login.mail"}:</label>
					</td>
					<td>
						<input type="text" required="required" name="changeMail" id="changeMail" pattern="{$loginPage.mailPattern}" value="{$loginPage.changeForm.mail|default:$user.mail}" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="changeMailPassword">{lang node="system.page.login.change.current-password"}:</label>
					</td>
					<td>
						<input type="password" required="required" name="changeMailPassword" id="changeMailPassword" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="changeMail" id="changeMail" value="{lang node="system.page.login.change"}" />
					</td>
				</tr>
			</table>

		</form>

	</fieldset>

	<fieldset class="float-right" style="width: 45%;">

		<legend>{lang node="system.page.login.change.password.title"}</legend>

		<p class="description">
			{lang node="system.page.login.change.password.description"}
		</p>

		<form method="post">

			<table>
				<tr>
					<td>
						<label for="changePassword1">{lang node="system.page.login.change.new-password-twice"}:</label>
					</td>
					<td>
						<input type="password" required="required" name="changePassword1" id="changePassword1" /><br />
						<input type="password" required="required" name="changePassword2" id="changePassword2" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="changePassword" id="changePassword" value="{lang node="system.page.login.change"}" />
					</td>
				</tr>
			</table>

		</form>

	</fieldset>

	<br class="clear" />

{/if}
