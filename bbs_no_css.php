<?php
	//データベースに接続
	// $dsn = 'mysql:dbname=oneline_bbs;host=localhost';
	// $user = 'root';
	// $password = '';
	// $dbh = new PDO($dsn,$user,$password);
	// $dbh->query('SET NAMES utf8');

	require("dbconnect.php");

	//POST送信が行われたら、下記の処理を実行
	// テストコメント
	if(isset($_POST) && !empty($_POST)){
		// $nickname = $_POST['nickname'];
		// $comment = $_POST['comment'];

		// SQL文作成(INSERT文) 投稿する為のコード
		// $sql = 'INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,"'.$_POST['nickname'].'","'.$_POST['comment'].'",now())';
		// $sql = 'INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) ';
		// $sql .= 'VALUES (null,\''.$_POST['nickname'].'\',\''.$_POST['comment'].'\',now())';
		$sql = sprintf('INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,"%s","%s",now())',$_POST['nickname'],$_POST['comment']);
		// sprintf関数を使うことによって、カンマ区切りの後の引数だけ変えることができ、
		// 誤って記号を消してしまうのを防ぐことができる。

		// INSERT文実行
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
	}

		// ここから掲示板に表示させる為のコード
		$sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';
		//SQL文実行
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		// 格納する変数の初期化
		$posts = array();
		// var_dump($stmt);

		while(1){
			//実行結果として得られたデータを表示
			$rec = $stmt->fetch(PDO::FETCH_ASSOC);
			if($rec == false){
		    	break;
			}
		// 取得したデータを配列に格納しておく
		$posts[] = $rec;
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
	<title>oneline_bbs_no_cssセブ掲示版</title>


</head>
	<body>
		<form action="bbs_no_css.php" method="post">
		<!-- 自分のファイルに飛ばしたい場合、action=を省略しても可 -->
			<input type="text" name="nickname" placeholder="nickname" required>
			<textarea type="text" name="comment" placeholder="comment" required></textarea>
			<button type="submit" >つぶやく</button>
		</form>

		<?php foreach ($posts as $post_each){ ?>
		<?php echo '<h2><a href="#">'.$post_each['nickname'].'</a> <span>'.$post_each['created'].'</span></h2> <p>'.$post_each['comment'].'</p>' ?>

<!-- 			<h2><a href="#"><?php echo $post_each['nickname']; ?></a>
			<span><?php echo $post_each['created']; ?></span></h2>
			<p><?php echo $post_each['comment']; ?></p> -->

		<?php } ?>

			<!-- <h2><a href="#">nickname Eriko</a> -->
			<!-- <span>2015-12-02 10:10:10</span></h2> -->
			<!-- <p>つぶやきコメント2</p> -->

	</body>
</html>