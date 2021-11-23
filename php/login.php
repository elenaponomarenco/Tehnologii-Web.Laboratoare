<style>
    <?php include 'C:\xampp\htdocs\TW_php\Laboratorul 3\html(pagini_aditionale)\css\login_output.css'; ?>
</style>
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
            echo "<h1 style='color: #FF0000; font-family: Lucida Bright; position: absolute; top: 7%; left: 29%;' >
                                    You didn't filled up the form correctly!</h1>";
            print_msg($username_err, $password_err);
        } else {
            print_msg($username_err, $password_err);
        }
    }

    function test_input($data) {
        $data = trim($data); //Elimina caracterele inutile (spațiu suplimentar, tab, newline)
        $data = stripslashes($data); //Elimina backslash (\)
        $data = htmlspecialchars($data);
        return $data;
    }
    
    function print_msg($username, $password){
        echo "<div><form> 
            <h2><center>Your input</center></h2><br>
            <b>Username</b>: $username <br><br>
            <b>Password</b>: $password <br>
        </form></div>";
        
    }
    //mysqli_connect_error = Returns a string description of the last connect error
    if (mysqli_connect_error())//daca conectiunea a eronat
    {
        ///primim mesaj corespunzator cu codul erorii si descrierea acesteia si programul isi termina executarea 
        die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } elseif ($erori == 0) { //daca conectarea a trecut cu succes
        //scriem urmatoarele interogari(query) 
        //selectam datele din tabel care corespund cu datele introduse de utilizator
        $SELECT = "SELECT * from register where username = '$username' and password = '$password' "; 
        //mysqli_query = efectuează o interogare asupra bazei de date
        $result = mysqli_query($conn, $SELECT);  
        //se preia un rând de rezultat ca asociativ, o matrice numerică sau ambele
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        //returneaza numarul de randuri primite ca rezultat
        $rnum = mysqli_num_rows($result); 

        if($rnum == 1){ //daca s-a gasit un rand atunci datele din 'register' exista in baza de date si utilizatorul poate intra in profil 
            // echo "<h1><center> Login successful</center></h1>";
            // header('Location: http://localhost/TW_php/Laboratorul%203/html(pagini_aditionale)/index.html');
            // exit; 
           echo "<script>
            document.location='http://localhost/TW_php/Laboratorul%203/html(pagini_aditionale)/index.html';
            alert('Login successful');
            </script>" ;
        
    
        }  
        else{  
            echo "<h1 style='color: #FF0000;'><center> Login failed. Invalid username or password.</center></h1>";  
        } 
    }
?> 
<br>
    <a href="http://localhost/TW_php/Laboratorul%203/html(pagini_aditionale)/index.html">&#171;  &#206;napoi la pagina principal&#259;</a>