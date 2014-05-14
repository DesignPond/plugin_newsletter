<?php
	
	// require wordpress bootstrap
	require_once( $_SERVER["DOCUMENT_ROOT"].'/wp-load.php' );
	
	// assets url for images and links
	$url   = plugins_url().'/dd_newsletter/assets/';	
	$home  = home_url();
	
	// database functions
	$database = new Database();	
	
	// weeke day range for query last week's arrets
	$dates  = $database->getWeekDays();
	
	// Get arrets
	$arrets = $database->getArretsAndCategoriesForDates($dates);
	
	// title dates of week
	$ladate = $database->arrangeDate($dates);
	
?>

<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">

    <title>Droit pour le Praticien | Newsletter</title>

    <style type="text/css">
    	
	    body{
            width: 100%; 
            background-color: #fafafa; 
            margin:0; 
            padding:0; 
            -webkit-font-smoothing: antialiased;
        }
        
        html{
            width: 100%; 
        }
        
        table{
            font-size: 14px;
            border: 0;
        }

        @media only screen and (max-width: 640px){
        

            body[yahoo] .header-bg{width: 440px !important; height: 10px !important;}
            body[yahoo] .main-header{line-height: 28px !important;}
            body[yahoo] .main-subheader{line-height: 28px !important;}
            
            body[yahoo] .container{width: 440px !important;}
            body[yahoo] .container-middle{width: 420px !important;}
            body[yahoo] .mainContent{width: 400px !important;}
            
            body[yahoo] .main-image{width: 400px !important; height: auto !important;}
            body[yahoo] .banner{width: 400px !important; height: auto !important;}
            /*------ sections ---------*/
            body[yahoo] .section-item{width: 400px !important;}
            body[yahoo] .section-img{width: 400px !important; height: auto !important;}
            /*------- prefooter ------*/
            body[yahoo] .prefooter-header{padding: 0 10px !important; line-height: 24px !important;}
            body[yahoo] .prefooter-subheader{padding: 0 10px !important; line-height: 24px !important;}
            /*------- footer ------*/
            body[yahoo] .top-bottom-bg{width: 420px !important; height: auto !important;}
            
        }
        
        @media only screen and (max-width: 479px){
        
        	/*------ top header ------ */
            body[yahoo] .header-bg{width: 280px !important; height: 10px !important;}
            body[yahoo] .top-header-left{width: 260px !important; text-align: center !important;}
            body[yahoo] .top-header-right{width: 260px !important;}
            body[yahoo] .main-header{line-height: 28px !important; text-align: center !important;}
            body[yahoo] .main-subheader{line-height: 28px !important; text-align: center !important;}
            
            /*------- header ----------*/
            body[yahoo] .logo{width: 260px !important;}
            body[yahoo] .nav{width: 260px !important;}
            
            body[yahoo] .container{width: 280px !important;}
            body[yahoo] .container-middle{width: 260px !important;}
            body[yahoo] .mainContent{width: 240px !important;}
            
            body[yahoo] .main-image{width: 240px !important; height: auto !important;}
            body[yahoo] .banner{width: 240px !important; height: auto !important;}
            /*------ sections ---------*/
            body[yahoo] .section-item{width: 240px !important;}
            body[yahoo] .section-img{width: 240px !important; height: auto !important;}
            /*------- prefooter ------*/
            body[yahoo] .prefooter-header{padding: 0 10px !important;line-height: 28px !important;}
            body[yahoo] .prefooter-subheader{padding: 0 10px !important; line-height: 28px !important;}
            /*------- footer ------*/
            body[yahoo] .top-bottom-bg{width: 260px !important; height: auto !important;}
            
	    }
	    
	    
    </style>
    
</head>

