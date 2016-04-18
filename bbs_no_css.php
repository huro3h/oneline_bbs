<?php
	//データベースに接続
	$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
	$user = 'root';
	$password = '';
	$dbh = new PDO($dsn,$user,$password);
	$dbh->query('SET NAMES utf8');

	//POST送信が行われたら、下記の処理を実行
	//テストコメント
	if(isset($_POST) && !empty($_POST)){

		$nickname = $_POST['nickname'];
		$comment = $_POST['comment'];

		//SQL文作成(INSERT文) 投稿する為のコード
		$sql = 'INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,"'.$nickname.'","'.$comment.'",now())';
		//INSERT文実行
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
	}

		// ここから掲示板に表示させる為のコード
		$sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';
		//SQL文実行
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		// $posts = array();
		// var_dump($stmt);
		while(1){
			//実行結果として得られたデータを表示
			$rec = $stmt->fetch(PDO::FETCH_ASSOC);
			if($rec == false){
		    	break;
	    	}
	    $posts[]=$rec;
	    // echo $rec['id'];
	    // echo $rec['nickname'];
	    // echo $rec['comment'];
	    // echo $rec['created'];

		}
		// データベースから切断
		$dbh = null;

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

	    <?php foreach ($posts as $post){ ?>

		    <h2><a href="#"><?php echo $post['nickname']; ?></a>
		    <span><?php echo $post['created']; ?></span></h2>
		    <p><?php echo $post['comment']; ?></p>

	    <?php } ?>

		    <!-- <h2><a href="#">nickname Eriko</a> -->
		    <!-- <span>2015-12-02 10:10:10</span></h2> -->
		    <!-- <p>つぶやきコメント2</p> -->

	</body>
</html>