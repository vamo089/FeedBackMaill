<?php
$to = '';


 $name  = substr( $_POST['name'], 0, 64 );
 $phone = substr( $_POST['phone'], 0, 64 );
 $email   = substr( $_POST['email'], 0, 64 );
 $message = substr( $_POST['message'], 0, 500);


 if ( !empty( $_FILES['file']['tmp_name'] ) and $_FILES['file']['error'] == 0 ) {
   $sitename = $_SERVER['SERVER_NAME'];
   $uploaddir = "../../files/";
   $filepath = $_FILES['file']['tmp_name'];
   $filename = $_FILES['file']['name'];
   $filesize = $_FILES['file']['size'];
   $title = "$name($phone) Прислал файлы";
   if($filesize > 6000000){

     $uploadfile = $uploaddir . basename($_FILES['file']['name']);
     move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
     $message = "$message \n Файлы прикреплены к письму.\n $sitename/$uploaddir$filename";
   }
   else
     $message = "$message \n Файлы прикреплены к письму.";
}
  else {
   $filepath = '';
   $filename = '';
   $title .= "$name($phone)";
 }

 $message = "Сообщение пользователя:\n $message";
 send_mail($to, $message, $email, $filepath, $filename, $title, $filesize);







function send_mail($to, $body, $email, $filepath, $filename, $title, $filesize){
 $subject = "$title";
 $boundary = "--".md5(uniqid(time()));
 $headers = "From: ".$email."\r\n";
 $headers .= "MIME-Version: 1.0\r\n";
 $headers .="Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";
 $multipart = "--".$boundary."\r\n";
 $multipart .= "Content-type: text/plain; charset=\"utf-8\"\r\n";
 $multipart .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";

 $body = $body."\r\n\r\n";

 $multipart .= $body;

 $file = '';
 if ( !empty( $filepath ) ) {
   $fp = fopen($filepath, "r");
   if ( $fp ) {
     $content = fread($fp, filesize($filepath));
     fclose($fp);
     $file .= "--".$boundary."\r\n";
     $file .= "Content-Type: application/octet-stream\r\n";
     $file .= "Content-Transfer-Encoding: base64\r\n";
     $file .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
     $file .= chunk_split(base64_encode($content))."\r\n";
   }
 }
 $multipart .= $file."--".$boundary."--\r\n";
 mail($to, $subject, $multipart, $headers);
}
?>
