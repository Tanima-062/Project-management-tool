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
    <link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <style>
        .rotate_background_image{
            position: absolute;
            top: 69px;
            bottom: 0;
            width: 344%;
            left: -192px;
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
        .ad_content_holder{
            width:160px;
            height:600px;
            position: relative;
            overflow: hidden;
            display:none;
        }
        .slider_layer{
            background: #098777;
            width: 121px;
            height: 600px;
            position: absolute;
            top: 0px;
            left: 19px;
            border-left: 5px solid #007168;
            border-right: 5px solid #007168;
        }
        .slider {
            color: white;
            padding:0px;
        }
        .slide{
            background:url('<?php echo URL::to('resources/views/admin/modules/deals/prev_v2/assets/160x600/box.png'); ?>');
            background-size: cover;
            background-repeat:no-repeat;
            overflow: hidden;
            width:146px;
            height:179px;
            position:relative;
            list-style: none;
            margin-left: 6px;
        }
        .img_box img{
            margin-left: 41px;
            margin-top: 16px;
            overflow: hidden;
            display: inline-block;
        }
        .best_deals_top_img{
            position:absolute;
            top:0;
            z-index: 10;
        }
        .discount_box span{
            color: #ffffff;
            display: inline-block;
            font-weight: 700;
            margin-top: 14px;
            margin-left: 10px;
            font-size: 13px;
            line-height: 0px;
        }
        .discount_box p{
            font-size: 12px;
            color: #fff;
            margin-left: 11px;
            font-weight: 700;
            line-height: 9px;
            display: block;
            overflow: hidden;
        }
        .discount_box{
            position:absolute;
            width:100px;
        }
        .product_title{
            font-size: 14px;
            text-align: left;
            color: #098777;
            font-weight: 700;
            margin-top: 0px;
            position: absolute;
            bottom: 35px;
            left: 11px;
            padding:0px 2px
        }
        .prev_price{
            margin-left: 12px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 0;
            margin-top: 6px;
            margin-right: 6px;
            background: url(<?php echo URL::asset('prev_v1'); ?>);
            background-repeat: no-repeat;
            background-position-y: 2px;
        }
        .current_price{
            font-size: 14px;
            font-weight: 700;
            display: inline-block;
            margin-top: 3px;
            color:#000000;
        }
        .price_details{
            position: absolute;
            bottom: 8px;
            color:#000000;
        }
        .bottom_logo{
            position:absolute;
            bottom: 0px;
            z-index: 10;
        }

    </style>

    <style>

    </style>

</head>
<body>
<?php if(!empty($creative_details) && $creative_details->is_active == 1 && $creative_details->creative != ''): ?>
    <?php if(is_image($creative_details->creative)): ?>
        <img id="campaign_ad" src="<?php echo URL::asset('public/uploads/images/'.$creative_details->creative); ?>" alt="" style="width:160px;height:auto">
    <?php else: ?>
        <iframe id="campaign_ad" src="<?php echo URL::asset('public/uploads/'.$creative_details->creative); ?>" frameborder="0" scrolling="no" width="100%" height="600"></iframe>
    <?php endif; ?>
<?php endif; ?>
<div class="container-fluid" style="padding-left:0%">
    <div class="row">
        <div class="col-xs-12">
            <div class="ad_content_holder holder">
                <a href="<?php echo site_urls()['deals']; ?>" target="_blank">
                    <img  src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/160x600/deals.png'); ?>" alt="" class="pull-left best_deals_top_img">
                </a>
                <img class="rotate_background_image" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/bg-circle.png'); ?>" alt="">
                <div class="slider_layer">

                </div>
                <ul class="slider">
                    <?php $__currentLoopData = $deal->contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="slide">
                            <a href="<?php echo $content->link.$deal->utm; ?>" target="_blank">
                                <div>
                                    <figure>
                                        <?php if($content->discount != null): ?>
                                            <div class="discount_box">
                                                <span class="ratation"><?php echo $content->discount; ?>%</span>
                                                <p>off</p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="img_box">
                                            <img src="<?php echo generate_custom_image($content->image,'70/70','cropped'); ?>" alt="" class="img-responsive text-center" />
                                        </div>
                                        <figcaption>
                                            <p class="product_title"><?php echo get_custom_title(ucwords($content->title)); ?></p>
                                            <div class="row price_details">
                                                <div class="col-xs-12">
                                                    <?php if($content->discounted_price !=null): ?>
                                                        <p class="prev_price pull-left text-muted">TK <?php echo number_format($content->discounted_price); ?></p>
                                                    <?php endif; ?>
                                                    <p class="current_price pull-left">TK <?php echo number_format($content->current_price); ?></p>
                                                </div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <a href="https://bikroy.com/en/shops/bikroy-deals" target="_blank">
                    <img class="bottom_logo" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/160x600/free-delivery.png'); ?>" alt="">
                </a>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
<script type="text/javascript">
    window.onload = function() {
        var no_of_element_to_show = 4;
        var margin_bottom = 6;
        var speed = .5;
        var mask = $('.slider');
        //mask.css('padding','0px 0px');
        var children = mask.children();
        children.css({'margin-top' : '0px', 'margin-bottom' : margin_bottom+'px'});
        var temp_height = mask.height();
        var total_height_of_boxes = 0;
        var total_height_of_visible_boxes = 0;
        children.each(function(i,v){
            if(i<no_of_element_to_show){
                total_height_of_visible_boxes += $(v).height();
                $(v).clone().appendTo(mask);
            }
        });
        children.each(function(i,v){
            total_height_of_boxes += $(v).height();
        });
        var mask_height = temp_height+margin_bottom;
        var vars= {
            y:-mask_height,
            ease:Power0.easeNone,
            repeat:-1
        };
        var sec = Math.round((mask_height*10)/(1000*speed));
        var tl = TweenMax.to(mask,sec ,vars);
        mask.on({
            mouseenter:function(){
                tl.pause();
            },
            mouseleave:function(){
                tl.play();
            }
        });


    };
    $(document).ready(function(){
        var campaign_ad_time = (Number('<?php echo ($creative_details) ? $creative_details->time : 0; ?>')*1000);
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
    })
</script>
</body>
</html>