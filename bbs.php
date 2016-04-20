<?php
require("dbconnect.php");
	if(isset($_POST) && !empty($_POST)){
		if (isset($_POST['update'])){
			//Update文を実行
			$sql = "UPDATE `posts` SET `nickname`='".$_POST['nickname']."',`comment`='".$_POST['comment'];
			$sql .= "',`created`=now() WHERE `id`=".$_POST['id'];
		}else{
			$sql = sprintf('INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,"%s","%s",now())',$_POST['nickname'],$_POST['comment']);
		}
			// INSERT文実行
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
	}
		// GET送信されたら編集処理するコード
		$editname='';
		$editcomment = '';
		$id = '';
		if (isset($_GET['action']) && ($_GET['action'] == 'edit')){
			//編集したいデータを取得するSQL文を作成（SELECT文）
			$selectsql = 'SELECT * FROM `posts` WHERE `id`='.$_GET['id'];
			//SQL文を実行
			$stmt = $dbh->prepare($selectsql);
			$stmt->execute();
			$rec = $stmt->fetch(PDO::FETCH_ASSOC);
			$editname = $rec['nickname'];
			$editcomment = $rec['comment'];
			$id = $rec['id'];
		}
		if (isset($_GET['action']) && ($_GET['action'] == 'delete')){
			$deletesql = "DELETE FROM `posts` WHERE `id`=".$_GET['id'];
			//SQL文を実行
			$stmt = $dbh->prepare($deletesql);
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
	$dbh = null;
 ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Music Lovers</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<div>
<link rel="stylesheet" href="main.css" type="text/css">
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="bbs.php"><span class="strong-title"><i class="fa fa-music" aria-hidden="true"></i> Music Lovers</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
<!--                   <li class="hidden">
                      <a href="#page-top"></a>
                  </li>
                  <li class="page-scroll">
                      <a href="#portfolio">Portfolio</a>
                  </li>
                  <li class="page-scroll">
                      <a href="#about">About</a>
                  </li>
                  <li class="page-scroll">
                      <a href="#contact">Contact</a>
                  </li> -->
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
        <form action="bbs.php" method="post">
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" value="<?php echo $editname; ?>" required>

              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>

          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required><?php echo $editcomment; ?></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>

			<?php if ($editname == ''){ ?>
				<button type="submit" name="insert"  class="btn btn-primary col-xs-12" disabled>つぶやく</button>
			<?php }else{ ?>
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<button type="submit" name="update" class="btn btn-primary col-xs-12" disabled>変更する</button>
			<?php } ?>

        </form>
      </div>

      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">

        <?php foreach ($posts as $post_each){ ?>
        <article class="timeline-entry">
            <div class="timeline-entry-inner">
            <a href="bbs.php?action=edit&id=<?php echo $post_each['id'];?>">
                <div class="timeline-icon bg-success">
                    <i class="entypo-feather"></i>
                    <i class="fa fa-star"></i>
                </div>
            </a>
                <div class="timeline-label clear">
                    <h2>
	                    <a href="#" id="nonclear"><?php echo $post_each['nickname']; ?></a>
	                    <span><?php echo $post_each['created']; ?></span>
                    </h2>
                    <p id="nonclear"><?php echo $post_each['comment']; ?></p>
                    <a href="bbs.php?action=delete&id=<?php echo $post_each['id'];?>"><i class="fa fa-ban fa-lg"></i></a>
                </div>
            </div>
        </article>
        <?php } ?>

        <article class="timeline-entry begin">
            <div class="timeline-entry-inner">
                <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                    <i class="entypo-flight"></i> +
                </div>
            </div>
        </article>
       </div>
      </div>
    </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>