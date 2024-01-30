<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();	  
$errors = [];

           


function died($error) {
        $er1 =  "We are very sorry, but there were error(s) found with the form you submitted. ";
         $er2 = "These errors appear below.<br /><br />";
         $er3 = $error."<br /><br />";
         $arr =  array( $er1,  $er2, $er3);
         $allErrors = join('', $arr);
      
	  $errorMessage = "<p style='color: red;'>{$allErrors}</p>"; $_SESSION['errorMessage'] = $errorMessage;
	   header('Location: ../contact');
  }
  

if (!empty($_POST)) {
    
    
      // reCAPTCHA validation
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
              

                $secretAPIkey = '6LcwN2ApAAAAAPBSzG_V_biGEtTMrWA5v2yt6WaW';
                // reCAPTCHA response verification
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretAPIkey.'&response='.$_POST['g-recaptcha-response']);
                
                // Decode JSON data
                $response = json_decode($verifyResponse);
               
                    if($response->success){
                        
                        
                         if(!empty($_POST['website'])){
                  died('We are sorry, but there appears to be a problem with the form you submitted.');
         
            }else{
    
		   $name = $_POST['name'];
		   $email = $_POST['email'];
		   $message = $_POST['message'];
		  
		   if (empty($name)) {
			   $errors[] = 'Name is empty';
		   }

		   if (empty($email)) {
			   $errors[] = 'Email is empty';
		   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			   $errors[] = 'Email is invalid';
		   }

		   if (empty($message)) {
			   $errors[] = 'Message is empty';
		   }
		   
		   if(preg_match('/http|www/i',$message)) {
                 $errors[] = "We do not allow a url in the Message. <br />";
               }
   
				if (empty($errors)) {
					
					
								require 'vendor/autoload.php';

								$Correo = new PHPMailer(true); 
								$bodyParagraphs = ["Name: {$name}", "Email: {$email}", "Message:", nl2br($message)];
								$body = join('<br />', $bodyParagraphs);
								try {
									//Server settings
									$Correo->isSMTP();                                      // Set mailer to use SMTP
									$Correo->Host = 'abseducation.com.ng';                       // Specify main and backup SMTP servers
									$Correo->SMTPAuth = true;                               // Enable SMTP authentication
									$Correo->Username = 'mails@abseducation.com.ng';     // Your Email/ Server Email
									$Correo->Password = 'Up}Sp;.K*Ifs';                     // Your Password
									$Correo->SMTPOptions = array(
										'ssl' => array(
										'verify_peer' => false,
										'verify_peer_name' => false,
										'allow_self_signed' => true
										)
									);                         
									$Correo->SMTPSecure = 'ssl';                           
									$Correo->Port = 465;                                   

									//Send Email
									$Correo->setFrom('mails@abseducation.com.ng');
									
									//Recipients
									$Correo->addAddress('info@abseducation.com.ng');              
									$Correo->addReplyTo($email);
						
									//Content
									$Correo->isHTML(true);                                  
									$Correo->Subject = "ABS Contact Form";
									$Correo->Body    = $body;

									if($Correo->send()){
										   $errorMessage = "<p style='color: green;'> Message Sent, Thank you for your feedback.</p>";
										   $_SESSION['errorMessage'] = $errorMessage;
										   header('Location: ../contact');
									}						
							
									} catch (Exception $e) {
											  $errors = 'Oops, something went wrong. Mailer Error: ' . $e;	 
									}
										
										
				  } else {
				
				   $allErrors = join('<br/>', $errors);
				   $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
				   $_SESSION['errorMessage'] = $errorMessage;
				   header('Location: ../contact');
			   }
   }
                        
                      
                    }  
                    else{
                           $errors[] = 'Robot verification failed, please try again.';      
                           $allErrors = join('<br/>', $errors);
        				   $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
        				   $_SESSION['errorMessage'] = $errorMessage;
				           header('Location: ../contact');
                    }
            
            
            } else{ 
               
                           $errors[] = 'Plese check on the reCAPTCHA box.';      
                           $allErrors = join('<br/>', $errors);
        				   $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
        				   $_SESSION['errorMessage'] = $errorMessage;
				           header('Location: ../contact');
                    
               
            } 
            
            
            
             
            
        
       
  
}
?>