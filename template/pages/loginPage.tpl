<h1>{'system.page.login.title'|lang}</h1>

{if $user.isGuest}
	<p>
		{'system.page.login.introduction'|lang}
	</p>
	<fieldset class="float-left" style="width: 45%;">

		<legend>{'system.page.login.login.title'|lang}</legend>

		<form method="post">
			<table>
				<tr>
					<td>
						<label for="username">{'system.page.login.username'|lang}:</label>
					</td>
					<td>
						<input type="text" required="required" name="username" id="username" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password">{'system.page.login.password'|lang}:</label>
					</td>
					<td>
						<input type="password" required="required" name="password" id="password" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="longSession">{'system.page.login.login.longSession'|lang}:</label>
					</td>
					<td>
						<input type="checkbox" name="longSession" id="longSession" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="login" id="login" value="{'system.page.login.login.login'|lang}" />
					</td>
				</tr>
			</table>
		</form>

	</fieldset>
	<fieldset class="float-right" style="width: 45%;">

		<legend>{'system.page.login.signUp.title'|lang}</legend>
		<form method="post">
			<table>
				<tr>
					<td>
						<label for="signUpUsername">{'system.page.login.username'|lang}:</label>
					</td>
					<td>
						<input type="text" required="required" name="signUpUsername" id="signUpUsername" pattern="{$loginPage.usernamePattern}" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="mail">{'system.page.login.mail'|lang}:</label>
					</td>
					<td>
						<input type="text" required="required" name="mail" id="mail" pattern="{$loginPage.mailPattern}" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password1">{'system.page.login.passwordTwice'|lang}:</label>
					</td>
					<td>
						<input type="password" required="required" name="password1" id="password1" /><br />
						<input type="password" required="required" name="password2" id="password2" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="signUpSubmit" id="signUpSubmit" value="{'system.page.login.signUp.signUp'|lang}" />
					</td>
				</tr>
			</table>
		</form>

	</fieldset>
	<br class="clear" />
{else}
	<p>
		{'system.page.login.welcomeText'|lang:["userName" => $user.name]}
	</p>
	<fieldset class="float-left" style="width: 45%;">
		<legend>{'system.page.login.changeMail.title'|lang}</legend>

		<p class="description">
			{'system.page.login.changeMail.description'|lang}
		</p>

		<hr />

		<form method="post">

			<table>
				<tr>
					<td>
						<label for="changeMail">{'system.page.login.mail'|lang}:</label>
					</td>
					<td>
						<input type="text" required="required" name="changeMail" id="changeMail" pattern="{$loginPage.mailPattern}" value="{$loginPage.changeMail|default:$user.mail}" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="changeMailPassword">{'system.page.login.password'|lang}:</label>
					</td>
					<td>
						<input type="password" required="required" name="changeMailPassword" id="changeMailPassword" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="changeMailSubmit" id="changeMailSubmit" value="{'system.page.login.changeMail.change'|lang}" />
					</td>
				</tr>
			</table>

		</form>

	</fieldset>
	<fieldset class="float-right" style="width: 45%;">

		<legend>{'system.page.login.changePassword.title'|lang}</legend>

		<p class="description">
			{'system.page.login.changePassword.description'|lang}
		</p>

		<hr />

		<form method="post">

			<table>
				<tr>
					<td>
						<label for="changePassword1">{'system.page.login.passwordTwice'|lang}:</label>
					</td>
					<td>
						<input type="password" required="required" name="changePassword1" id="changePassword1" /><br />
						<input type="password" required="required" name="changePassword2" id="changePassword2" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="changePasswordSubmit" id="changePasswordSubmit" value="{'system.page.login.changePassword.change'|lang}" />
					</td>
				</tr>
			</table>

		</form>

	</fieldset>
	<br class="clear" />
	<fieldset class="float-left" style="width: 45%;">

		<legend>{'system.page.login.avatar.title'|lang}</legend>

		<p class="description">

			<img src="http://gravatar.com/avatar/{$user.mail|trim|strtolower|md5}.png?s=150" alt="avatar" class="float-left" id="avatar" />

			{'system.page.login.avatar.description'|lang}

		<p>
			<a href="http://gravatar.com/emails/">{'system.page.login.avatar.avatarLink'|lang}</a>
		</p>

		<div class="clear"></div>

		</p>


	</fieldset>
	<fieldset class="float-right" style="width: 45%;">

		<legend>{'system.page.login.chooseLanguage.title'|lang}</legend>

		<p class="description">
			{'system.page.login.chooseLanguage.description'|lang}
		</p>

		<hr />

		<form method="post">

			<table>
				<tr>
					<td>
						<label for="chooseLanguage">{'system.page.login.language'|lang}:</label>
					</td>
					<td>
						<select name="chooseLanguage" id="chooseLanguage">
							{foreach $loginPage.availableLanguages as $curLanguage}
								<option value="{$curLanguage.id}" title="{$curLanguage.description}"{if $curLanguage.id == $language.id} selected="selected"{/if}>{$curLanguage.title}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="chooseLanguageSubmit" id="chooseLanguageSubmit" value="{'system.page.login.chooseLanguage.change'|lang}" />
					</td>
				</tr>
			</table>


		</form>

	</fieldset>
	<br class="clear" />
{/if}
