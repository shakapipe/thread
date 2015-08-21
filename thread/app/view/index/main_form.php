<li class="form_edit_<?php echo $form_data['id']; ?>">
	<form action="index.php" method="post" name = "edit_form<?php echo $form_data['id']; ?>" enctype="multipart/form-data">
	<div>
		<p>
			<em>【編集】</em>
			<em>タイトル：</em>
			<?php
			//バリデーションエラーがあった時だけ、POST値をvalueに渡す
			$title_val = empty($errors['edit_title']) && empty($errors['edit_body']) ? '' : $post['edit_title'];
			?>
			<input type="text" value="<?php echo $title_val ?>" name="edit_title" class="<?php echo @$errors['edit_title']['form_class']; ?>">
			<?php
			if(!empty($errors['edit_title']))
				echo '<div class="' . $errors['edit_title']['mes_class'] . '">' . $errors['edit_title']['message'] . '</div>';
			?>
		</p>
		<p>
			<em>本文：</em>
			<?php
			//バリデーションエラーがあった時だけ、POST値をvalueに渡す
			$body_val = empty($errors['edit_title']) && empty($errors['edit_body']) ? '' : $post['edit_body'];
			?>
			<textarea name="edit_body" class="<?php echo @$errors['edit_body']['form_class']; ?>"><?php echo $body_val; ?></textarea>
			<?php
			if(!empty($errors['body']))
				echo '<div class="' . $errors['edit_body']['mes_class'] . '">' . $errors['edit_body']['message'] . '</div>';
			?>
		</p>
		<p>
			<input name="edit_commit" type="submit" value="投稿">
			<input name="id" type="hidden" value="<?php echo $form_data['id']; ?>">
		</p>
	</div>
	</form>
</li>
<li class="form_reply_<?php echo $form_data['id']; ?>">
	<form action="index.php" method="post" name = "reply_form<?php echo $form_data['id']; ?>">
	<div>
		<p>
			<em>【返信】</em>
			<em>タイトル：</em>
			<?php
			//バリデーションエラーがあった時だけ、POST値をvalueに渡す
			$title_val = empty($errors['reply_title']) && empty($errors['reply_body']) ? '' : $post['reply_title'];
			?>
			<input type="text" value="<?php echo $title_val ?>" name="reply_title" class="<?php echo @$errors['reply_title']['form_class']; ?>">
			<?php
			if(!empty($errors['reply_title']))
				echo '<div class="' . $errors['reply_title']['mes_class'] . '">' . $errors['reply_title']['message'] . '</div>';
			?>
		</p>
		<p>
			<em>本文：</em>
			<?php
			//バリデーションエラーがあった時だけ、POST値をvalueに渡す
			$body_val = empty($errors['reply_title']) && empty($errors['reply_body']) ? '' : $post['reply_body'];
			?>
			<textarea name="reply_body" class="<?php echo @$errors['reply_body']['form_class']; ?>"><?php echo $body_val; ?></textarea>
			<?php
			if(!empty($errors['reply_body']))
				echo '<div class="' . $errors['reply_body']['mes_class'] . '">' . $errors['reply_body']['message'] . '</div>';
			?>
		</p>
		<p>
			<input name="reply_commit" type="submit" value="投稿">
			<input name="parent_id" type="hidden" value="<?php echo $form_data['id']; ?>">
			<input name="reply_id"  type="hidden" value="<?php echo $form_data['id']; ?>">
		</p>
	</div>
	</form>
</li>