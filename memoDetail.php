<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　投稿詳細ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// メモIDのGETパラメータを取得
$m_id = (!empty($_GET['m_id'])) ? $_GET['m_id'] : '';
// DBからメモデータを取得
$viewData = getMemoOne($m_id);
$viewUserData = getMemoUser($m_id);
// パラメータに不正な値が入っているかチェック
if(empty($viewData)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header("Location:index.php"); //トップページへ
}
debug('取得したDBデータ：'.print_r($viewData,true));

// post送信されていた場合
if(!empty($_POST['submit'])){
    debug('POST送信があります。');

    //ログイン認証
require('auth.php');



}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = '投稿詳細';
require('head.php');
?>

<body class="page-memoDetail page-1colum">

<!-- ヘッダー -->
<?php
require('header.php');
?>

<!-- メインコンテンツ -->
<div id="contents">
  <div class="site-width">
  <!-- Main -->
   <section id="main" >

    <div class="title">
        <i class="fa fa-heart icn-like js-click-like <?php if(isLike($_SESSION['user_id'], $viewData['m_id'])){ echo 'active'; } ?>" aria-hidden="true" data-memoid="<?php echo sanitize($viewData['m_id']); ?>" ></i>
    </div>

    <div class="memo-img-container">
        <div class="img-main">
            <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="メイン画像" id="js-switch-img-main">
        </div>

        <div class="img-sub">
            <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="画像1" class="js-switch-img-sub">
            <img src="<?php echo showImg(sanitize($viewData['pic2'])); ?>" alt="画像2" class="js-switch-img-sub">
            <img src="<?php echo showImg(sanitize($viewData['pic3'])); ?>" alt="画像3" class="js-switch-img-sub">
        </div>
    </div>

    <div class="m-d">
        <div class="prof-img">
          <img src="<?php echo showImg(sanitize($viewUserData['pic'])); ?>" alt="プロフ画像" class="prof-i">
          <p><?php echo sanitize($viewUserData['username']); ?></p>
        </div>

        <div class="memo-detail">
          <p><?php echo sanitize($viewData['comment']); ?></p>
        </div>
    </div>

    <div class="memo-favo">
        <div class="item-left">
        <a href="index.php<?php echo appendGetParam(array('m_id')); ?>">&lt; 投稿一覧に戻る</a>
        </div>
    </div>

   </section>

  </div>
</div>

<!-- footer -->
<?php
require('footer.php');
?>
