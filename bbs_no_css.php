<?php
	//POST送信が行われたら、下記の処理を実行
	//テストコメント
	if(isset($_POST) && !empty($_POST)){
		$nickname = $_POST['nickname'];
		$comment = $_POST['comment'];

		//データベースに接続
		$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
		$user = 'root';
		$password = '';
		$dbh = new PDO($dsn,$user,$password);
		$dbh->query('SET NAMES utf8');

		//SQL文作成(INSERT文)
		$sql = 'INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,"'.$nickname.'","'.$comment.'",now())';
		//INSERT文実行
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		//データベースから切断
		$dbh = null;
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Oneline_bbs_セブ掲示版</title>

</head>
	<body>
		<form action="bbs_no_css.php" method="post">
			<input type="text" name="nickname" placeholder="nickname" required>
			<textarea type="text" name="comment" placeholder="comment" required></textarea>
			<button type="submit" >つぶやく</button>
	    </form>

	    <h2><a href="#">nickname Eriko</a> <span>2015-12-02 10:10:10</span></h2>
	    <p>つぶやきコメント2</p>

	    <h2><a href="#">nickname Eriko</a> <span>2015-12-02 10:10:10</span></h2>
	    <p>つぶやきコメント2</p>
	</body>
</html>

<!-- SELECT * FROM `posts` WHERE id=1 -->

