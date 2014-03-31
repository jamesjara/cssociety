<!-- Begin Main Menu -->
<div class="navbar navbar-inverse navbar-fixed-top">
              <div class="navbar-inner">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand" href="#"><img src="<?php echo cdn_logo ;?>"  /></a></a>
                  <div class="nav-collapse collapse navbar-responsive-collapse">
				  <?php $RootMenu = new cMenu("RootMenu"); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "audittraillist.php", 8, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "ownerslist.php", 8, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(20, $Language->MenuPhrase("20", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(10, $Language->MenuPhrase("10", "MenuText"), "fb_postslist.php?cmd=resetall", 20, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "blogslist.php", 20, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "fb_gruposlist.php?cmd=resetall", 20, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "feedbacklist.php", 20, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "noticiaslist.php", 20, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "paiseslist.php", -1, "", IsLoggedIn(), FALSE);

//$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
				 <?php 
					include("clients/".conf_domain.'.php');
				?>
                  </div><!-- /.nav-collapse -->
                </div>
              </div><!-- /navbar-inner -->
</div>
<!--</div>
 End Main Menu -->
