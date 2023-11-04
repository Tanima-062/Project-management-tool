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
    <link rel="stylesheet" href="<?php echo URL::asset('public/plugins/animate-css/animate.min.css'); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <style>
        .ad_content_holder{
            width:100%;
            background: #098777;
            position: relative;
            height:500px;
            overflow: hidden;
        }
        .slider {
            margin: 0px auto;
            padding: 0px 10px;
            color: white;
        }
        .slide{
            background-color: #fff;
            margin-bottom: 10px;
            padding: 10px 4px;
            border-radius: 5px;
            position: relative;
            list-style: none;
        }
        .slide a{
            text-decoration: none;
        }
        figcaption{
            color:#2d2d2d;
        }
        figcaption h4{
            font-size: 15px;
            color: #087177;
            font-weight: 700;
            display: inline-block;
            line-height: 16px;
            padding: 0;
            margin: 1px;
        }
        .best_deals_top_img{
            position:absolute;
            top:0;
            z-index: 11;
        }
        .bottom_logo{
            position:absolute;
            bottom: 0px;
            z-index: 10;
        }
        .font-line-through{
            text-decoration: line-through;
            font-size:12px;
            margin-bottom:0px;
        }
        .current_price{
            font-size:14px;
            font-weight:700;
            margin-bottom:0px;
        }
        .buy_now_img{
            height:15px!important;
        }
        .discount_box{
            width: 40px;
            height: 28px;
            background: url(<?php echo URL::asset('public/images/discount-bg.png'); ?>);
            background-repeat: no-repeat;
            background-size: cover;
            color:#fff;
            font-size:13px;
            font-weight:700;
            padding:2px;
            position:absolute;
            top:0;
            left:0;
            z-index: 10;
        }
        .discount_box span{
            display:inline-block;
            margin-left:3px;
        }
        .ratation{
            -webkit-transform: rotate(-15deg);
            -moz-transform: rotate(-15deg);
            -ms-transform: rotate(-15deg);
            -o-transform: rotate(-15deg);
        }
        .product_image{
            margin-left:5px;
        }
    </style>


</head>
<body>
<?php if(!empty($creative_details) && $creative_details->is_active == 1 && $creative_details->creative != ''): ?>
    <?php if(is_image($creative_details->creative)): ?>
        <img id="campaign_ad" src="<?php echo URL::asset('public/uploads/images/'.$creative_details->creative); ?>" alt="" style="width:250px;height:250px">
    <?php else: ?>
        <iframe id="campaign_ad" src="<?php echo URL::asset('public/uploads/'.$creative_details->creative); ?>" frameborder="0" scrolling="no" width="250" height="250"></iframe>
    <?php endif; ?>
<?php endif; ?>
<div class="container-fluid" style="padding-left:0%">
    <div class="row">
        <div class="col-xs-12">
            <div class="ad_content_holder holder">
                <a href="https://bikroy.com/en/shops/bikroy-deals?utm_source=WebEngage&utm_medium=OnSite_Notifications&utm_campaign=DealsLogo" target="_blank">
                    <img class="img-responsive best_deals_top_img" src="<?php echo URL::asset('public/images/preview_250x250/deals.png'); ?>" alt="">
                </a>
                <ul class="slider" id="ticker01">
                    <?php $__currentLoopData = $deal->contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="slide">
                            <a href="<?php echo $content->link.$deal->utm; ?>" target="_blank">
                                <figure>
                                    <?php if($content->discount != null): ?>
                                        <div class="discount_box">
                                            <span class="ratation"><?php echo $content->discount; ?>%</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row">
                                        <div class="col-xs-4" style="padding-right:0px;">
                                            <img src="<?php echo generate_custom_image_path($content->image,'110/102'); ?>" alt="" class="img-responsive product_image" />
                                        </div>
                                        <div class="col-xs-8">
                                            <figcaption>
                                                <h4><?php echo ucwords($content->title); ?></h4>
                                                <?php if($content->discounted_price !=null): ?>
                                                    <p class="font-line-through text-muted">TK <?php echo $content->discounted_price; ?></p>
                                                <?php endif; ?>
                                                <p class="current_price">
                                                    TK <?php echo $content->current_price; ?>

                                                    <?php if($content->discount != null): ?>
                                                        <span class="text-danger">( -<?php echo $content->discount; ?>%)</span>
                                                    <?php endif; ?>
                                                </p>
                                            </figcaption>
                                            <figure>
                                                <a href="<?php echo $content->link; ?>" target="_blank">
                                                    <img src="<?php echo URL::asset('public/images/buy_now_img.png'); ?>" class="img-responsive buy_now_img"/>
                                                </a>
                                            </figure>
                                        </div>
                                    </div>
                                </figure>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <a href="https://bikroy.com/" target="_blank">
                    <img class="bottom_logo" src="<?php echo URL::asset('public/images/preview_250x250/logo_1.png'); ?>" alt="">
                </a>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
<script type="text/javascript">

    window.onload = function() {
        var no_of_element_to_show = 4;
        var margin_bottom = 10;
        var speed = .5;
        var mask = $('.slider');
        mask.css('padding','0px 10px');
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
        })
    };

</script>
</body>
</html>