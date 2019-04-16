<?php
/*
Author: Osman Çakmak
Skype: oxcakmak
Email: oxcakmak@hotmail.com
Website: http://oxcakmak.com/
Country: Turkey [TR]
*/
//If you have post request
//strip_tags: clears html tags
//trim: removes blanks
require_once('config.php');
if(isset($_POST['actionRegister'])){
        $user_nickname = strip_tags(trim($_POST['user_nickname']));
        $user_email = strip_tags(trim($_POST['user_email']));
        $user_password = strip_tags(trim($_POST['user_password']));
        //if sent values are empty
        if(empty($user_nickname) || empty($user_password)){
            echo "space";
        }else{
            //if the user name is less than 5 characters long
            if(strlen($user_nickname) < 5){
                echo "min_five_username";
            }else{
                //if the password is less than 5 characters long
                if(strlen($user_password) < 5){
                    echo "min_five_password";
                }else{
                    $user_password = sha1(strip_tags(trim($_POST['user_password'])));
                    //if the e-mail address is equal to the supported service extension
                    //[https://github.com/oxcakmak/PHP-Email-Validate-Validator]
                    if(validateMail($user_email, $supportedMails)){
                        $registerCheckUserExists = $dbh->prepare("SELECT * FROM user WHERE user_nickname = :user_nickname");
                        $registerCheckUserExists->execute(array(
                            ":user_nickname" => $user_nickname
                        ));
                        $registerCheckUserExistsRow = $registerCheckUserExists->fetch(PDO::FETCH_ASSOC);
                        //if the user is present in the system
                        if($registerCheckUserExists->rowCount() > 0){
                            echo "exists";
                        }else{
                            //if the user is not present in the system
                            $insertRegisterUser = $dbh->prepare("INSERT INTO user (user_nickname, user_email, user_password, user_address, user_date, user_key) VALUES (:user_nickname, :user_email, :user_password, :user_address, :user_date, :user_key)");
                            $insertRegisterUser->execute(array(
                                ":user_nickname" => $user_nickname, 
                                ":user_email" => $user_email, 
                                ":user_password" => $user_password, 
                                ":user_address" => $newAddress, 
                                ":user_date" => $newDate,
                                ":user_key" => bin2hex(openssl_random_pseudo_bytes(16))
                            ));
                            echo "success";
                        }
                    }else{
                        echo "unsupported_mail_service";
                    }
                }
            }
        }
    }
    ?>
