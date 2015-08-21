<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link type="text/css" rel="stylesheet" href="css/common/reset.css">
	<link type="text/css" rel="stylesheet" href="css/common/common.css">
	<link type="text/css" rel="stylesheet" href="css/index.css">
	<script src="js/common/jquery-2.1.4.min.js"></script>
	<script src="js/index.js"></script>
	<title>掲示板テスト</title>
</head>

<!--Startbody-->
<body>
<div class="f-input_form">
	<form action="index.php" method="post" name = "thread_form" enctype="multipart/form-data">

	<div class="mod-comment-add">
		<table>
			<tr>
				<th>タイトル</th>
				<td>
					<?php
					//バリデーションエラーがあった時だけ、POST値をvalueに渡す
					$title_val = empty($errors['title']) && empty($errors['body']) ? '' : $post['title'];
					?>
					<input type="text" value="<?php echo $title_val ?>" name="title" class="<?php echo @$errors['title']['form_class']; ?>">
					<?php
					if(!empty($errors['title']))
						echo '<div class="' . $errors['title']['mes_class'] . '">' . $errors['title']['message'] . '</div>';
					?>
				</td>
			</tr>
			<tr>
				<th>本文</th>
				<td>
					<?php
					//バリデーションエラーがあった時だけ、POST値をvalueに渡す
					$body_val = empty($errors['title']) && empty($errors['body']) ? '' : $post['body'];
					?>
					<textarea name="body" class="<?php echo @$errors['body']['form_class']; ?>"><?php echo $body_val; ?></textarea>
					<?php
					if(!empty($errors['body']))
						echo '<div class="' . $errors['body']['mes_class'] . '">' . $errors['body']['message'] . '</div>';
					?>
				</td>
			</tr>
		</table>

		<p class="u-commit">
			<input name="commit" type="submit" value="投稿">
		</p>
	</div>
	</form>

	<div class="f-thread_list">
		<?php 
		//掲示板の一覧がある時
		if(!empty($thread_list)){
		?>
			<ul class="mod-main">
			<?php
			foreach($thread_list as $thread_val){
			?>
				<li id="anchor_<?php echo $thread_val['id'] ?>" class="mod-comment">
					<p>
						<span>
							【  I D  】&nbsp;<?php echo'[' .  $thread_val['id'] . ']　更新日時：' . date('Y/m/d H:i:s', $thread_val['updated_at']); ?>
						</span>
						<span>【タイトル】</span><span class="js-title_<?php echo $thread_val['id'] ?>">
							<?php echo $thread_val['title']; ?></span>
						<span>
							【 本　文 】</span><span class="js-body_<?php echo $thread_val['id'] ?>"><?php echo $thread_val['body']; ?>
						</span>
					</p>
				</li>
				<li class="thread_button">
					<div>
						<form action="index.php" method="post" name = "main_form<?php echo $thread_val['id']; ?>">
						<input name="reply"  type="button" value="返信">
						<input name="edit"   type="button" value="編集">
						<input name="delete" type="submit" value="削除">
						<input name="id" type="hidden" value="<?php echo $thread_val['id']; ?>">
						</form>
					</div>
				</li>
				<?php if(!empty($thread_val['reply'])) {?>
				<li>
					<ul class="mod-reply">
					<?php
					foreach($thread_val['reply'] as $reply_key => $reply_val){
					?>
						<li id="anchor_<?php echo $reply_val['id']; ?>" class="mod-comment">
							<p>
								<span>【  I D  】&nbsp;<?php echo'[' .  $reply_val['id'] . ']　'
										. '<a href="#anchor_' . $reply_val['reply_id'] . '">>[' . $reply_val['reply_id'] . ']</a>'
										. '　更新日時：' . date('Y/m/d H:i:s', $reply_val['updated_at']); ?></span>
								<span>【タイトル】</span><span class="js-title_<?php echo $reply_val['id'] ?>"><?php echo $reply_val['reply_title']; ?></span>
								<span>【 本　文 】</span><span class="js-body_<?php echo $reply_val['id'] ?>"><?php echo $reply_val['reply_body']; ?></span>
							</p>
						</li>
						<li class="thread_button">
							<div class="js-mod-reply_button">
								<form action="index.php" method="post" name = "reply_form<?php echo $thread_val['id']; ?>">
								<input name="reply"  type="button" value="返信">
								<input name="edit"   type="button" value="編集">
								<input name="delete" type="submit" value="削除">
								<input name="id" type="hidden" value="<?php echo $reply_val['id']; ?>">
								</form>
							</div>
						</li>
					<?php
					}
					?>
					</ul>
				</li>
				<?php
				}
			}
			?>
			</ul>
		<?php
		}
		//投稿されていないとき
		else{
			echo '<p class="u-none">投稿数が0件です</p>';
		}
		?>
	</div>
	<?php //バリデーションエラー時のフォームを開いた状態保持に使用 ?>
	<input name="post_form" type="hidden" value="<?php echo $post_form ?>">
	<?php //Ajax用　フォームのPOST値 ?>
	<input name="serialize_post" type="hidden" value="<?php echo empty($post) ? '' : base64_encode(serialize($post)); ?>">
	<?php //Ajax用　フォームのエラー値 ?>
	<input name="serialize_errors" type="hidden" value="<?php echo empty($errors) ? '' : base64_encode(serialize($errors)); ?>">
</div>

</body><!--EndBody-->
</html>
