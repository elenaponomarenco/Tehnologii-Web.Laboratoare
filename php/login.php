<?php
    include 'db_connect.php';
    //declaram variabilele care vor transferate din fisierul html
    $username = $_POST['username'];
    $password = $_POST['password'];

    //to prevent from mysqli injection  
    $username = stripcslashes($username); //sterge backslash 
    $password = stripcslashes($password);  
    //sterge caracterele speciale pentru ca sirul sa fie valid pentru utilizarea acestuia in interogare viitoare
    $username = mysqli_real_escape_string($conn, $username);  
    $password = mysqli_real_escape_string($conn, $password); 

    $erori = 0;
    if ($_SERVER["REQUEST_METHOD"] == "POST") { //verifica daca forma a fost executata(submit), atunci ar trebui de validat aceasta
        # validare pentru username
        if (empty($_POST["username"])) {
           $username_err = "Username is required";
           $erori++;
        }else {
           $username_err = test_input($username);
        }
        # validare pentru password
        $number = preg_match('@[0-9]@', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        
        if(strlen($password) < 7 || !$number || !$uppercase || !$lowercase ) {
            $password_err = "Password is not valid.";
            $erori++;
        } else {
            $password = test_input($password);
            $password_err = "Your password is strong but wrong.";
        }
        if ($erori > 0)
        {
            echo json_encode(array("status" => "Validation_Fail"));
            die();
        } 
    }

    function test_input($data) {
        $data = trim($data); //Elimina caracterele inutile (spațiu suplimentar, tab, newline)
        $data = stripslashes($data); //Elimina backslash (\)
        $data = htmlspecialchars($data);
        return $data;
    }

    //mysqli_connect_error = Returns a string description of the last connect error
    if (mysqli_connect_error())//daca conectiunea a eronat
    {
        ///primim mesaj corespunzator cu codul erorii si descrierea acesteia si programul isi termina executarea 
        echo json_encode(array("status" => "Connect_Fail"));
        die();
    } elseif ($erori == 0) { //daca conectarea a trecut cu succes
        //scriem urmatoarele interogari(query) 
        //selectam datele din tabel care corespund cu datele introduse de utilizator
        $SELECT = "SELECT * from register where username = '$username' "; 
        //mysqli_query = efectuează o interogare asupra bazei de date
        $result = mysqli_query($conn, $SELECT);  
        //se preia un rând de rezultat ca asociativ, o matrice numerică sau ambele
        $err_pass = false;
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        // hash-uirea parolei pentru comparare cu cea din baza de date
        if (password_verify($password, $row["password"])){
            $err_pass = true;
        }

        //returneaza numarul de randuri primite ca rezultat
        if(mysqli_num_rows($result) == 1 && $err_pass){ //daca s-a gasit un rand atunci datele din 'register' exista in baza de date si utilizatorul poate intra in profil 
            {
                echo json_encode(array("status" => "Succes"));
            }
        }  
        else{ 
            echo json_encode(array("status" => "Login_Fail")); 
        } 
    }
?> 