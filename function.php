<?php
//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する
session_save_path("/セッション/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定
ini_set('session.gc_maxlifetime',60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime',60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える
session_regenerate_id();

//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
    debug('セッションID：'.session_id());
    debug('セッション変数の中身：'.print_r($_SESSION,true));
    debug('現在日時タイムスタンプ：'.time());
    if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
        debug( 'ログイン期限日時タイムスタンプ：'.( $_SESSION['login_date'] + $_SESSION['login_limit'] ) );
    }
}

//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'メールアドレスまたはパスワードが違います');
define('MSG12', '古いパスワードが違います');
define('MSG13', '古いパスワードと同じです');
define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '有効期限が切れています');
define('SUC01', 'パスワードを変更しました');
define('SUC02', 'プロフィールを変更しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '投稿しました');
define('SUC05', '削除しました');

//================================
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$err_msg = array();

//================================
// バリデーション関数
//================================

//バリデーション関数（未入力チェック）
function validRequired($str, $key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}
//バリデーション関数（Email形式チェック）
function validEmail($str, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
//バリデーション関数（Email重複チェック）
function validEmailDup($email){
    global $err_msg;
    //例外処理
    try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(':email' => $email);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // クエリ結果の値を取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //array_shift関数は配列の先頭を取り出す関数です。クエリ結果は配列形式で入っているので、array_shiftで1つ目だけ取り出して判定します
    if(!empty(array_shift($result))){
        $err_msg['email'] = MSG08;
    }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
//バリデーション関数（同値チェック）
function validMatch($str1, $str2, $key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
//バリデーション関数（最小文字数チェック）
function validMinLen($str, $key, $min = 6){
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
//バリデーション関数（最大文字数チェック）
function validMaxLen($str, $key, $max = 256){
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
//バリデーション関数（半角チェック）
function validHalf($str, $key){
    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
//固定長チェック
function validLength($str, $key, $len = 8){
    if( mb_strlen($str) !== $len ){
        global $err_msg;
        $err_msg[$key] = $len . MSG14;
    }
}
//パスワードチェック
function validPass($str, $key){
    //半角英数字チェック
    validHalf($str, $key);
    //最大文字数チェック
    validMaxLen($str, $key);
    //最小文字数チェック
    validMinLen($str, $key);
}
//selectboxチェック
function validSelect($str, $key){
    if(!preg_match("/^[0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG15;
    }
}
//エラーメッセージ表示
function getErrMsg($key){
    global $err_msg;
    if(!empty($err_msg[$key])){
        return $err_msg[$key];
    }
}

//================================
// ログイン認証
//================================
function isLogin(){
    // ログインしている場合
    if( !empty($_SESSION['login_date']) ){
debug('ログイン済みユーザーです。');

// 現在日時が最終ログイン日時＋有効期限を超えていた場合
if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
    debug('ログイン有効期限オーバーです。');

    // セッションを削除（ログアウトする）
    session_destroy();
    return false;
}else{
    debug('ログイン有効期限以内です。');
    return true;
}

    }else{
        debug('未ログインユーザーです。');
        return false;
    }
}

//================================
// データベース
//================================
//DB接続関数
function dbConnect(){
//DBへの接続準備
    $dsn = 'mysql:dbname=memopon;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
           // SQL実行失敗時
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
           // デフォルトフェッチモードを連想配列形式に設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
           // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
           // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    // PDOオブジェクト生成（DBへ接続）
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
    }
    //SQL実行関数
    //function queryPost($dbh, $sql, $data){
//  //クエリー作成
//  $stmt = $dbh->prepare($sql);
//  //プレースホルダに値をセットし、SQL文を実行
//  $stmt->execute($data);
//  return $stmt;
//}
    function queryPost($dbh, $sql, $data){
       //クエリー作成
    $stmt = $dbh->prepare($sql);
       //プレースホルダに値をセットし、SQL文を実行
    if(!$stmt->execute($data)){
        debug('クエリに失敗しました。');
        debug('失敗したSQL：'.print_r($stmt,true));
        $err_msg['common'] = MSG07;
        return 0;
    }
    debug('クエリ成功。');
        return $stmt;
}
    function getUser($u_id){
        debug('ユーザー情報を取得します。');
        //例外処理
        try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM users  WHERE id = :u_id AND delete_flg = 0';
        $data = array(':u_id' => $u_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

    // クエリ結果のデータを１レコード返却
        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }

        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
        }
        //  return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function getMemo($u_id, $m_id){
        debug('メモ情報を取得します。');
        debug('ユーザーID：'.$u_id);
        debug('メモID：'.$m_id);
        //例外処理
        try {
            // DBへ接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'SELECT * FROM memo WHERE user_id = :u_id AND m_id = :m_id AND delete_flg = 0';
            $data = array(':u_id' => $u_id, ':m_id' => $m_id);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);

            if($stmt){
        // クエリ結果のデータを１レコード返却
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
        return false;
    }

} catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
}
}
function getMemoList($currentMinNum = 1, $sort, $span = 20){
    debug('メモ情報を取得します。');
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // 件数用のSQL文作成
        $sql = 'SELECT m_id FROM memo WHERE delete_flg = 0';
        if(!empty($sort)){
            switch($sort){
                case 1:
                    $sql .= ' ORDER BY m_id DESC';

                break;
                case 2:
                    $sql .= ' ORDER BY m_id ASC';

                break;
            }
        }
        $data = array();
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst['total'] = $stmt->rowCount(); //総レコード数
        $rst['total_page'] = ceil($rst['total']/$span); //総ページ数
        if(!$stmt){
            return false;
        }

        //ページング用のSQL文作成
        $sql = 'SELECT * FROM memo WHERE delete_flg = 0';
        if(!empty($sort)){
            switch($sort){
                case 1:
                    $sql .= ' ORDER BY create_date ASC';
                break;
                case 2:
                    $sql .= ' ORDER BY create_date DESC';
                break;
                case 3:
                    $sql .= ' ORDER BY update_date ASC';
                break;
                case 4:
                    $sql .= ' ORDER BY update_date DESC';
                break;
            }
        }
        $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
        $data = array();
        debug('SQL：'.$sql);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            // クエリ結果のデータを全レコードを格納
            $rst['data'] = $stmt->fetchAll();
            return $rst;
        }else{
            return false;
        }

    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}
function getMemoOne($m_id){
    debug('メモ情報を取得します。');
    debug('メモID：'.$m_id);
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM memo WHERE m_id = :m_id AND delete_flg = 0';
        $data = array(':m_id' => $m_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            // クエリ結果のデータを１レコード返却
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }

    } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
}
    }

    function getMemoUser($m_id){
        debug('メモのユーザー情報を取得します。');
        debug('メモID：'.$m_id);
        //例外処理
        try {
            // DBへ接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'SELECT * FROM memo AS m JOIN users AS u ON m.user_id = u.id WHERE m.m_id = :m_id';

            $data = array(':m_id' => $m_id);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
    
            if($stmt){
                // クエリ結果のデータを１レコード返却
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
    
        } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
        }

    function getMyMemos($u_id){
        debug('自分の投稿情報を取得します。');
        debug('ユーザーID：'.$u_id);
        //例外処理
        try {
            // DBへ接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'SELECT * FROM memo WHERE user_id = :u_id AND delete_flg = 0';
            $data = array(':u_id' => $u_id);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);

            if($stmt){
                // クエリ結果のデータを全レコード返却
                return $stmt->fetchAll();
            }else{
                return false;
            }

        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
        }
    }


function isLike($u_id, $m_id){
    debug('お気に入り情報があるか確認します。');
    debug('ユーザーID：'.$u_id);
    debug('メモID：'.$m_id);
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM favorite WHERE memo_id = :m_id AND user_id = :u_id';
        $data = array(':u_id' => $u_id, ':m_id' => $m_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt->rowCount()){
            debug('お気に入りです');
            return true;
        }else{
            debug('特に気に入ってません');
            return false;
        }

    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}
function getMyFavorite($u_id){
    debug('自分のお気に入り情報を取得します。');
    debug('ユーザーID：'.$u_id);
//例外処理
try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM favorite AS f JOIN memo AS m ON f.memo_id = m.m_id WHERE f.user_id = :u_id';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
        // クエリ結果の全データを返却
        return $stmt->fetchAll();
    }else{
        return false;
    }
    
} catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
}
}
//================================
// メール送信
//================================
function sendMail($from, $to, $subject, $comment){
    if(!empty($to) && !empty($subject) && !empty($comment)){
        //文字化け防止
        mb_language("Japanese"); //現在使っている言語を設定する
        mb_internal_encoding("UTF-8");

        //メールを送信
        $result = mb_send_mail($to, $subject, $comment, "From: ".$from);
        //送信結果を判定
        if ($result) {
            debug('メールを送信しました。');
        } else {
            debug('【エラー発生】メールの送信に失敗しました。');
        }
    }
}

