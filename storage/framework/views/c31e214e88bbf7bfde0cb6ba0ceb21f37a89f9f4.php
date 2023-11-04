<!DOCTYPE html>
<html lang="en">
<head>
    <title>DSD preview</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>

        #myCarousel{
            width: 300px;
            height: 250px;
            margin: <?php echo request()->has('preview=true') ? '20px auto' : '0'; ?>;
            overflow: hidden;
        }
        .carousel-inner{
            margin-top:8px;
        }
        .carousel-control.left, .carousel-control.right {
            background-image: none !important;
            filter: none !important;
            height: 41px;
            top: 106px;
        }
        .glyphicon-chevron-right{
            margin-right:-22px!important;
        }
        .glyphicon-chevron-left{
            margin-left:-22px!important;
        }
        body{
            margin:0;
            padding: 0;
            background: #fff0;
            font-family: "Roboto", sans-serif;

        }
        .glyphicon{
            background: #0000001a;
            width: 41px!important;
            height: 40px!important;
            margin-top: -10px;
            font-size: 28px;
            padding-top: 5px;
        }
        .product_image{
            width: 120px;
            height: 100px;
        }
        .custom_row{
            width: 300px;
            overflow: hidden;
            display: block;
            border: 1px solid #ddd;
            margin-bottom: 7px;
            border-radius: 4px;
            cursor: pointer;
            background: #fff;
        }
        .image_column{
            float: left;
            display: inline-block;
            width: 120px;
            position: relative;
            -webkit-box-shadow: 5px 0px 8px -2px rgba(0,0,0,0.20);
            box-shadow: 5px 0px 8px -2px rgba(0,0,0,0.20);
        }
        .content_column{
            float: left;
            display: inline-block;
            width: 178px;
            padding: 3px 10px;
            background: #fff;
        }
        .product_title{
            font-size: 14px;
            font-weight: 900;
            display: block;
            line-height: 17px;
            height: 34px;
            overflow: hidden;
            margin: 0px;
            margin-bottom: 5px;
        }
        .product_price{
            color: #009877;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 3px;
        }
        .original_price{
            color: #D95E46;
            font-size: 12px;
            text-decoration: line-through;
            margin-left: 10px;
        }
        .order_online{
            background: #FFCF00;
            color: #673500;
            font-size: 15px;
            font-weight: 900;
            padding:2px;
        }
        .discount_container{
            position: absolute;
            bottom: 0;
            right: 0;
        }
        .discount_container p{
            position: absolute;
            top: 8px;
            left: 13px;
            color: #fff;
            font-weight: 900;
            font-size: 13px;
        }
        .discount_container img{
            width: 42px;
            height: 29px;
        }
    </style>
</head>
<body>

<div class="<?php echo request()->has('preview=true') ? 'container' : ''; ?>" id="app">

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <a :href="getDsdUrl()" target="_blank">
            <img src="<?php echo asset('public/images/dsd/header.svg'); ?>" alt="" style="width: 300px;height: 30px">
        </a>
        <!-- Indicators -->
