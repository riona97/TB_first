<?php
$dsn='サーバー名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//3-2　こんなテーブル
$sql="CREATE TABLE IF NOT EXISTS mikan"."("."id INT auto_increment PRIMARY KEY,"."name char(32),"."comment TEXT,"."date TIMESTAMP,"."pw char(40)".");";
$stmt=$pdo->query($sql);

//if用単語
	$message = $_POST['message'];
	$name = $_POST['name'];
	$hidden= $_POST['hide'];
	$delete = $_POST['delete'];
	$edit = $_POST['change'];
	$time = date("Y/m/d H:i:s");


//3-8　削除
$password2 = $_POST['pw2'];//削除のパスワード
 if (!empty($delete) && !empty($password2)) {
$id=$_POST['delete'];
$pw2=$_POST['pw2'];
	$sql='SELECT*FROM mikan';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
	 if($row['id']===$id && $row['pw']===$pw2){
	 $id2=$_POST['delete'];
	 $sql='delete from mikan where id=:id';
	 $stmt=$pdo->prepare($sql);
	 $stmt->bindParam(':id',$id2,PDO::PARAM_INT);
	 $stmt->execute();

	 echo "削除されました。";
	 }elseif($row['id']===$id && $row['pw']!==$pw2){
	 echo "パスワードが間違っています。初めからやり直してください。";
	 }
	}
}


//3-7 編集


if(!empty($hidden)){

$id=$_POST['hide'];
$name=$_POST['name'];
$comment=$_POST['message'];
$$pw=$_POST['pw1'];
$sql='update mikan set name=:name,comment=:comment,pw=:pw where id=:id ';
$stmt=$pdo->prepare($sql);
$stmt->bindParam(':name',$name,PDO::PARAM_STR);
$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
$stmt->bindParam(':id',$id,PDO::PARAM_INT);
$stmt->bindParam(':pw',$pw2,PDO::PARAM_STR);
$stmt->execute();

}
$password1 = $_POST['pw1'];
if(!empty($name) && !empty($message) && !empty($password1)){	

//3-5 データ保存
$sql=$pdo->prepare("INSERT INTO mikan (name,comment,date,pw) VALUES(:name,:comment,:date,:pw)");
$sql->bindParam(':name',$name,PDO::PARAM_STR);
$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
$sql->bindParam(':date',$date,PDO::PARAM_STR);
$sql->bindParam(':pw',$pw,PDO::PARAM_STR);
$name=$_POST['name'];
$comment=$_POST['message'];
$date=date("Y/m/d H:i:s");
$pw=$_POST['pw1'];
$sql->execute();
}

// 編集編 コメ欄記載用
$password3 = $_POST['pw3'];//編集のパスワード
if (!empty($edit) && !empty($password3)) {

	$sql='SELECT*FROM mikan';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){

		if ($row['id'] == $edit && $row['pw']==$password3){//一致の際
                 for($h = 0; $h < count($row); $h++){
                    $simEdit[$h] = mb_substr(trim($row[$h]), 0);
                 }
		}elseif($row['id']==$edit && $row['pw']!==$password3){
		echo "パスワードが間違っています。初めからやり直してください。";
		}
		}
}
?>

<!DOCTYPE html>
<html lang="ja">
<html>
<head>
<title>nameテーブル表示</title>
<meta charset="utf-8">
<link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
</head>
<body>
<font size="5">
<h1><center>Free Bulletin Board</center></h1> 
</font>
<style>

body{
background-color:#eaeea2;
}
h1{
font-family: 'Dancing Script', cursive;
}
div{
background: #ffd700;
  width: 300px;
  padding: 10px;
  text-align: center;
  border: 1px solid #cccccc;
  margin: 30px auto;

}

</style>

</head>
<boby>

<div>
 <form method="POST" action="">
名前：<br>
<input type="text" name="name" value="<?php echo $simEdit[1]; ?>"placeholder="名前"><br>
コメント:<br>
<input type="text" name="message" value="<?php echo $simEdit[2]; ?>" placeholder="コメント"><br>
パスワード:<br>
<input tyep="text" name="pw1"  value="<?php echo $simEdit[4]; ?>" placeholder="パスワード"><br>
<input type="hidden" name="hide" value="<?php echo $simEdit[0]; ?>" >
<br>
<input type="submit"  value="送信">
<br>

 
削除対象番号:<br>
<input type="text" name="delete" placeholder="削除番号（半角）"><br>
<input tyep="text" name="pw2" placeholder="パスワード"><br>
<input type= "submit"  value="削除"><br>
<br>

編集対象番号:<br>
<input type='text' name="change" placeholder="編集番号（半角）"><br>
<input tyep="text" name="pw3" placeholder="パスワード"><br>
<input type= "submit"  value="編集"><br>
<br>
</form>
<br>
</div>


投稿履歴：<br><?php echo $row_count; ?>
 


<?PHP
//3-6
$sql='SELECT*FROM mikan order by id';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['date'].'<br>';
}
?>

</boby>
</html>