//================================
// その他
//================================
// サニタイズ
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
}
    // フォーム入力保持
    function getFormData($str, $flg = false){
        if($flg){
            $method = $_GET;
        }else{
            $method = $_POST;
        }
        global $dbFormData;
        // ユーザーデータがある場合
        if(!empty($dbFormData)){
            //フォームのエラーがある場合
            if(!empty($err_msg[$str])){
                //POSTにデータがある場合
                if(isset($method[$str])){
                    return sanitize($method[$str]);
                }else{
                    //ない場合
                    return sanitize($dbFormData[$str]);
                }
            }else{
                //POSTにデータがあり、DBの情報と違う場合
                if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
                return sanitize($method[$str]);
                }else{
                return sanitize($dbFormData[$str]);
                }
            }
        }else{
            if(isset($method[$str])){
                return sanitize($method[$str]);
            }
        }
    }
    //sessionを１回だけ取得
    function getSessionFlash($key){
        if(!empty($_SESSION[$key])){
            $data = $_SESSION[$key];
            $_SESSION[$key] = '';
            return $data;
        }
    }
    //認証キー生成
    function makeRandKey($length = 8) {
    static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; ++$i) {
        $str .= $chars[mt_rand(0, 61)];
    }
    return $str;
    }
    // 画像処理
    function uploadImg($file, $key){
        debug('画像アップロード処理開始');
        debug('FILE情報：'.print_r($file,true));

        if (isset($file['error']) && is_int($file['error'])) {
            try {
                // バリデーション
                //$file['error']の値を確認。
                switch ($file['error']) {
case UPLOAD_ERR_OK: // OK
break;
case UPLOAD_ERR_NO_FILE:   // ファイル未選択の場合
    throw new RuntimeException('ファイルが選択されていません');
    case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズを超過した場合
case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過した場合
    throw new RuntimeException('ファイルサイズが大きすぎます');
    default: // その他の場合
    throw new RuntimeException('その他のエラーが発生しました');
                }

// $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
// exif_imagetype関数は定数を返す
$type = @exif_imagetype($file['tmp_name']);
if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){
throw new RuntimeException('画像形式が未対応です');
            }

            // ファイルデータからSHA-1ハッシュをとってファイル名を決定し、ファイルを保存する
            // image_type_to_extension関数はファイルの拡張子を取得する
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
            if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
// 保存したファイルパスの権限を変更する
chmod($path, 0644);
    
debug('ファイルは正常にアップロードされました');
debug('ファイルパス：'.$path);
return $path;

        } catch (RuntimeException $e) {

            debug($e->getMessage());
            global $err_msg;
            $err_msg[$key] = $e->getMessage();

        }
    }
}
//ページング
// $currentPageNum : 現在のページ数
// $totalPageNum : 総ページ数
// $link : 検索用GETパラメータリンク
// $pageColNum : ページネーション表示数
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
    // 現ページが総ページ数と同じかつ総ページ数が表示項目以上なら左にリンク４
    if( $currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum - 4;
        $maxPageNum = $currentPageNum;
        // 現ページが総ページ数１P前なら左にリンク３、右に１
    }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum - 3;
        $maxPageNum = $currentPageNum + 1;
        //現ページが２の場合は左１、右３
    }elseif( $currentPageNum == 2 && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum - 1;
        $maxPageNum = $currentPageNum + 3;
        //現ページ１の場合は左なし、右５
    }elseif( $currentPageNum == 1 && $totalPageNum >= $pageColNum){
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
        // 総ページ数が表示項目数より少ない場合、総ページ数ループのMax、ループのMinを１
    }elseif($totalPageNum < $pageColNum){
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
        //それ以外左２
    }else{
        $minPageNum = $currentPageNum - 2;
        $maxPageNum = $currentPageNum + 2;
    }

        echo '<div class="pagination">';
        echo '<ul class="pagination-list">';
if($currentPageNum != 1){
echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
}
for($i = $minPageNum; $i <= $maxPageNum; $i++){
echo '<li class="list-item ';
if($currentPageNum == $i ){ echo 'active'; }
echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
}
if($currentPageNum != $maxPageNum && $maxPageNum > 1){
    echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
}
echo '</ul>';
echo '</div>';
    }
    //画像表示用関数
    function showImg($path){
        if(empty($path)){
            return 'images/sample-img.png';
        }else{
            return $path;
        }
    }
    //GETパラメータ付与
    // $del_key : 付与から取り除きたいGETパラメータのキー
    function appendGetParam($arr_del_key = array()){
        if(!empty($_GET)){
            $str = '?';
            foreach($_GET as $key => $val){
                if(!in_array($key,$arr_del_key,true)){ //取り除くパラメータではない時urlにくっつけるパラメータ
                $str .= $key.'='.$val.'&';
                }
            }
            $str = mb_substr($str, 0, -1, "UTF-8");
            return $str;
        }
    }