<!--        <ol class="carousel-indicators">-->
<!--            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>-->
<!--            <li data-target="#myCarousel" data-slide-to="1"></li>-->
<!--            <li data-target="#myCarousel" data-slide-to="2"></li>-->
<!--        </ol>-->

        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div v-for="(items, index) in products" :class="index === 0 ? 'item active':'item'  ">
                <div @click="redirect_to_url(row.final_url,row._id)" class="custom_row" v-for="(row,i) in items">
                    <div class="image_column">
                        <img class="product_image" :src="row.image_url_1 | resize" alt="Los Angeles" style="width:100%;">
                        <div class="discount_container" v-if="row.discount_percentage > 0">
                            <img src="<?php echo asset('public/images/dsd/discount.svg'); ?>" alt="">
                            <p v-text="row.discount_percentage+'%'"></p>
                        </div>
                    </div>
                    <div class="content_column">
                        <h4 class="product_title" v-text="row.item_title"></h4>
                        <p class="product_price">{{ getCurrencyText() }} {{ row.price.toLocaleString() }} <span class="original_price" v-if="row.discount_percentage > 0" v-text="getCurrencyText()+' '+row.original_price.toLocaleString()"></span></p>
                        <a :href="row.final_url" target="_blank" class="btn btn-block btn-sm order_online">
                            {{ cta_button_text }}
                            <img style="margin-left: 8px;width: 14px; height: 16px" src="<?php echo asset('public/images/dsd/button-arrow.svg'); ?>" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">

    var app = new Vue({
        el:'#app',
        data:{
            message:false,
            disabled:false,
            market:'<?php echo $banner->market; ?>',
            product_ids:'<?php echo $banner->product_ids; ?>',
            category:'<?php echo $banner->category; ?>',
            product_keywords:'<?php echo $banner->product_keywords; ?>',
            shop_id:'<?php echo $banner->shop_id; ?>',
            min_price:'<?php echo $banner->min_price; ?>',
            max_price:'<?php echo $banner->max_price; ?>',
            min_discount:'<?php echo $banner->min_discount; ?>',
            max_discount:'<?php echo $banner->max_discount; ?>',
            utm_code: '<?php echo $banner->utm_code; ?>',
            cta_button_text: '<?php echo $banner->cta_button_text; ?>',
            product_search_url:'https://tracking.bikroyit.com:3500/product_list',
            products:[],
            sort_by:'<?php echo $banner->sort_by; ?>',
            enable_equalise_exposure:(<?php echo json_encode($banner->enable_equalise_exposure); ?>) == 1 ? true : false,
        },
        methods:{
            getDsdUrl:function(){
                let market = '<?php echo $banner->market; ?>'
                //console.log(market)
                if(this.market === 'Bikroy' ){
                    return 'https://bikroy.com/en/ads?buy_now=1'
                }else if( market === 'Ikman' ){
                    return 'https://ikman.lk/en/ads?buy_now=1'
                }else{
                    return 'https://tonaton.com/en/ads?buy_now=1'
                }
            },
            getCurrencyText:function (){
                if(this.market === 'Bikroy'){
                    return 'Tk';
                }else if(this.market === 'Ikman'){
                    return 'Rs';
                }else{
                    return 'GH₵'
                }
            },
            product_search:function(){
                this.loading = true;
                let _this = this;
                this.products = [];
                let request_data = {
                    market:this.market,
                    category:this.category,
                    shop_id:this.shop_id !== '' ? this.shop_id.split(',') : [],
                    product_keywords: this.product_keywords,
                    min_price: this.min_price,
                    max_price: this.max_price,
                    min_discount: this.min_discount,
                    max_discount:this.max_discount,
                    sort_by:this.sort_by,
                };
                if(this.product_ids)
                    request_data.product_ids = this.product_ids.split(',');

                axios.post(this.product_search_url,request_data).then(function(res){
                    _this.loading = false
                    _this.products = res.data;
                    if(_this.enable_equalise_exposure){
                        _this.onChangeEqualiseExposure();
                    }else{
                        _this.products = _.chunk(res.data, 2);
                    }
                });
            },
            redirect_to_url:function (url,product_id) {
                axios.get('https://json.geoiplookup.io/').then(function(location){
                    talk_to_server('Click',{
                        product_id:product_id,
                        ip_address:location.data.ip,
                        region:location.data.region,
                        district:location.data.district,
                        city:location.data.city,
                        postal_code:location.data.postal_code,
                    });
                }).catch(function(err){
                    talk_to_server('Click',{
                        product_id:product_id,
                        ip_address:'',
                        region:'',
                        district:'',
                        city:'',
                        postal_code:'',
                    });
                    console.log('stacked at geolookup / '+ err);
                })
                window.open(url+this.utm_code, '_blank');
            },
            onChangeEqualiseExposure:function(){
                if(this.enable_equalise_exposure === true){
                    let group_by_shop = _.groupBy(this.products,'shop_id');
                    let sort_shop_by_max_product =_.orderBy(group_by_shop, ['group_by_shop', function (o) {
                        return o.length;
                    }], ["desc","desc"]);
                    let custom_list = [];
                    let max_product_array = _.pullAt(sort_shop_by_max_product,0);
                    max_product_array = max_product_array.length>0 ? max_product_array[0]:[];
                    _.each(max_product_array,function(item){
                        custom_list.push(item);
                        _.each(sort_shop_by_max_product,function(shop){
                            if(shop.length > 0){
                                let pulled_item = _.pullAt(shop,0);
                                custom_list.push(pulled_item[0]);
                            }
                        })
                    });
                    this.products = _.chunk(custom_list, 2);
                }

            }

        },
        filters:{
            resize:function(value){
                let split = value.split('/');
                split[split.length-3] ='120';
                split[split.length-2] ='100';
                return split.join('/');
            }
        },
        mounted:function(){
            this.product_search();
        }
    });

    $(document).ready(function(){

    })

</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.dev.js"></script>
<script type="text/javascript">
    let server_url = 'https://tracking.bikroyit.com:4000/visitor/save';
    function talk_to_server(request_type,location){
        console.log(location)
        let req = axios.get(
            server_url,
            {
                params:{
                    client_id:'Dsd_<?php echo $banner->market; ?>',
                    request_type:request_type,
                    campaign_type: '320x250',
                    campaign_id: <?php echo $banner->id; ?>,
                    product_id:location.product_id,
                    ip_address:location.ip_address,
                    device:'Desktop',
                    region:location.region,
                    district:location.district,
                    city:location.city,
                    postal_code:location.postal_code,
                }
            }
        );
        req.then(function(res){
            console.log(res.data);
        });
        req.catch(function(error){
            console.log(error);
        });
    };
    axios.get('https://json.geoiplookup.io/').then(function(location){
        talk_to_server('View',{
            ip_address:location.data.ip,
            region:location.data.region,
            district:location.data.district,
            city:location.data.city,
            postal_code:location.data.postal_code,
        });
    }).catch(function(err){
        talk_to_server('View',{
            ip_address:'',
            region:'',
            district:'',
            city:'',
            postal_code:'',
        });
        console.log('stacked at geolookup / '+ err);
    })



    function redirect(url,product_id) {

        axios.get('https://json.geoiplookup.io/').then(function(location){
            talk_to_server('Click',{
                product_id:product_id,
                ip_address:location.data.ip,
                region:location.data.region,
                district:location.data.district,
                city:location.data.city,
                postal_code:location.data.postal_code,
            });
        }).catch(function(err){
            talk_to_server('Click',{
                product_id:product_id,
                ip_address:'',
                region:'',
                district:'',
                city:'',
                postal_code:'',
            });
            console.log('stacked at geolookup / '+ err);
        })
        window.open(url, '_blank');
    }


</script>
</body>
</html>
