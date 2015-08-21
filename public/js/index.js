//start
$(function () {

	//バリデーションエラーが合った場合に該当フォームを開く
	var form_class_name = $('input[name="post_form"]');
	if(form_class_name.val()){
		//フォーム固有ID,POST値、エラー値、フォームの区分名
		var id        =  form_class_name.val().split('_')[2];
		var post      = $('input[name="serialize_post"]').val();
		var errors    = $('input[name="serialize_errors"]').val();
		var form_name = form_class_name.val().split('_')[1];

		//フォームを開くAjax
		viewFormAjax({'id':id
					, 'post': post
					, 'errors':errors
					, 'form_name':form_name}
					, null
		);

		//スクロール
		moveAnchorLink(0, '#anchor_' + id);
	}


	//編集・返信の投稿ボタン押下時
	$(document).on('click', 'input[name="reply_commit"],input[name="edit_commit"]', function(){
		setOpenForm($(this))
	});
	/**
	 * setOpenForm
	 * バリデーションNG時、該当フォームを開く
	 * @param object curr_this 返信・編集の投稿ボタンを押下時のthis
	 * @returns none
	 */
	var setOpenForm = function(curr_this){
		//対象のフォーム [edit:編集 or reply:返信フォーム]のクラス名取得
		var form_class_name = curr_this.closest('li').attr('class');

		//バリデーションエラー時のフォームを開いた状態保持に使用
		//投稿成功時はリダイレクト処理がかかるので、値はブランクにリセットされる
		var post_form = $('input[name="post_form"]');
		post_form.val(form_class_name);
		curr_this.closest('p').append(post_form);
	};

	//投稿の削除ボタン押下時
	$(document).on('click', 'input[name="delete"]', function(e){
		if(confirm('本当に削除してよろしいでしょうか？') == false){
			//サブミット処理停止
			e.preventDefault();
			e.stopPropagation();
		}
	});

	//返信コメントのアンカーリンククリック時
	$(document).on('click', '[href^="#anchor_"]', function(e){
		//イベントSTOP
		e.preventDefault();
		//スクロール先取得
		var scroll_dist = $(this).attr('href');

		//スクロール実行
		moveAnchorLink(1000, scroll_dist);
		return false;
	});


	//返信・編集ボタンを押したときのフォーム
//	$(document).on('click', 'input[name="reply"], input[name="edit"]', function(){
//		//編集・返信フォームの表示・非表示（編集時はフォーム内容をセット）
//		viewForm($(this));
//	});


	/**
	 * view_form
	 * 返信・編集フォームで開いていれば全て閉じ、閉じていれば該当のフォームのみ開く
	 * @param object curr_this 全ての返信・編集ボタンを押下時のthis
	 * @returns none
	 */
	var viewForm = function(curr_this, id){
		//押したボタンのNAMEを取得[edit:編集 or reply:返信フォーム]
		var form_name = curr_this.attr('name');
		//フォーム固有ID
		var id        = curr_this.nextAll('input[name=id]').val();
		//対象のフォーム [編集 or 返信フォーム]
		var form      = curr_this.closest('li').nextAll('li[class^=form_' + form_name + '_' + id + ']');
		//全ての編集・返信フォーム
		var form_list = $('li[class^=form_]');

		//表示状態の時
		if(form.css('display') == 'list-item'){
			form_list.hide();
		}
		//非表示状態の時
		else{
			form_list.hide();
			form.show();
			//編集フォームのとき
			if(form_name == 'edit'){
				//編集フォームの内容をセットする
				setEditForm({this:curr_this,form:form,id:id});
			}
		}
	};


	/**
	 * moveAnchorLink
	 * アンカーリンク設定タグまでスクロール
	 * @param int    スクロールスピード
	 * @param string スクロール先のID [#anchor_ + id]
	 * @returns none
	 */
	function moveAnchorLink(scroll_speed_ms, scroll_dist){
		//リンク先のコメントが削除済みの場合
		if($(scroll_dist).size() == 0){
			alert('そのコメントは既に削除されています');
			return false;
		}
		//スクロール先の位置を取得
		var position    = $(scroll_dist).offset().top;

		//実行
		$('html, body').animate({scrollTop:position}, scroll_speed_ms, "swing");
	};


	//返信・編集ボタンを押した時
	$(document).on('click', 'input[name="reply"], input[name="edit"]', function(){
		//フォーム固有ID,POST値、エラー値、フォームの区分名
		var id        = $(this).nextAll('input[name=id]').val();
		var post      = $('input[name="serialize_post"]').val();
		var errors    = $('input[name="serialize_errors"]').val();
		var form_name =  $(this).attr('name');
		viewFormAjax({'id':id
					, 'post': post
					, 'errors':errors
					, 'form_name':form_name}
					,$(this)
		);
	});

	/**
	 * Ajax
	 * 返信・編集ボタンを押したときのフォームをAjaxで取得し、表示
	 * @param object data      id,post,erros,form_nameをまとめたオブジェクト
	 * @param object curr_this 編集・返信ボタンのクリックイベントオブジェクト[ボタン押下時に使用、それ以外はNULL]
	 * @returns none
	 */
	function viewFormAjax(data, curr_this){
		var id        = data['id'];
		var post      = data['post'];
		var errors    = data['errors'];
		var form_name = data['form_name'];
		//全ての編集・返信フォーム
		var form_list = $('li[class^=form_]');
		//イベントのthis
		var curr_this = curr_this;
		//追加するフォームを取得
		var form  = $('li[class^=form_' + form_name + '_' + id + ']');

		//表示状態の時
		if(curr_this && form.css('display') == 'list-item'){
			form_list.remove();
			return false;
		}

		$.ajax({

			type: 'POST',
			scriptCharset: 'utf-8',
			url: 'ajax/index/index_form.php',
			datatype: 'html',
			async: false,
			data: { 'id':data['id'], 'post': data['post'], 'errors':data['errors']}

		}).done(function( form_html ) {
			//フォームを追加
			$('#anchor_' + id).next('.thread_button').after(form_html);

			//現在出ている返信・編集フォームを削除
			if(form_list){
				form_list.remove();
			}

			//追加したフォームを取得
			var form  = $('li[class^=form_' + form_name + '_' + id + ']');

			//編集フォームのとき
			if(form_name == 'edit' && !errors){
				//編集フォームの内容をセットする
				setEditForm({'this':curr_this, 'form':form, 'id':id});
			}
			form.show();

			//一回Ajaxが実行されたらバリデーションエラーを空にする
			//他のフォームを開いたときのエラー残りを回避
			$('input[name="serialize_post"]').val('');
			$('input[name="serialize_errors"]').val('');

		}).fail(function( msg ) {

			alert("通信エラーです");

		});
	}

	/**
	 * set_edit_form
	 * 編集フォームの時、フォーム内容に編集前に設定されていたテキストをセット
	 * @param object form_obj  form情報のオブジェクト
	 *			form_obj['this']
	 *			form_obj['id']
	 *			form_obj['form']
	 * @returns none
	 */
	var setEditForm = function(form_obj){

		//編集項目のタイトルと本文取得
		var thread_ul  = form_obj['this'].closest('ul');
		var edit_title = thread_ul.find('.js-title_' + form_obj['id']).text().trim();
		var edit_body  = thread_ul.find('.js-body_' + form_obj['id']).text().trim();

		//編集フォームにテキストをセット
		form_obj['form'].find('[name=edit_title]').val(edit_title);
		form_obj['form'].find('[name=edit_body]').val(edit_body);

	};

//end
});