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
            width:970px;
            position: relative;
            height:90px;
            overflow: hidden;
            display:none;
        }
        .rotate_background_image{
            position: absolute;
            top: -411px;
            bottom: 0;
            width: 907px;
            left: 0px;
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
            height:88px;
            position:relative;
            margin-top:0px;
        }
        .slider_layer{
            background: #098777;
            width: 100%;
            height: 72px;
            position: absolute;
            top: 10px;
            left: 0;
            border-bottom: 5px solid #007168;
        }
        .slider {
            color: white;
        }
        .slide{
            background: url(<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x90/product-box.png'); ?>);
            background-size: cover;
            background-repeat: no-repeat;
            overflow: hidden;
            width: 338px;
            height: 82px;
            margin-right: 5px;
            padding: 6px;
            margin-top: 4px;
        }
        .discount_box span{
            color: #ffffff;
            display: inline-block;
            font-weight: 700;
            margin-top: 12px;
            margin-left: 7px;
            font-size: 17px;
            line-height: 0px;

        }
        .discount_box p{
            font-size: 13px;
            color: #fff;
            margin-left: 11px;
            font-weight: 700;
            line-height: 12px;
        }
        .discount_box{
            position: absolute;
            width: 55px;
            height: 38px;
            background: url(<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x90/red.png'); ?>);
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            top: 6px;
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
            left: 126px;
            top: 16px;
        }
        .prev_price{
            margin-left: 23px;
            font-size: 16px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: -5px;
            margin-right: 8px;
            background: url(<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x90/cut.png'); ?>);
            background-repeat: no-repeat;
            background-position-y: 6px;
            overflow: hidden;
        }
        .current_price{
            font-size: 20px;
            font-weight: 700;
            display: inline-block;
            color: #000000;
            margin-bottom: 19px;
        }
        .price_details{
            position: absolute;
            bottom: -7px;
            color: #000000;
            margin-left: 103px;
        }
        .deals_left_logo{
            position:absolute;
            top:0;
            left:0;
            z-index: 100;
        }

    </style>
</head>
<body>
<?php if(!empty($creative_details) && $creative_details->is_active == 1 && $creative_details->creative != ''): ?>
    <?php if(is_image($creative_details->creative)): ?>
        <img id="campaign_ad" src="<?php echo URL::asset('public/uploads/images/'.$creative_details->creative); ?>" alt="" style="width:970px;height:90px">
    <?php else: ?>
        <iframe id="campaign_ad" src="<?php echo URL::asset('public/uploads/'.$creative_details->creative); ?>" frameborder="0" scrolling="no" width="100%" height="90"></iframe>
    <?php endif; ?>
<?php endif; ?>
<div class="container-fluid" style="padding-left:0%">
    <div class="row">
        <div class="col-xs-12">



            <div class="ad_content_holder">
                <img class="rotate_background_image" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x90/bg-circle.png'); ?>" alt="">
                <a href="<?php echo site_urls()['deals']; ?>" target="_blank">
                    <img class="free_delivery_img" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x90/logo.png'); ?>" alt="">
                </a>
                <a href="<?php echo site_urls()['web']; ?>" target="_blank">
                    <img class="deals_left_logo" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x90/deals.png'); ?>"
                         alt="Deals logo">
                </a>
                <div class="row">

                    <div class="col-xs-12 slider_holder">
                        <div class="slider_layer">

                        </div>
                        <div class="slider default-ticker">
                            <?php $__currentLoopData = $deal->contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                    <img src="<?php echo generate_custom_image($content->image,'69/71','cropped'); ?>" alt="" class="img-responsive text-center" />
                                                </div>
                                                <figcaption>
                                                    <p class="product_title"><?php echo get_custom_title(ucwords($content->title)); ?></p>
                                                    <div class="row price_details">
                                                        <div class="col-xs-12">
                                                            <?php if($content->discounted_price !=null): ?>
                                                                <p class="prev_price">TK <?php echo number_format($content->discounted_price); ?></p>
                                                            <?php endif; ?>
                                                            <p class="current_price">TK <?php echo number_format($content->current_price); ?></p>
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