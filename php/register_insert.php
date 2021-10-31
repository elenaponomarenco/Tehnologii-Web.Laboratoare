<?php

function function_alert($message) {
      
    // Display the alert box 
    echo "<script>window.confirm('$message');</script>";
}

//declaram variabilele care vor transferate din fisierul html
$username = $_POST['username'];
$password = $_POST['password'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$phoneCode = $_POST['phoneCode'];
$phone = $_POST['phone'];

//verificarea validarii(sunt goale variabilele sau nu)
if (!empty($username)  || !empty($password) || !empty($gender) || !empty($email) || !empty($phoneCode) || !empty($phone))  
{
    //declaram variabilele pentru a intra in baza de date
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "tw_lab4";

    //crearea conectiunii
    //mysqli = Represents a connection between PHP and a MySQL database.
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
    //mysqli_connect_error = Returns a string description of the last connect error
    if (mysqli_connect_error())//daca conectiunea a eronat
    {
        ///primim mesaj corespunzator cu codul erorii si descrierea acesteia si programul isi termina executarea 
        die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } else { //daca conectarea a trecut cu succes
        //scriem urmatoarele interogari(query) 
        //asa cum emailul la fiecare e unic inseamna ca selectam un email din tabela register
        $SELECT = "SELECT email from register where email = ? limit 1";
        //interogare pentru inserarea in tabel a datelor din forma introduse de utilizator
        // ? = este locul plasarii parametrilor viitori
        $INSERT = "INSERT into register (username, password, gender, email, phoneCode, phone) values (?, ?, ?, ?, ?, ?)";
        
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
            $stmt -> bind_param("ssssii", $username, $password, $gender, $email, $phoneCode, $phone);
            $stmt -> execute();
            echo "<h1><center>New record inserted sucessfully. " -" . <a href='D:\SOFT\XAMPP\htdocs\TW_php\Laboratorul 3\index.html' style='text-decoration:none;color:darkblue;'> Return Home</a></center></h1>";//afisarea mesajului de introducerea datelor cu succes
        } else { //daca rnum nu este 0 atunci email-ul deja este inregistrat
            function_alert("<h1><center>Someone already register using this email</center></h1>");
        }
        $stmt -> close();
        $conn -> close();
    }
} else {//daca nu s-au introdus datele pe pagina se afiseaza mesajul corespunzator
    echo "<h1><center>All field are required!</center></h1>";
    die();//iesirea din program
}
?>