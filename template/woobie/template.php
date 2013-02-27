<?php

// Important for IDEs
/* @var $this \skies\system\template\Template */

use skies\system\navigation\Navigation;
use skies\util\StringUtil;
use skies\system\template\Message;

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>

		<?php

		$this->printMeta(8);
		$this->printIncludes(8);
		$this->printFavicon(8);
		$this->printTitle(8);

		?>

	</head>
	<body>

		<!-- Start wrapper -->
		<div id="wrapper">

			<!-- Start navigation -->
			<?php (new \skies\system\navigation\Navigation(1))->printNav(); ?>
			<!-- End navigation -->


			<!-- Start logo -->
			<div id="logo">
				<img src="<?=SUBDIR?>/images/logo.png" />
			</div>
			<!-- End logo -->


			<!-- Start conent -->
			<div id="content">

				<?php

				\skies\system\template\Message::printAll();

				$this->printContent();

				?>

			</div>
			<!-- End content -->


            <!-- Start footer -->
            <div id="footer-wrapper">
                <div id="footer" style="line-height: 16px;">
                    This is GNU GPL, do with it what you want. Hosted by SkyIrc. - <a href="https://github.com/ozzyfant/Skies"><img style="width: 16px; vertical-align: middle;" src="<?=SUBDIR?>/images/github.png"</a>
                </div>
            </div>
            <!-- End footer -->

		</div>
		<!-- End wrapper -->
	</body>
</html>