<body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

	<table border="0" width="100%" cellpadding="0" cellspacing="0">	
		<tr><td height="30"></td></tr>		
        <tr>       	
            <td width="100%" align="center" valign="top" bgcolor="#ffffff">
            	
                <!-- -------   top header   ---------- -->
                <table border="0" width="600" cellpadding="0" cellspacing="0" align="center" class="container">
                                    
                    <tr bgcolor="43637c"><td height="14"></td></tr>                   
                    <tr bgcolor="43637c">
	                    <td align="center">
	                    	<table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">
	                    		<tr>
	                    			<td>
					                    <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="top-header-left">
					                    	<tr>
					                    		<td align="center">
					                    			<table border="0" cellpadding="0" cellspacing="0" class="date">
					                    				<tr>
								                    		<td>
									                    		<img editable="true" mc:edit="icon1" width="13" height="13" style="display: block;" src="<?php echo $url; ?>img/icon-cal.png" alt="icon 1" />
								                    		</td>
								                    		<td>&nbsp;&nbsp;</td>
								                    		<td mc:edit="date" style="color: #fefefe; font-size: 11px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
								                    			
								                    			<?php 
								                    			
								                    				if( ! ini_get('date.timezone') )
																	{
																	    date_default_timezone_set('GMT');
																	}
																	
								                    				setlocale (LC_TIME, 'fr_FR');
																	$dateFormat = date("d-m-Y");
																	echo strftime("%A %d %B %Y",strtotime("$dateFormat")); 
								                    				
								                    			?>
								                    			
								                    		</td>
								                    	</tr>
				
					                    			</table>
					                    		</td>
					                    	</tr>
					                    </table>
					                    
					                    <table border="0" align="left" width="10" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="top-header-right">
					                    	<tr><td width="10" height="20"></td></tr>
					                    </table>
					                    
					                    <table border="0" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="top-header-right">
					                    	<tr>
					                    		<td align="center">
					                    			<table border="0" cellpadding="0" cellspacing="0" align="center" class="tel">
					                    				<tr>
								                    		<td>
									                    		<img editable="true" mc:edit="icon2" width="17" height="12" style="display: block;" src="<?php echo $url; ?>img/icon-email.png" alt="icon 2" />
								                    		</td>
								                    		<td>&nbsp;&nbsp;</td>
								                    		<td mc:edit="tel" style="color: #fefefe; font-size: 11px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
								                    			
								                    				<a style="color:#fff;text-decoration: none !important;" href="mailto:info@droitpourlepraticien.ch">info@droitpourlepraticien.ch</a>
								                    			
								                    		</td>
								                    	</tr>
					                    			</table>
					                    		</td>
					                    	</tr>					                    	
					                    </table>
	                    			</td>
	                    		</tr>
	                    	</table>
	                    </td>
                    </tr>
                    
                    <tr bgcolor="43637c"><td height="10"></td></tr>                  
                </table>
                
                <!-- --------    end top header    ---------- -->
                
                
                <!-- --------   main content--------- -->
                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="container" bgcolor="a6bfcf">
              	<!-- ------- Header  -------- -->
                	<tr bgcolor="a6bfcf"><td height="40"></td></tr>
                	
                	<tr>
	                	<td>
	                		<table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">
	                			<tr>
	                				<td>
	                					<table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="logo">
				                			<tr>
				                				<td align="center">
				                					<a href="<?php echo $home; ?>" style="display: block;text-decoration: none !important; border-style: none !important; border: 0 !important;"><img editable="true" mc:edit="logo" width="270" height="35" border="0" style="display: block;" src="<?php echo $url; ?>img/logo.png" alt="logo" /></a>
				                				</td>
				                			</tr>
				                		</table>		
				                		<table border="0" width="10" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="nav">
				                			<tr>
				                				<td height="10" width="20"></td>
				                			</tr>
				                		</table>
				                		<table border="0" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="nav">
				                			<tr><td height="10"></td></tr>
				                			<tr>
				                				<td align="center" mc:edit="navigation" style="font-size: 13px; font-family: Helvetica, Arial, sans-serif;">
				                					
				                						<a style="text-decoration: none !important; color: #0f4060" href="<?php echo $home; ?>"><strong>Home</strong></a>
					                                    <span style="text" class="navSpac">&nbsp;&nbsp;&nbsp;&nbsp;</span>
					                                    <a style="text-decoration: none !important; color: #0f4060" href="<?php echo $home; ?>/?page_id=17"><strong>Contact</strong></a>
				                					
				                				</td>
				                			</tr>
				                		</table>
	                				</td>
	                			</tr>
	                		</table>
	                	</td>
                	</tr>
                	
                	<tr bgcolor="a6bfcf"><td height="40"></td></tr>
                	<!-- -------- end header ------- -->
					
					
					<!-- ------- main section ------- -->                	
                	<tr mc:repeatable>
                		<td bgcolor="a6bfcf">
                			<table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">
	                				
	                			<tr bgcolor="ffffff"><td height="14"></td></tr>
	                			
	                			<tr bgcolor="ffffff"><td align="center">
	                				<img style="display: block;" mc:edit="main-img" class="main-image" width="538" height="180" src="<?php echo $url; ?>img/main-img.png" alt="derniers publications du TF" />
	                			</td></tr>
	                			
	                			<tr bgcolor="ffffff"><td height="20"></td></tr>
	                			
	                			<tr bgcolor="ffffff">
	                				<td>
	                					<table width="528" border="0" align="center" cellpadding="0" cellspacing="0" class="mainContent">
	                						<tr>	
	                							<td mc:edit="title1" class="main-header" style=" text-align:center; color: #0f4060; font-size: 16px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
				                					
				                						Semaine du <strong><?php echo $ladate; ?></strong>
				                					
				                				</td>
				                			</tr>		
	                					</table>
	                				</td>
	                			</tr>
	                			
	                			<tr bgcolor="ffffff"><td align="center" height="14" width="560"></td></tr>
	                			
	
                			</table>
                		</td>
                	</tr><!-- ------- end main section ------- -->
                	
                	
                	<tr mc:repeatable="campaigner" mc:variant="vertical space"><td height="35"></td></tr>

