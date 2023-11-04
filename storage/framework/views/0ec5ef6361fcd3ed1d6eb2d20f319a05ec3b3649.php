<!DOCTYPE html>
<html lang="en">
<head>
    <title>DSD preview</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <style>
        #myCarousel{
            width: 970px;
            height: 90px;
            margin: 0;
            overflow: hidden;
            position: relative;
            background: #e7edee00;
        }

        .carousel-control.left, .carousel-control.right {
            background-image: none !important;
            filter: none !important;
            height: 41px;
            top:14px;
        }
        .glyphicon-chevron-right{
            margin-right:107px!important;
        }
        .glyphicon-chevron-left{
            margin-left:-72px!important;
        }

        body{
            margin:0;
            padding: 0;
            font-family: "Roboto", sans-serif;
        }

        .dsd_logo{
            position: absolute;
            top: 0;
            right: 0;
            z-index: 1;
        }
        .product_image{
            width: 120px;
            height: 90px;
        }
        .product_box{
            width: 300px;
            overflow: hidden;
            display: inline-block;
            cursor: pointer;
            height: 90px;
            margin-right: 10px;
            background: #fff;
            border-radius: 5px;
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
            width: 180px;
            height: 90px;
            overflow: hidden;
            padding: 0 0 0 11px;
        }
        .product_title{
            font-size: 14px;
            font-weight: 900;
            display: block;
            line-height: 14px;
            height: 14px;
            overflow: hidden;
            margin-bottom: 3px;
        }
        .product_price{
            color: #009877;
            font-weight: 700;
            font-size: 16px;
            margin: 3px 0 2px 0;
        }
        .original_price{
            color: #D95E46;
            font-size: 12px;
            text-decoration: line-through;
            margin-left: 10px;
        }
        .order_online{
            background: #FFCF00;
            color: #673500!important;
            font-size: 15px;
            font-weight: 900;
            padding: 2px 15px 2px 21px;
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
        .arrow_image{
            margin-left: 3px;
            width: 14px;
            height: 16px;
            display: inline-block!important;
        }
    </style>
</head>
<body>

<div class="" id="app">

    <div id="myCarousel" class="carousel slide">
        <a :href="getDsdUrl()" target="_blank">
            <img src="<?php echo asset('public/images/dsd/dsd-970x90.png'); ?>" alt="" class="dsd_logo">
        </a>
        <vue-slick-carousel ref="slick" v-if="products.length > 0"  :autoplay="true" v-bind="slickOptions" class="carousel-inner">
            <div v-for="(row, index) in products" :class="index === 0 ? 'item active':'item'  ">
                <div @click="redirect_to_url(row.final_url,row._id)" class="product_box">
                    <div class="image_column">
                        <img class="product_image" :src="row.image_url_1 | resize" alt="Product image">
                        <div class="discount_container" v-if="row.discount_percentage > 0">
                            <img src="<?php echo asset('public/images/dsd/discount.svg'); ?>" alt="">
                            <p v-text="row.discount_percentage+'%'"></p>
                        </div>
                    </div>
                    <div class="content_column">
                        <h4 class="product_title" v-text="row.item_title"></h4>
                        <p class="product_price">{{ getCurrencyText() }} {{ row.price.toLocaleString() }} <span class="original_price" v-if="row.discount_percentage > 0" v-text="getCurrencyText()+' '+row.original_price.toLocaleString()"></span></p>
                        <a :href="row.final_url" target="_blank" class="btn btn-sm order_online">
                            {{ cta_button_text }}
                            <img class="arrow_image" src="<?php echo asset('public/images/dsd/button-arrow.svg'); ?>" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </vue-slick-carousel>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#" @click.prevent="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#" @click.prevent="next">
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.dev.js"></script>
<script src="https://unpkg.com/vue-slick-carousel"></script>
<script type="text/javascript">

    var app = new Vue({
        el:'#app',
        components: {
            VueSlickCarousel : window['vue-slick-carousel']
        },
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
            sort_by:'<?php echo $banner->sort_by; ?>',
            enable_equalise_exposure:(<?php echo json_encode($banner->enable_equalise_exposure); ?>) == 1 ? true : false,
            products:[],
            slickOptions: {
                "arrows":false,
                "infinite": true,
                "slidesToShow": 3,
                "slidesToScroll": 1,
                "autoplay": true,
                "autoplaySpeed": 3000,
                "pauseOnDotsHover": true,
                "pauseOnFocus": true,
                "pauseOnHover": true
            }
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
            next() {
                this.$refs.slick.next();
            },

            prev() {
                this.$refs.slick.prev();
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
                    }
                });
            },
            redirect_to_url:function (url,product_id) {
                console.log(product_id);
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
                    this.products = custom_list;
                }

            },


        },
        filters:{
            resize:function(value){
                let split = value.split('/');
                split[split.length-3] ='120';
                split[split.length-2] ='90';
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
                    campaign_type: '970x90',
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
