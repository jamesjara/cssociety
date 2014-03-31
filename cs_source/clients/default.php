<ul class="nav pull-right">			 
                      <li class="divider-vertical"></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php if($Language->Phrase("opciones")!=null) echo $Language->Phrase("opciones"); else echo "Opciones";  ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
							<?php if (IsLoggedIn()){?>
							<li><a href="profile.php"><?php  if($Language->Phrase("perfil")!=null) echo $Language->Phrase("perfil"); else echo "Perfil";   ?></a></li>
							<li><a href="changepwd.php"><?php echo $Language->Phrase("ChangePwd");    ?></a></li>
							<?php } ?>
							<li class="divider"></li>
							<li class="nav-header"><?php if ($Language->Phrase("Language")!=null) echo $Language->Phrase("Language"); else echo "Idioma"; ?> </li>
									<?php
									foreach ($EW_LANGUAGE_FILE as $langfile) { 
									?>
									 <li <?php if ($gsLanguage == $langfile[0]) echo ' class="active" ';?> ><a href='?language=<?php echo $langfile[0]; ?>'><?php echo $langfile[1];?></a></li>
									<?php
									}
									?>
						<li class="divider"></li>
                          <li><a href="faq.php"><?php   if($Language->Phrase("ayuda")!=null) echo $Language->Phrase("Faq"); else echo "Faq";    ?></a></li>
                         <?php   
							if (IsLoggedIn()) { 
								echo '<li class="divider"></li> <li><a href="logout.php">'.$Language->Phrase("logout").'</a></li>';			
							}
						 ?>
                        </ul>
                      </li>					  
                      <li><a class="innosystem" target="_blank" href="http://www.innosystem.org"><span style="font-size: 15px;
color: rgb(185, 40, 21);font-weight: bold;" class="logo-red">
INNO</span><span style="color: rgb(180, 172, 172);font-weight: bold;font-size: 15px; " class="logo-white">SYSTEM
</span></a></li>
                    </ul>