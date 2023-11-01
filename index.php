<?php
include('header.php');
include('conn.php');
include('scripts.php');


$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$cpassword = $_POST['password'] ?? null;
$notes = $_POST['notes'] ?? null;
$timein = "";
$timeout = "";

date_default_timezone_set("Asia/Manila");
$curdate = date("Y-m-d");
$curtime = date("H:i:s");
$timezone = date_default_timezone_set("Asia/Manila");


if(isset($_POST['timein'])) {
    if($username == '' && $password == ''){
        echo "<script>alert('All fields required.');</script>";
    }else{
        $checkUsers = $conn->query("SELECT * FROM tblUsers where username='$username' && password='$password'"); //dapat talaga may '' ung variable
        if (mysqli_num_rows($checkUsers) > 0){
            $checkDate = $conn->query("SELECT * FROM $username WHERE date = '$curdate'");

            if (mysqli_num_rows($checkDate) != 0){
                $datelogassoc = mysqli_fetch_assoc($checkDate);
                $dateassoc = $datelogassoc['date'];
                $timeinassoc = $datelogassoc['timein'];
                $timeoutassoc = $datelogassoc['timeout'];
                

                if(!empty($timeinassoc)){
                    if(!empty($timeoutassoc)){
                        session_start();
                        $_SESSION['username'] = $username;
                        

                        // echo "
                        //     <script>
                        //     alert('You are already logged in and logged out today!');
                        //     window.location.href = 'view.php';
                        //     </script>
                        // ";

                    //     echo "
                    
                    //     <div style='style='position: absolute; top:50vh; left:80vh'>  
                    //         <div class='toast' role='alert' aria-live='assertive' aria-atomic='true'>  
                    //             <div class='toast-header' data-delay='10000'>
                    //                 <strong class='mr-auto text-primary'>Toast Header</strong>
                    //                 <small class='text-muted'>5 mins ago</small>
                    //                 <button type='button' class='ml-2 mb-1 close' data-dismiss='toast'>&times;</button>
                    //                 </div>
                    //                 <div class='toast-body'>
                    //                 You are already logged in!
                    //             </div>
                    //         </div>
                    //     </div>
                    // ";
                    $alreadyLogin = "You are already logged in!";
                    $displayMsg = $alreadyLogin;
                    include('functions.php');
                    showToasts();
                    
                    //alreadyLogin();

                        // exit(header("location: view.php"));
                        $timein = "disabled";
                        $timeout = "disabled";
                    }else{
                        echo "<script>alert('You are already logged in. Please logout.');</script>";
                        $timein = "disabled";
                    }
                }else{
                    $insert = $conn->query("INSERT INTO $username (`date`, `timein`, `timeout`, `notes`) VALUES ('$curdate', '$curtime', '00:00:00', '$notes')");
                    //echo "<script>alert('Succesfully logged in!');</script>";
                    echo "
                    
                    <div class='toast' >    
                    <div class='toast-header' data-delay='5000'>
      <strong class='mr-auto text-primary'>Toast Header</strong>
      <small class='text-muted'>5 mins ago</small>
      <button type='button' class='ml-2 mb-1 close' data-dismiss='toast'>&times;</button>
    </div>
    <div class='toast-body'>
      Some text inside the toast body
    </div>
  </div>
                    ";
                }
            }else{
               $insert = $conn->query("INSERT INTO $username (`date`, `timein`, `timeout`, `notes`) VALUES ('$curdate', '$curtime', '00:00:00', '$notes')");
                // echo "<script>alert('Succesfully logged in!');</script>"; 
                echo "
                    
                <div class='toast' data-delay='10000'>    
                <div class='toast-header'>
      <strong class='mr-auto text-primary'>Toast Header</strong>
      <small class='text-muted'>5 mins ago</small>
      <button type='button' class='ml-2 mb-1 close' data-dismiss='toast'>&times;</button>
    </div>
    <div class='toast-body'>
      Some text inside the toast body
    </div>
  </div>
                    ";
            }
        }
    }
}

if(isset($_POST['timeout'])) {
    if($username == '' && $password == ''){
        echo "<script>alert('All fields required.');</script>";
    }else{
        $checkUsers = $conn->query("SELECT * FROM `tblUsers` where `username`='$username' && `password`='$password'");
        if (mysqli_num_rows($checkUsers) > 0){
            $checkDate = $conn->query("SELECT * FROM $username WHERE `date` = '$curdate'");

            if (mysqli_num_rows($checkDate) != 0){
                $datelogassoc = mysqli_fetch_assoc($checkDate);
                $timeoutassoc = $datelogassoc['timeout'];

                if($timeoutassoc != '00:00:00'){
                    echo "
                        <script>
                            alert('You are already logged out!');
                            window.location.href = 'view.php';
                        </script>
                    ";
                    $timeout = "disabled";
                }else{
                    $Timeout = $conn->query("UPDATE $username SET `timeout`='$curtime', `notes`='$notes' WHERE `date`='$curdate'");
                    echo "<script>alert(`Succesfully logged out!`);</script>";
                }
            }
        }
    }
}


if(isset($_POST['view'])) {
    if($username == '' && $password == ''){
        echo "<script>alert('All fields required.');</script>";
    }else{
        $checkUsers = $conn->query("SELECT * FROM tblUsers where username='$username' && password='$password'");
        if (mysqli_num_rows($checkUsers) > 0){
            session_start();
            $_SESSION['username'] = $username;
            exit(header("location: view.php"));
        }
    }
}
?>
<title>Webtimekeeper</title>



    <div class="card   shadow " style="border-radius: 1rem;">
        <div class="card-body p-4 text-center">
            <form class="col-12" action="" method="post">
                <div class="text-center">
                    <h4 class="mb-3">Webtimekeeper <i class="bi bi-clock"></i>  </h4>
                </div>

                <hr>

                <div>
                    <?php echo date_default_timezone_get() ;?> <i class="bi bi-geo-alt"></i>
                </div>

                <div >
                    <h1 id="txt" class="display-4"></h1>
                </div>

                <div>
                    <?php echo $date= date('l, M j') ;?>
                </div>

                <div class="form-group mt-3">
                    <input type="text" name="username" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Enter username" required>
                    <!-- <small id="usernameHelp" class="form-text text-muted">We'll never share your details with anyone else.</small> -->
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" aria-label="Enter password" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2"><i class="bi bi-eye" onclick="ShowPass()"></i></span>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="notes" id="notes" class="form-control" placeholder="Enter notes" aria-label="Enter notes" aria-describedby="basic-addon2">
                </div>
                
                <div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
                    <input type="submit" name="timein" class="btn btn-success" value="Timein" <?php echo $timein;?>/>
                    <input type="submit" name="timeout" class="btn btn-primary" value="Timeout" <?php echo $timeout;?>/>
                    <input type="submit" name="view" class="btn btn-info" value="View"/>
                </div>

                <p class="text-center text-muted mt-3 mb-0">Don't have an account?  <a href="register.php"
                    class=""><u>Register here</u></a>
                </p>
            </form>   
        </div>
    </div>


<?php include('footer.php');?>

