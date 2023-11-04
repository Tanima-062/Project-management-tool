<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php if((isset($banner->link)) && (validImage($banner->link) == 0)): ?>
            <iframe src="<?php echo e($banner->link); ?>" id="loadId" bannerId="<?php echo e($banner->id); ?>"></iframe>
        <?php else: ?>
            <a href="#" id="clickId" bannerId="<?php echo e($banner->id); ?>" target="_blank">
                <img src="<?php echo e($banner->link); ?>" id="loadId" bannerId="<?php echo e($banner->id); ?>">
            </a>
        <?php endif; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#clickId").click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('bannerId');
                    var request = new XMLHttpRequest();
                    request.open("GET", '/rich_media_ad_banner_system/bannerClicksCount?id='+id);
                    request.onreadystatechange = function() {
                        if(this.readyState === 4 && this.status === 200) {
                            window.open(this.responseText,"_blank");
                        }
                    };
                    request.send();
                });
                if($("#loadId").is(":visible")){
                    var id = $("#loadId").attr('bannerId');
                    var request = new XMLHttpRequest();
                    request.open("GET", '/rich_media_ad_banner_system/bannerImpressionsCount?id='+id);
                    request.onreadystatechange = function() {
                        if(this.readyState === 4 && this.status === 200) {
                        }
                    };
                    request.send();
                }
            });
        </script>
    </body>  
</html>

