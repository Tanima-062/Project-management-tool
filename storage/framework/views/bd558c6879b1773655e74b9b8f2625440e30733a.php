<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $deal->title; ?></title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo URL::to('public/favicon.ico'); ?>" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo URL::asset('public/css/ticker.min.css'); ?>">
    <script type="text/javascript" src="<?php echo URL::asset('public/js/ticker.min.js'); ?>"></script>

    <style>
        .ad_content_holder{
            width:320px;
            position: relative;
            height:100px;
            overflow: hidden;
            display:none;
        }
        .rotate_background_image{
            position: absolute;
            top: -115px;
            width: 1084px;
            left: -386px;
            -webkit-animation:spin 60s linear infinite;
            -moz-animation:spin 60s linear infinite;
            animation:spin 60s linear infinite;
        }
        @-moz-keyframes spin {
            100% {
                -moz-transform: rotate(360deg);
            }
        }
        @-webkit-keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }
        @keyframes  spin {
            100% {
                -webkit-transform: rotate(360deg);
                transform:rotate(360deg);
            }
        }

        .free_delivery_img{
            position: absolute;
            bottom: 0;
            right: 0;
            z-index: 9999;
        }
        .slider_holder{
            height: 92px;
            position: relative;
            margin-top: 3px;
        }
        .slider_layer{
            background: #098777;
            width: 100%;
            height: 75px;
            position: absolute;
            top: 10px;
            left: 0;
            border-bottom: 5px solid #007168;
        }
        .slider {
            color: white;
        }
        .slide{
            background: url(<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/320x100/product-box.png'); ?>);
            background-size: cover;
            background-repeat: no-repeat;
            overflow: hidden;
            width: 313px;
            height: 92px;
            margin-right: 5px;
            padding: 6px;
            margin-top: 1px;
        }
        .discount_box span{
            color: #ffffff;
            display: inline-block;
            font-weight: 700;
            margin-top: 12px;
            margin-left: 8px;
            font-size: 15px;
            line-height: 0px;

        }
        .discount_box p{
            font-size: 12px;
            color: #fff;
            margin-left: 10px;
            font-weight: 700;
            line-height: 9px;
        }
        .discount_box{
            position: absolute;
            width: 55px;
            height: 38px;
            background: url(<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/320x100/red.png'); ?>);
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            top: 2px;
            left: 2px;
        }
        .img_box img{
            margin-left: 42px;
            margin-top: 1px;
        }
        .product_title{
            font-size: 14px;
            text-align: right;
            color: #098777;
            font-weight: 700;
            position: absolute;
            left: 120px;
            top: 23px;
        }
        .prev_price{
            margin-left: 24px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 25px;
            margin-right: 18px;
            background: url(<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/320x100/cut.png'); ?>);
            background-repeat: no-repeat;
            background-position-y: 6px;
            overflow: hidden;
        }
        .current_price{
            font-size: 16px;
            font-weight: 700;
            display: inline-block;
            color: #000000;
            margin-bottom: 22px;
            overflow: hidden;
        }
        .price_details{
            position: absolute;
            bottom: -7px;
            color: #000000;
            margin-left: 103px;
        }
        .custom_slide_first{
            background: none;
            width: 96px;
            height: 61px;
            margin-top: 18px;
            padding: 0px;
            margin-left: 7px;
            margin-right: 10px;
        }
        .custom_slide_second{
            background: none;
            width: 57px;
            height: 42px;
            margin-top: 26px;
            padding: 0px;
            margin-left: 16px;
            margin-right: 19px;
        }
    </style>
</head>
<body>
<?php if(!empty($creative_details) && $creative_details->is_active == 1 && $creative_details->creative != ''): ?>
    <?php if(is_image($creative_details->creative)): ?>
        <img id="campaign_ad" src="<?php echo URL::asset('public/uploads/images/'.$creative_details->creative); ?>" alt="" style="width:320px;height:100px">
    <?php else: ?>
        <iframe id="campaign_ad" src="<?php echo URL::asset('public/uploads/'.$creative_details->creative); ?>" frameborder="0" scrolling="no" width="100%" height="90"></iframe>
    <?php endif; ?>
<?php endif; ?>
<div class="container-fluid" style="padding-left:0%">
    <div class="row">
        <div class="col-xs-12">
            <div class="ad_content_holder">
                <img class="rotate_background_image" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/320x100/bg.png'); ?>" alt="">
                <div class="row">

                    <div class="col-xs-12 slider_holder">
                        <div class="slider_layer">

                        </div>
                        <div class="slider default-ticker">
                        <?php $__currentLoopData = $deal->contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($i==1): ?>
                                    <a href="<?php echo $content->link; ?>" target="_blank">
                                        <div class="slide custom_slide_first">
                                            <img src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/320x100/deals.png'); ?>" alt="">
                                        </div>
                                    </a>
                                <?php endif; ?>
                                    <?php if($i==2): ?>
                                        <a href="<?php echo $content->link; ?>" target="_blank">
                                            <div class="slide custom_slide_second">
                                                <img src="<?php echo URL::asset('prev_v1'); ?>" alt="">
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                <a href="<?php echo $content->link.$deal->utm; ?>" target="_blank">
                                    <div class="slide">
                                        <?php if($content->discount != null): ?>
                                            <div class="discount_box">
                                                <span class=""><?php echo $content->discount; ?>%</span><br>
                                                <p>off</p>
                                            </div>
                                        <?php endif; ?>
                                        <figure>
                                            <div class="img_box">
                                                <img src="<?php echo generate_custom_image($content->image,'70/72','cropped'); ?>" alt="" class="img-responsive text-center" />
                                            </div>
                                            <figcaption>
                                                <p class="product_title"><?php echo get_custom_title(ucwords($content->title)); ?></p>
                                                <div class="row price_details">
                                                    <div class="col-xs-12">
                                                        <?php if($content->discounted_price !=null): ?>
                                                            <p class="prev_price">Tk <?php echo number_format($content->discounted_price); ?></p>
                                                        <?php endif; ?>
                                                        <p class="current_price">Tk <?php echo number_format($content->current_price); ?></p>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var campaign_ad_time = (Number('<?php echo ($creative_details) ? $creative_details->time : 0; ?>')*1000);
    $(document).ready(function(){
        var campaign_ad = $('#campaign_ad');
        if(campaign_ad.length){
            setTimeout(
                function()
                {
                    campaign_ad.fadeOut(1000,function(){
                        $('.ad_content_holder').show();
                    });
                }, campaign_ad_time
            );
        }else{
            $('.ad_content_holder').show();
        }


        $(".default-ticker").ticker({
            item: 'a',
            pauseOnHover: true,
            speed: 50,
            pauseAt: '',
            delay: 500,
        });
    })
</script>
</body>
</html>