<?php 
//htmlspecialchars = convertarea caracterelor speciale în entități HTML(ex: & (ampersand) inlocuit cu &amp;)
$username = htmlspecialchars($_POST['username']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$message = htmlspecialchars($_POST['message']);

if(!empty($email) && !empty($message)){
    //$email este trecut prin filtru pentru a fi un email valid
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      
      $receiver = "receiver_email_address"; //enter that email address where you want to receive all messages
      $subject = "From: $username <$email>";
      $body = "Name: $username\nEmail: $email\nPhone: $phone\n\nMessage:\n$message\n\nCu respect,\n$username";
      $sender = "From: $email";
      //mail() = trimite un simplu email
      if(mail($receiver, $subject, $body, $sender))
      {
         echo "<h1><center>Your message has been sent. Thank You!" . " -" . "<a href='D:\SOFT\XAMPP\htdocs\TW_php\Laboratorul 3\index.html' style='text-decoration:none;color:darkblue;'> Return Home</a></center></h1>";
      } else {
         echo "Sorry, failed to send your message!";
      }
    } else {
      echo "<h1><center>Enter a valid email address!</center></h1>";
    }
  } else {
    echo "<h1><center>Email and message field is required!</center></h1>";
  }

?>