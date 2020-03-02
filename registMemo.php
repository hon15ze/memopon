<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　投稿ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// GETデータを格納
$m_id = (!empty($_GET['m_id'])) ? $_GET['m_id'] : '';
// DBからメモデータを取得
$dbFormData = (!empty($m_id)) ? getMemo($_SESSION['user_id'],$m_id) : '';
// 新規登録画面か編集画面か判別
$edit_flg = (empty($dbFormData)) ? false : true;

// パラメータ改ざんチェック
//================================
// GETパラメータはあるが、改ざんされている場合、正しいデータが取れないのでマイページへ遷移
if(!empty($m_id) && empty($dbFormData)){
   debug('GETパラメータのメモIDが違います。マイページへ遷移します。');
   header("Location:mypage.php"); //マイページへ
}

// POST送信時処理
//================================
if(!empty($_POST)){
   debug('POST送信があります。');
   debug('POST情報：'.print_r($_POST,true));
debug('FILE情報：'.print_r($_FILES,true));

//変数にユーザー情報を代入
$comment = $_POST['comment'];
//画像をアップロードし、パスを格納
$pic1 = ( !empty($_FILES['pic1']['name']) ) ? uploadImg($_FILES['pic1'],'pic1') : '';
// 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
$pic1 = ( empty($pic1) && !empty($dbFormData['pic1']) ) ? $dbFormData['pic1'] : $pic1;
$pic2 = ( !empty($_FILES['pic2']['name']) ) ? uploadImg($_FILES['pic2'],'pic2') : '';
$pic2 = ( empty($pic2) && !empty($dbFormData['pic2']) ) ? $dbFormData['pic2'] : $pic2;
$pic3 = ( !empty($_FILES['pic3']['name']) ) ? uploadImg($_FILES['pic3'],'pic3') : '';
$pic3 = ( empty($pic3) && !empty($dbFormData['pic3']) ) ? $dbFormData['pic3'] : $pic3;

//削除ボタンを押した場合
$delete = $_POST['delete'];

// 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
if(empty($dbFormData)){
   //最大文字数チェック
   validMaxLen($comment, 'comment', 500);
   //未入力チェック
   validRequired($comment, 'comment');
}else{
   if($dbFormData['comment'] !== $comment){
//最大文字数チェック
   validMaxLen($comment, 'comment', 500);
//未入力チェック
   validRequired($comment, 'comment');
   }
}

if(empty($err_msg)){
   debug('バリデーションOKです。');

   //例外処理
   try {
      // DBへ接続
      $dbh = dbConnect();
      // $_POST['delete']の有無
      if(empty($_POST['delete'])) {

      // SQL文作成
      // 編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
      if($edit_flg){
         debug('編集 DB更新です。');
         $sql = 'UPDATE memo SET comment = :comment, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE user_id = :u_id AND m_id = :m_id';
         $data = array(':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':m_id' => $m_id);
      }else{
         debug('新規投稿 DB新規登録です。');
         $sql = 'insert into memo ( comment, pic1, pic2, pic3, user_id, create_date ) values (:comment,  :pic1, :pic2, :pic3, :u_id, :date)';
         $data = array(':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
       }
      debug('SQL：'.$sql);
      debug('流し込みデータ：'.print_r($data,true));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功の場合
      if($stmt){
         $_SESSION['msg_success'] = SUC04;
         debug('マイページへ遷移します。');
         header("Location:mypage.php"); //マイページへ
      }
    } else {
         // SQL文作成
         $sql = 'UPDATE memo SET delete_flg = 1 WHERE user_id = :u_id AND m_id = :m_id';
         $data = array(':u_id' => $_SESSION['user_id'], ':m_id' => $m_id);
   
         // クエリ実行
         $stmt = queryPost($dbh, $sql, $data);
   
         // クエリ成功の場合
         if($stmt){
            debug('投稿を削除しました');
            $_SESSION['msg_success'] = SUC05;
            header("Location:mypage.php"); //マイページへ
         }
      }
   

   } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
   }
}
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
   $siteTitle = (!$edit_flg) ? '新規投稿': '編集';
   require('head.php'); 
?>

<body class="page-about page-2colum page-logined">

<!-- メニュー -->
<?php
   require('header.php');
?>

<!-- メインコンテンツ -->
<div id="contents">
   <div class="site-width">
   <h1 class="page-title"><i class="fas fa-edit"></i><?php echo (!$edit_flg) ? '新規投稿' : '編集する'; ?></h1>

   <!-- Main -->
   <section id="main" >
      <div class="form-container">
         <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">

            <div style="overflow:hidden;">
               <div class="imgDrop-container">
                  画像1
                  <label class="area-drop <?php if(!empty($err_msg['pic1'])) echo 'err'; ?>">
                     <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                     <input type="file" name="pic1" class="input-file">
                     <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic1'))) echo 'display:none;' ?>">
                     ドラッグ＆ドロップ
                  </label>

                  <div class="area-msg">
                     <?php
                        if(!empty($err_msg['pic1'])) echo $err_msg['pic1'];
                     ?>
                  </div>
               </div>

               <div class="imgDrop-container">
                  画像２
                  <label class="area-drop <?php if(!empty($err_msg['pic2'])) echo 'err'; ?>">
                     <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                     <input type="file" name="pic2" class="input-file">
                     <img src="<?php echo getFormData('pic2'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic2'))) echo 'display:none;' ?>">
                     ドラッグ＆ドロップ
                  </label>

                  <div class="area-msg">
                     <?php
                        if(!empty($err_msg['pic2'])) echo $err_msg['pic2'];
                     ?>
                  </div>
               </div>

               <div class="imgDrop-container">
                  画像３
                  <label class="area-drop <?php if(!empty($err_msg['pic3'])) echo 'err'; ?>">
                     <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                     <input type="file" name="pic3" class="input-file">
                     <img src="<?php echo getFormData('pic3'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic3'))) echo 'display:none;' ?>">
                     ドラッグ＆ドロップ
                  </label>

                  <div class="area-msg">
                     <?php 
                        if(!empty($err_msg['pic3'])) echo $err_msg['pic3'];
                     ?>
                  </div>
               </div>
            </div>

                  <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                     ひとこと
                     <textarea name="comment" id="js-count" cols="30" row="10" style="height:150px;"><?php echo getFormData('comment'); ?></textarea>
                  </label>

                  <p class="counter-text"><span id="js-count-view">0</span>/500文字</p>
                  <div class="area-msg">
                     <?php
                        if(!empty($err_msg['comment'])) echo $err_msg['comment'];
                     ?>
                  </div>

                  <div class="btn-container">
                     <input type="submit" class="btn btn-mid" value="<?php echo (!$edit_flg) ? '投稿する' : '更新する'; ?>">
                     <input type="submit" class="btn btn-mid" name="delete" value="削除する">
                  </div>
         </form>
      </div>
</section>

         <!-- サイドバー -->
      <?php
         require('sidebar_mypage.php');
      ?>
   </div>
</div>

<!-- footer -->
<?php
   require('footer.php');
?>