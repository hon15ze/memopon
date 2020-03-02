<header>
   <div>
      <nav id="top-nav">
         <ul>
         <?php
            if(empty($_SESSION['user_id'])){
         ?>
            <li><a class="users" href="signup.php">ユーザー登録</a></li>
            <li><a href="login.php">ログイン</a></li>
         <?php
            }else{
         ?>
            <li><a class="users" href="mypage.php">マイページ</a></li>
            <li><a href="logout.php">ログアウト</a></li>
         <?php
            }
         ?>
         </ul>
      </nav>
      <div class="linkbox">
         <img src="images/logo.png" class="logo"alt="logo">
         <a class="top-page" href="index.php"></a>
      </div>
   </div>
</header>