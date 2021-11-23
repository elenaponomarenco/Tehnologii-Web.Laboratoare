<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        <?php include 'C:\xampp\htdocs\TW_php\Laboratorul 3\html(pagini_aditionale)\css\contact_output.css'; ?>
    </style>
</head>
<body >
<?php 
//htmlspecialchars = convertarea caracterelor speciale în entități HTML(ex: & (ampersand) inlocuit cu &amp;)
$username = htmlspecialchars($_POST['username']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$message = htmlspecialchars($_POST['message']);

$erori = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") { //verifica daca forma a fost executata(submit), atunci ar trebui de validat aceasta
    # validare pentru username
    if (empty($_POST["username"])) {
       $username_err = "Name is required";
       $erori++;
    }else {
       $username_err = test_input($username);
    }
    # validare pentru email
    if (empty($_POST["email"])) {
      $email_err = "Email is required";
      $erori++;
    }else {
      $email_err = test_input($email);
      
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $email_err = "Invalid email format"; 
          $erori++;
      }
    }
    # validare pentru phone
    if (!is_numeric($phone)) {
      $phone_err = "'$phone' is invalid.\n";
      $erori++;
    } else {
      $phone = test_input($phone);
      $phone_err = " $phone\n";
    }
    if (empty($_POST["message"])) {
      $message_err = "Message is required";
      $erori++;
    }else {
        $message_err = $message;
    }
}
  function test_input($data) {
      $data = trim($data); //Elimina caracterele inutile (spațiu suplimentar, tab, newline)
      $data = stripslashes($data); //Elimina backslash (\)
      $data = htmlspecialchars($data);
      return $data;
  }
  
  function print_msg($username, $email, $phone, $message){
      echo "<div><form> 
          <h2><center>Your input</center></h2>
          <b>Username</b>: $username <br>
          <b>Email</b>: $email <br>
          <b>Phone</b>: $phone <br>
          <b>Message</b>: $message 
      </form></div>";
      
  }
  //$email este trecut prin filtru pentru a fi un email valid
  if($erori == 0){
    
    $receiver = "receiver_email_address"; //enter that email address where you want to receive all messages
    $subject = "From: $username <$email>";
    $body = "Name: $username\nEmail: $email\nPhone: $phone\n\nMessage:\n$message\n\nCu respect,\n$username";
    $sender = "From: $email";
    //mail() = trimite un simplu email
    if(mail($receiver, $subject, $body, $sender))
    {
      echo "<h1 style='color: #008000; position: absolute;
      top: 8%;
      left: 29%;'><center>Your message has been sent. Thank You!</center></h1>";
      print_msg($username_err, $email_err, $phone_err, $message_err);
    } else {
      echo "<h1 style='color: #FF0000;'><center>Sorry, failed to send your message!</center></h1>";
      print_msg($username_err, $email_err, $phone_err, $message_err);
    }
  } else {
    echo "<h1 style='color: #FF0000; font-family: Lucida Bright; position: absolute; top: 8%; left: 29%;' >
        You didn't filled up the form correctly!</h1>";
    print_msg($username_err, $email_err, $phone_err, $message_err);
  }
  
  
?>
<br>
    <a href="http://localhost/TW_php/Laboratorul%203/html(pagini_aditionale)/index.html">&#171;  &#206;napoi la pagina principal&#259;</a>
</body>
</html>