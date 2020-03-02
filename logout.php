<?php
//共通変数・関数を読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「ログアウトページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

debug('ログアウトします。');

//セッション削除
session_destroy();
debug('ログインページへ遷移します。');

//ログインページへ
header("Location:login.php");
?>