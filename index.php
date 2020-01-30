<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// GETパラメータを取得
//================================
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは１ページめ
// ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
// パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header("Location:index.php"); //トップページへ
}
// 表示件数
 $listSpan = 20;
// 現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);
// DBからメモデータを取得
$dbMemoData = getMemoList($currentMinNum, $sort);
debug('現在のページ：'.$currentPageNum);
//debug('フォーム用DBデータ：'.print_r($dbFormData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'HOME';
require('head.php');
?>

<body class="page-home page-2colum">

<!-- ヘッダー -->
<?php
    require('header.php');
?>

<!-- メインコンテンツ -->
<div id="contents">
<div class="site-width">
<!-- サイドバー -->
<section id="sidebar">
<form name="" method="get">
<h1 class="title">表示順</h1>
<div class="selectbox">
<span class="icn_select"></span>
<select name="sort">
    <option value="0" <?php if(getFormData('sort',true) == 0){ echo 'selected'; } ?> >選択してください</option>
    <option value="1" <?php if(getFormData('sort',true) == 1){ echo 'selected'; } ?> >投稿が古い順</option>
    <option value="2" <?php if(getFormData('sort',true) == 2){ echo 'selected'; } ?> >投稿が新しい順</option>
    <option value="3" <?php if(getFormData('sort',true) == 3){ echo 'selected'; } ?> >更新が古い順</option>
    <option value="4" <?php if(getFormData('sort',true) == 4){ echo 'selected'; } ?> >更新が新しい順</option>
</select>
    </div>
    <input class="btn-s" type="submit" value="並べ替え">
    </form>

</section>

<!-- Main -->
<section id="main" >
<div class="search-title">
<div class="search-left">
<span class="total-num"><?php echo sanitize($dbMemoData['total']); ?></span>件の投稿があります
</div>
<div class="search-right">
<span class="num"><?php echo $currentMinNum+1; ?></span> - <span class="num"><?php echo $currentMinNum+$listSpan; ?></span>件 / <span class="num"><?php echo sanitize($dbMemoData['total']); ?></span>件中
</div>
</div>
<div class="panel-list">
<?php
foreach($dbMemoData['data'] as $key => $val):
?>
<a href="memoDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&m_id='.$val['m_id'] : '?m_id='.$val['m_id']; ?>" class="panel">
<div class="panel-head">
<img src="<?php echo sanitize($val['pic1']); ?>" alt="">
</div>
</a>
<?php
endforeach;
?>
</div>

<?php pagination($currentPageNum, $dbMemoData['total_page'],'&sort='.$sort); ?>

</section>

</div>
</div>
<!-- footer -->
<?php
require('footer.php');
?>
