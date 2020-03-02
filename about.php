<?php

  //共通変数・関数ファイルを読込み
  require('function.php');

  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「　アバウトページ　');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugLogStart();

  debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
  $siteTitle = 'ABOUT';
  require('head.php');
  ?>

<body class="page-about page-home">

<!-- ヘッダー -->
    <?php
        require('header.php');
    ?>

<!-- メインコンテンツ -->
    <div id="contents">
    <div class="site-width">

    <!-- Main -->
    <section id="main" >
     <div>
        <h1 class="catch-copy c-c"><i class="fas fa-question-circle"></i>memoponとは<br>
        <p class="catch-copy">何気なく撮った一枚を、<br>
            ちょっとだれかに見てほしい。<br>
            そんな思いを叶える写真投稿サービスです。
        </p>
        <a class="look" href="index.php">>>投稿をみる</a>
      </div>
    </section>

     </div>
    </div>
<!-- footer -->
    <?php
    require('footer.php');
    ?>