<?php 

	foreach($arrets as $arret)
	{

?>
                	<!-- ------- section 1 ------- -->
                	<tr mc:repeatable>
                		<td>
                			<table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">

                				<tr bgcolor="fafafa">
                					<td>
                						<table width="528" border="0" align="center" cellpadding="0" cellspacing="0" class="mainContent">
                							<tr><td height="20"></td></tr>
                							<tr>
                								<td>
                									<table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="section-item">
                										<tr><td height="6"></td></tr>
                										<tr>
                											<td><a href="" style="width: 130px; display: block; border-style: none !important; border: 0 !important;">
                											<img editable="true" mc:edit="image1" width="130" height="101" border="0" style="display: block;" src="<?php echo $url; ?>img/image1.png" alt="image1" class="section-img" /></a></td>
                										</tr>
                										<tr><td height="10"></td></tr>
                									</table>
                									
                									<table border="0" width="10" align="left" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" cellspacing="0">
                										<tr><td height="30" width="10"></td></tr>
                									</table>
                									
                									<table border="0" width="360" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="section-item">
                										<tr>
                											<td mc:edit="title2" style="color: #0f4060; font-size: 16px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
	                											
	                											<a target="_blank" href="<?php echo $home; ?>/?page_id=1143&arret=<?php echo $arret['numero_nouveaute']; ?>"  href="" style="color: #0f4060; font-size: 16px;text-decoration: none !important; font-weight: bold; font-family: Helvetica, Arial, sans-serif;" href="#">
	                												<?php echo $arret['numero_nouveaute']; ?>
	                											</a>
	                											
                											</td>
                										</tr>
                										<tr><td height="15"></td></tr>
                										<tr>
                											<td mc:edit="subtitle2" style="color: #0f4060; line-height: 22px; font-size: 12px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                													
                													Date de décision: <strong><?php echo $arret['dated_nouveaute']; ?></strong><br/>
                													<?php echo $arret['nameCat']; ?><br/>
                													<?php echo $arret['nameSub']; ?>
                												
                											</td>
                										</tr>
                										<tr><td height="15"></td></tr>
                										<tr>
                											<td>
                												<a target="_blank" href="<?php echo $home; ?>/?page_id=1143&arret=<?php echo $arret['numero_nouveaute']; ?>" style="display: block; width: 80px; border-style: none !important;text-decoration: none !important; border: 0 !important;">
                												<img editable="true" mc:edit="readMoreBtn" width="79" height="20" border="0" style="display: block;" src="<?php echo $url; ?>img/readmore-btn.png" alt="" /></a>
                											</td>
                										</tr>
                									</table>
                								</td>
                							</tr>
                							
                							<tr><td height="20"></td></tr>
                							
                						</table>
                					</td>
                				</tr>
                				

                				
                			</table>
                		</td>
                	</tr><!-- ------- end section 1 ------- -->
                	
                	
                	<tr><td height="35"></td></tr>
                	
<?php } ?>
                	
                	<tr>
                		<td align="center" mc:edit="copy1" style="color: #fff; font-size: 13px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;" class="prefooter-header">	                			                	Vous recevez cette newsletter car vous êtes inscrits sur le site 
                			<a style="text-decoration: none !important; color: #43637c;" href="<?php echo $home; ?>">www.droitpraticien.ch</a>
                		</td>
                	</tr>
                	<tr><td height="7"></td></tr>	
                	<tr>              		
                		<td align="center" mc:edit="copy1" style="color: #fff; font-size: 13px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;" class="prefooter-header">
	                			Pour vous désinscrire de cette newsletter <a style="text-decoration: none !important; color: #43637c;" href="<?php echo $home; ?>">cliquez ici</a>	                		
                		</td>
                	</tr>	
                	
                	<tr><td height="30"></td></tr>

                </table>
                
                <!-- ---------- end main Content --------------- -->
                                             
                <!-- -------- footer  ------- -->
                
                <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" align="center">
                	<tr bgcolor="43637c"><td height="14"></td></tr>
                	<tr bgcolor="43637c">
                		<td mc:edit="copy3" align="center" style="color: #fff; font-size: 10px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">                			
                				Droit pour le Praticien © Copyright <?php echo date('Y'); ?>                			
                		</td>
                	</tr>
                	
                	<tr bgcolor="43637c"><td height="14"></td></tr>
                </table>
                <!-- -------  end footer ------- -->
            </td>
        </tr>
        
        <tr><td height="30"></td></tr>
        
	</table>
	
	
</body>
</html>
	    