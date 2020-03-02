<?php
//共通変数・関数ファイル読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================
//ログイン認証
require('auth.php');

// 画面表示用データ取得
//================================
$u_id = $_SESSION['user_id'];
// DBから投稿データを取得
$memoData = getMyMemos($u_id);
// DBからお気に入りデータを取得
$favoriteData = getMyFavorite($u_id);


// debug('取得した投稿データ：'.print_r($memoData,true));
debug('取得したお気に入りデータ：'.print_r($favoriteData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'マイページ';
require('head.php');
?>

<body class="page-mypage page-2colum page-logined">

<!-- メニュー -->
    <?php
      require('header.php');
    ?>



<!-- メインコンテンツ -->
    <div id="contents">
        <p id="js-show-msg" style="display:none;" class="msg-slide">
            <?php echo getSessionFlash('msg_success'); ?>
        </p>
     <div class="site-width">
        
     <h1 class="page-title"><i class="fas fa-home"></i>MYPAGE</h1>

<!-- Main -->
    <section id="main" >
        <section class="list panel-list">
            <h2 class="title">
            投稿一覧
            </h2>
        <?php
        if(!empty($memoData)):
                foreach($memoData as $key => $val):
        ?>
        <a href="registMemo.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&m_id='.$val['m_id'] : '?m_id='.$val['m_id']; ?>" class="panel">
        
            <div class="panel-head">
                <img src="<?php echo showImg(sanitize($val['pic1'])); ?>" >
            </div>
        </a>

        <?php
        endforeach;
        endif;
        ?>
        </section>


    <section class="list panel-list">
      <h2 class="title">
        お気に入り一覧
      </h2>
<?php
if(!empty($favoriteData)):
        foreach($favoriteData as $key => $val):
?>

<a href="memoDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&m_id='.$val['m_id'] : '?m_id='.$val['m_id']; ?>" class="panel">
    <div class="panel-head">
        <img src="<?php echo showImg(sanitize($val['pic1'])); ?>">
    </div>
</a>
    </div>
<?php
endforeach;
endif;
?>

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