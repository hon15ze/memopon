<footer id="footer">
        <p>Copyright <a href="about.php">memopon</a>. All Rights Reserved.</p>
    </footer>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script>
    $(function(){
        var $ftr = $('#footer');
        if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
            $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
        }
        // メッセージ表示
        var $jsShowMsg = $('#js-show-msg');
        var msg = $jsShowMsg.text();
        if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
        $jsShowMsg.slideToggle('slow');
        setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
        }
        
        // 画像ライブプレビュー
        var $dropArea = $('.area-drop');
        var $fileInput = $('.input-file');
        $dropArea.on('dragover', function(e){
            e.stopPropagation();
            e.preventDefault();
            $(this).css('border', '3px #ccc dashed');
        });
        $dropArea.on('dragleave', function(e){
            e.stopPropagation();
            e.preventDefault();
            $(this).css('border', 'none');
        });
        $fileInput.on('change', function(e){
            $dropArea.css('border', 'none');
            var file = this.files[0], //files配列にファイルが入る
            $img = $(this).siblings('.prev-img'), //兄弟のimgを取得
            fileReader = new FileReader(); //ファイルを読み込むFileReaderオブジェクト

            //imgのsrcにデータをセット
            fileReader.onload = function(event) {
// 読み込んだデータをimgに設定
$img.attr('src', event.target.result).show();
            };

            //画像読み込み
            fileReader.readAsDataURL(file);

        });

        // テキストエリアカウント
        var $countUp = $('#js-count'),
        $countView = $('#js-count-view');
        $countUp.on('keyup', function(e){
            $countView.html($(this).val().length);
        });

        //画像切替
        var $switchImgSubs = $('.js-switch-img-sub'),
        $switchImgMain = $('#js-switch-img-main');
        $switchImgSubs.on('click',function(e){
            $switchImgMain.attr('src',$(this).attr('src'));
        });

        // お気に入り登録・削除
        var $favorite,
        favoriteMemoId;
        $favorite = $('.js-click-like')|| null;
        favoriteMemoId = $favorite.data('memoid') || null;
        // 
        if(favoriteMemoId !== undefined && favoriteMemoId !== null){
            $favorite.on('click',function(){
                var $this = $(this);
                $.ajax({
                    type: "POST",
                    url: "ajaxFavo.php",
                    data: { memoId : favoriteMemoId}
                }).done(function( data ){
                    console.log('Ajax Success');
                    // クラス属性をtoggleでつけ外しする
                    $this.toggleClass('active');
                }).fail(function( msg ) {
                    console.log('Ajax Error');
                });
            });
        }

        
    });
</script>
</body>
</html>