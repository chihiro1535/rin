<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-1 </title>
</head>
<body>

<?php
//DB接続設定
$dsn="データベース名";
   $user="ユーザー名";
   $password="パスワード";
   $dbh=new PDO($dsn,$user,$password,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false,]);

   $sql = "CREATE TABLE IF NOT EXISTS m5_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password TEXT,"
    . "date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP "
    .");";
    $stmt = $dbh->query($sql);


//データ入力
if(isset($_POST["submit"])){
  $name=$_POST["name"];
  $comment=$_POST["comment"];
  $password=$_POST["password"];
  $id = $_POST["editno"];
  $date=date("Y/m/d/ H:i:s");
    if(isset($name)&&isset($comment)&&isset($password)){
      if($name!==""&&$comment!==""&&$password!==""&&$id==""){
        $sql = $dbh -> prepare("INSERT INTO m5_1 (name, comment,password,date) VALUES (:name, :comment,:password,:date)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);//PDOStatement::bindParam — 指定された変数名にパラメータをバインドする
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> execute();
      }
    }
}
      
//削除
if(isset($_POST["dsubmit"])){
  $dpassword=$_POST["dpassword"];
  $id = $_POST["deleteNo"];
  if(isset($dpassword,$id)){
    if($dpassword!==""&&$id!==""){
      $sql = 'SELECT * FROM m5_1';
      $stmt = $dbh->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row){
        if($id==$row['id']&&$dpassword==$row['password']){ 
          $sql = 'delete from m5_1 where id=:id';
          $stmt = $dbh->prepare($sql);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
        } 
      }    
    }
  } 
}

//投稿フォームに再表示
if(isset($_POST["esubmit"])){
  $eid=$_POST["edit"];
  $epassword=$_POST["epassword"];
  if(isset($eid,$epassword)){
      if($eid!==""&&$epassword!==""){
          $sql = 'SELECT * FROM m5_1';
          $stmt = $dbh->query($sql);
          $results = $stmt->fetchAll();
          foreach ($results as $row){
          if($eid==$row["id"]&&$epassword==$row["password"]){
              $newcomment=$row["comment"];
              $newname=$row["name"];
              $newid=$row["id"];
              }
          }
      }
  }
}

//編集
if(isset($_POST["submit"])){
        $names = $_POST["name"];
        $comments = $_POST["comment"];
        $id = $_POST["editno"];
        $date=date("Y/m/d/ H:i:s");
     if(isset($_POST["editno"])){
        if(isset($names)&&isset($comments)){
            if($names!==""&&$comments!==""){
            $sql = 'UPDATE m5_1 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':name', $names, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comments, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
            }    
        }        
    }
}

?>
<form action="m5_1.php" method="post">
<input type="text" name="name" placeholder="名前入力" value=<?php if(isset($newname)){echo $newname;}?>> 
<input type="text" name="comment" placeholder="コメント入力" value=<?php if(isset($newcomment)){echo $newcomment;}?>>
<input type="text" name="password" placeholder="新規パスワード設定"> 
<input type="hidden" name="editno" value=<?php if(isset($newid)){echo $newid;}?>><!--隠し目印-->
<input type="submit" name="submit"value="送信"><br>
<input type="number" name="deleteNo" placeholder="削除番号入力">
<input type="text" name="dpassword" placeholder="既定のパスワード入力"> 
<input type="submit" name="dsubmit"value="削除"><br>
<input type="number" name="edit" placeholder="編集番号入力">
<input type="text" name="epassword" placeholder="既定のパスワード入力"> 
<input type="submit" name="esubmit"value="編集"><br><br>
</form>

<?php        
    $sql = 'SELECT * FROM m5_1';
	$stmt = $dbh->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
	echo "<hr>";
	}
  ?>

</body>
</html>