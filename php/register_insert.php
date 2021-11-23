<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        <?php include 'C:\xampp\htdocs\TW_php\Laboratorul 3\html(pagini_aditionale)\css\register_output.css'; ?>
    </style>
</head>
<body >
    
<?php
    include 'db_connect.php';
    //declaram variabilele care vor transferate din fisierul html
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phoneCode = $_POST['phoneCode'];
    $phone = $_POST['phone'];

    $erori = 0;
    if ($_SERVER["REQUEST_METHOD"] == "POST") { //verifica daca forma a fost executata(submit), atunci ar trebui de validat aceasta
        # validare pentru username
        if (empty($_POST["username"])) {
           $username_err = "Name is required";
           $erori++;
        }else {
           $username_err = test_input($username);
        }
        # validare pentru password
        $number = preg_match('@[0-9]@', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        
        if(strlen($password) < 7 || !$number || !$uppercase || !$lowercase ) {
            $password_err = "Password is not valid. Try a stronger one!";
            $erori++;
        } else {
            $password = test_input($password);
            $password_err = "Your password is strong.";
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
        # validare pentru phoneCode
        if (!preg_match('/\+(\d+)/', $phoneCode)){
            $phoneCode_err = "'$phoneCode' is invalid";
            $erori++;
        } else {
            $phoneCode_err = test_input($phoneCode);
            $phoneCode_err = " $phoneCode";
        }
        # validare pentru phone
        if (!is_numeric($phone)) {
            $phone_err = "'$phone' is invalid.\n";
            $erori++;
        } else {
            $phone_err = test_input($phone);
            $phone_err = " $phone\n";
        }

        if ($erori > 0)
        {
            echo "<h1 style='color: #FF0000; font-family: Lucida Bright; position: absolute; top: 7%; left: 29%;' >
                                    You didn't filled up the form correctly!</h1>";
            print_msg($username_err, $password_err, $email_err, $phoneCode_err, $phone_err);
        } else {
            print_msg($username_err, $password_err, $email_err, $phoneCode_err, $phone_err);
        }
    }

    function test_input($data) {
        $data = trim($data); //Elimina caracterele inutile (spațiu suplimentar, tab, newline)
        $data = stripslashes($data); //Elimina backslash (\)
        $data = htmlspecialchars($data);
        return $data;
    }
    
    function print_msg($username, $password, $email, $phoneCode, $phone){
        echo "<div><form> 
            <h2><center>Your input</center></h2>
            <b>Username</b>: $username <br>
            <b>Password</b>: $password <br>
            <b>Email</b>: $email <br>
            <b>PhoneCode</b>: $phoneCode <br>
            <b>Phone</b>: $phone 
        </form></div>";
        
    }

        //mysqli_connect_error = Returns a string description of the last connect error
    if (mysqli_connect_error())//daca conectiunea a eronat
    {
        ///primim mesaj corespunzator cu codul erorii si descrierea acesteia si programul isi termina executarea 
        die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } elseif ($erori == 0) { //daca conectarea a trecut cu succes
        //scriem urmatoarele interogari(query) 
        //asa cum emailul la fiecare e unic inseamna ca selectam un email din tabela register
        $SELECT = "SELECT email from register where email = ? limit 1";
        //interogare pentru inserarea in tabel a datelor din forma introduse de utilizator
        // ? = este locul plasarii parametrilor viitori
        $INSERT = "INSERT into register (username, password, email, phoneCode, phone) values (?, ?, ?, ?, ?)";
        
        //prepare statement
        $stmt = $conn -> prepare($SELECT); //prepararea interogarii pentru executare
        $stmt -> bind_param("s", $email);//leagă variabile la o interogare pregătită ca parametri, in locul '?'
        $stmt -> execute(); //executarea interogarii
        $stmt -> bind_result($email);//leagă variabilele la o interogare pregătită pentru stocarea rezultatelor
        $stmt -> store_result(); //transferă un set de rezultate din query pregătită
        $rnum = $stmt -> num_rows(); //returneaza nr de randuri din setul de rezultate 

        if ($rnum == 0) //daca setul nu are nici un rezultat
        {
            $stmt -> close();//interogarea precedenta este inchisa pentru alta interogare

            $stmt = $conn -> prepare($INSERT);
            $stmt -> bind_param("sssii", $username, $password, $email, $phoneCode, $phone);
            $stmt -> execute();
            echo "<h1 style='color: #008000;'><center>New record inserted sucessfully!</center></h1>";//afisarea mesajului de introducerea datelor cu succes
        } else { //daca rnum nu este 0 atunci email-ul deja este inregistrat
            echo "<h1 style='color: #FF0000;'><center>This email is already registered!</center></h1>";
        }
        $stmt -> close();
        $conn -> close();
    } 
?>
<br>
    <a href="http://localhost/TW_php/Laboratorul%203/html(pagini_aditionale)/index.html">&#171;  &#206;napoi la pagina principal&#259;</a>
</body>
</html>