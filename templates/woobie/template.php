<?php

// Important for IDEs
/* @var $this \skies\system\template\Template */


?>
<!DOCTYPE html>
<html>
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
            
            <!-- End navigation -->
            
            
            <!-- Start logo -->
            <div id="logo"></div>
            <!-- End logo -->

            
            <!-- Start conent -->
            <div id="content">

<?php
                
                $this->printContent();
?>


            </div>
            <!-- End content -->
            
            
            <!-- Start footer -->
            <div id="footer-wrapper">
                <div id="footer">
                    <a href="<?=SUBDIR?>/impressum/">Impressum</a>
                </div>
            </div>
            <!-- End footer -->
        </div>
        <!-- End wrapper -->
    </body>
</html>