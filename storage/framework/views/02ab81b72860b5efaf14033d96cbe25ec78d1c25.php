
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/plugins/CSSPlugin.min.js"></script>

    <style>
        .ad_content_holder{
            width:970px;
            position: relative;
            height:250px;
            overflow: hidden;
            display:none;
        }
        .rotate_background_image{
            position:absolute;
            top:-648px;
            bottom:0;
            width:159%;
            left:-29%;
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
            height:218px;
            position:relative;
            margin-top:15px;
        }
        .slider_layer{
            background: #098777;
            width: 100%;
            height: 181px;
            position: absolute;
            top: 20px;
            left: 0;
            border-bottom: 5px solid #007168;
        }
        .slider {
            color: white;
        }
        .slide{
            background:url('<?php echo URL::to('resources/views/admin/modules/deals/prev_v2/assets/970x250/product-box.png'); ?>');
            background-size: cover;
            background-repeat:no-repeat;
            overflow: hidden;
            width:265px;
            height:218px;
            margin-right:5px;
            padding:6px;
        }
        .discount_box span{
            color: #ffffff;
            display: inline-block;
            font-weight: 700;
            margin-top: 18px;
            margin-left: 10px;
            font-size: 17px;
            line-height: 0px;
        }
        .discount_box p{
            font-size: 15px;
            color: #fff;
            margin-left: 11px;
            font-weight: 700;
            line-height: 15px;
        }
        .discount_box{
            position:absolute;
            width:100px;
        }
        .img_box img{
            margin-left: 60px;
            margin-top: 11px;
        }
        .product_title{
            font-size: 16px;
            text-align: center;
            color: #098777;
            font-weight: 700;
            margin-top: 0px;
            position: absolute;
            bottom: 50px;
            left: 26px;
        }
        .prev_price{
            margin-left: 23px;
            font-size: 15px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: -4px;
            margin-right: 8px;
            background: url(<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x250/cut.png'); ?>);
            background-repeat: no-repeat;
            background-position-y: 5px;
            overflow: hidden;
        }
        .current_price{
            font-size: 20px;
            font-weight: 700;
            display: inline-block;
            color: #000000;
            margin-bottom: 16px;
        }
        .price_details{
            position: absolute;
            bottom: 8px;
            color:#000000;
        }
        .deals_left_logo{
            position:absolute;
            top:0;
            left:0;
            z-index: 100;
        }
        .holds-the-iframe {
            background:url(https://eggmediacdnsg.azureedge.net/_mpimage/pet-care?src=https%3A%2F%2Feggyolk.chaldal.com%2Fapi%2FPicture%2FRaw%3FpictureId%3D23022&q=low&v=1&targetSize=700&q=low) center center no-repeat;
        }

    </style>
</head>
<body>
<?php if(!empty($creative_details) && $creative_details->is_active == 1 && $creative_details->creative != ''): ?>
    <?php if(is_image($creative_details->creative)): ?>
        <img id="campaign_ad" src="<?php echo URL::asset('public/uploads/images/'.$creative_details->creative); ?>" alt="" style="width:970px;height:250px">
    <?php else: ?>
        <div class="holds-the-iframe">
            <iframe id="campaign_ad" src="<?php echo URL::asset('public/uploads/'.$creative_details->creative); ?>" frameborder="0" scrolling="no" width="100%" height="250"></iframe>
        </div>
    <?php endif; ?>
<?php endif; ?>
<div class="container-fluid" style="padding-left:0%">
    <div class="row">
        <div class="col-xs-12">
            <div class="ad_content_holder">
                <img class="rotate_background_image" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x250/bg-circle.png'); ?>" alt="">
                <img class="free_delivery_img" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x250/free-delivery.png'); ?>" alt="">
                <a href="<?php echo site_urls()['deals']; ?>" target="_blank">
                    <img class="deals_left_logo" src="<?php echo URL::asset('resources/views/admin/modules/deals/prev_v2/assets/970x250/deals.png'); ?>"
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
                                            <figure>
                                                <div class="img_box">
                                                    <img src="<?php echo generate_custom_image_path($content->image,'140/105'); ?>" alt="" class="img-responsive text-center" />
                                                </div>
                                                <figcaption>
                                                    <p class="product_title"><?php echo get_custom_title(ucwords($content->title)); ?></p>
                                                    <div class="row price_details">
                                                        <div class="col-xs-12">
                                                            <?php if($content->discounted_price !=null): ?>
                                                            <p class="prev_price">TK <?php echo number_format($content->discounted_price); ?></p>
                                                            <?php endif; ?>
                                                            <p class="current_price">TK <?php echo number_format($content->current_price ); ?></p>
                                                        </div>
                                                    </div>
                                                </figcaption>
                                            </figure>
                                        <?php endif; ?>
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
    function touchEvent(event){
        var x = event.touches[0].clientX;
        var y = event.touches[0].clientY;
        alert(x)
    }

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