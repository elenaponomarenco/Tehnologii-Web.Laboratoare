<?php

//declaram variabilele care vor transferate din fisierul html
$username = $_POST['username'];
$password = $_POST['password'];

//verificarea validarii(sunt goale variabilele sau nu)
if (!empty($username)  || !empty($password))  
{
    //declaram variabilele pentru a intra in baza de date
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "tw_lab4";

    //crearea conectiunii
    //mysqli = Represents a connection between PHP and a MySQL database.
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

    //to prevent from mysqli injection  
    $username = stripcslashes($username); //sterge backslash 
    $password = stripcslashes($password);  
    //sterge caracterele speciale pentru ca sirul sa fie valid pentru utilizarea acestuia in interogare viitoare
    $username = mysqli_real_escape_string($conn, $username);  
    $password = mysqli_real_escape_string($conn, $password); 

    //mysqli_connect_error = Returns a string description of the last connect error
    if (mysqli_connect_error())//daca conectiunea a eronat
    {
        ///primim mesaj corespunzator cu codul erorii si descrierea acesteia si programul isi termina executarea 
        die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } else { //daca conectarea a trecut cu succes
        //scriem urmatoarele interogari(query) 
        //selectam datele din tabel care corespund cu datele introduse de utilizator
        $SELECT = "SELECT * from register where username = '$username' and password = '$password' "; 
        //mysqli_query = efectuează o interogare asupra bazei de date
        $result = mysqli_query($conn, $SELECT);  
        //se preia un rând de rezultat ca asociativ, o matrice numerică sau ambele
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        //returneaza numarul de randuri primite ca rezultat
        $rnum = mysqli_num_rows($result); 

        if($rnum == 1){ //daca s-a gasit un rand atunci datele din Login exista in baza de date si utilizatorul poate intra in profil 
            echo "<h1><center> Login successful . " -" . <a href='D:\SOFT\XAMPP\htdocs\TW_php\Laboratorul 3\index.html' style='text-decoration:none;color:darkblue;'> Return Home</a></center></h1>";  
        }  
        else{  
            echo "<h1><center> Login failed. Invalid username or password. " -" . <a href='D:\SOFT\XAMPP\htdocs\TW_php\Laboratorul 3\index.html' style='text-decoration:none;color:darkblue;'> Return Home</a></center></h1>";  
        } 
    }
} else {//daca nu s-au introdus datele pe pagina se afiseaza mesajul corespunzator
    echo "<h1><center>All field are required!</center></h1>";
    die();//iesirea din program
}
?> 