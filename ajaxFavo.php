<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// Ajax処理
//================================

// postがあり、ユーザーIDがあり、ログインしている場合
if(isset($_POST['memoId']) && isset($_SESSION['user_id']) && isLogin()){
    debug('POST送信があります。');
    $m_id = $_POST['memoId'];
    debug('メモID：'.$m_id);

    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // レコードがあるか検索
        $sql = 'SELECT * FROM favorite WHERE memo_id = :m_id AND user_id = :u_id';
        $data = array(':u_id' => $_SESSION['user_id'], ':m_id' => $m_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $resultCount = $stmt->rowCount();
        debug($resultCount);
        // レコードが１件でもある場合
        if(!empty($resultCount)){
            // レコードを削除する
            $sql = 'DELETE FROM favorite WHERE memo_id = :m_id AND user_id = :u_id';
            $data = array(':u_id' => $_SESSION['user_id'], ':m_id' => $m_id);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
        }else{
            // レコードを挿入する
            $sql = 'INSERT INTO favorite (memo_id, user_id, create_date) VALUES (:m_id, :u_id, :date)';
            $data = array(':u_id' => $_SESSION['user_id'], ':m_id' => $m_id, ':date' => date('Y-m-d H:i:s'));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
        }

    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}

debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>