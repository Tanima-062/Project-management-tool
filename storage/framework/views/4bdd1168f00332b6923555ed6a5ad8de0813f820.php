
<?php $__env->startSection('custom_page_style'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.4.3/dist/style.css">
    <style>
        .dropdown-menu > li > a.opt{
            padding:1px 18px!important;
        }
        .dropdown-header > span.text{
            color:#009877;
            font-weight: 700;
        }
        .panel-title{
            font-size: 12px;
        }

        .autoCompleter .bootstrap-autocomplete{
            top:70px!important;
        }
        .discount{
            text-decoration: line-through;
            font-size: 15px;
            font-weight: 700;
            display: inline-block;
        }
        .shop_name{
            color: #666;
            background: #ddde;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 300;
            line-height: 11px;
            display: inline-block;
        }
        .discount_percentage{
            display: inline-block;
            border-radius: 100px;
            padding: 3px 5px;
            line-height: 9px;
            font-size: 9px;
            background: #D95E46;
            color: #fff;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="block-header">
            <a href="<?php echo URL::to('module/dsd'); ?>" class="btn btn-sm btn-primary"> <i class="material-icons">list</i> ALL DSD Banner</a>
        </div>
        <!-- Color Pickers -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Create new DSD banner
                            <small>All feeds will be creating through API call</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else here</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body" id="app">
                        <div v-if="message" class="alert bg-teal alert-dismissible m-t-20 animated fadeInDownBig" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                           Success
                        </div>
                        <div class="row clearfix">
                            <?php echo Form::open(['url'=>'#','class'=>'form']); ?>

                            <div class="col-xs-4">

                                <label for="">Campaign title</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">title</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" name="title" v-model="title" class="form-control date" placeholder="Title.." autofocus>
                                    </div>
                                </div>

                                <label for="">Market</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">house_siding</i>
                                    </span>
                                    <div class="">
                                        <?php echo Form::select('market',['Bikroy'=>'Bikroy', 'Ikman'=>'Ikman', 'Tonaton'=>'Tonaton'],null,['class'=>'','v-model'=>'market','@change'=>'change_market','data-width'=>'100%']); ?>

                                    </div>
                                </div>
                                <label for="">Product ID's <small style="font-width: 300; font-size: 10px" class="text-muted">(,) seperated for multiple id's</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="material-icons">text_rotation_none</i></span>
                                    <div class="form-line">
                                        <textarea @change="product_search" v-model="product_ids" placeholder="Pest your ids here..." name="product_ids" id="" class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                                <label for="">UTM code <small style="font-width: 300; font-size: 10px" class="text-muted">Start with question mark (?)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="material-icons">link</i></span>
                                    <div class="form-line">
                                        <?php echo Form::text('utm_code',null,['class'=>'form-control','placeholder'=>'UTM code...','v-model'=>'utm_code']); ?>

                                    </div>
                                </div>
                                <label for="">CTA Button Text</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="material-icons">text_rotation_none</i></span>
                                    <div class="form-line">
                                        <?php echo Form::text('cta_button_text',null,['class'=>'form-control','placeholder'=>'UTM code...','v-model'=>'cta_button_text']); ?>

                                    </div>
                                </div>
                                <label for="">Sort by</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="material-icons">sort</i></span>
                                    <div class="">
                                        <?php echo Form::select('short_by',['Date'=>'Latest Uploaded', 'Ibs'=>'Top Ibs',],null,['class'=>'','v-model'=>'sort_by','@change'=>'product_search','data-width'=>'100%']); ?>

                                    </div>
                                </div>
                                <div class="input-group">
                                    <input @change="onChangeEqualiseExposure" v-model="enable_equalise_exposure" type="checkbox" id="basic_checkbox_2" class="filled-in" checked />
                                    <label for="basic_checkbox_2">Equalise Exposure </label> &nbsp;
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success btn-md btn-block"  @click.prevent="submit()" :disabled="disabled">SAVE </button>
                                    <img v-if="form_submit_animation" src="<?php echo URL::asset('public/images/loading_icon.gif'); ?>" alt="Loading icon" style="height:50px;">
                                </div>
                                <label for="">
                                    <small>Changes won't reflect until  you save it </small>
                                </label>
                                <div class="form-group" style="margin-bottom: 2px;padding:10px;background:#ddd;">
                                    <table class="table" style="margin-bottom: 0;">
                                        <thead>
                                        <tr>
                                            <td style="padding-bottom: 0">
                                                <?php echo Form::select('size_for_preview',['300x250'=>'300x250','970x90'=>'970x90','160x600'=>'160x600'],null,['class'=>'selectpicker', 'data-width'=>'100%','v-model'=>'size_for_preview','@change'=>'change_preview']); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="btn-group btn-group-justified" role="group">
                                                    <a @click.prevent="load_preview" class="btn btn-warning btn-sm waves-effect" data-toggle="modal" data-target="#defaultModal">Preview Banner</a>
                                                    <a @click.prevent data-id="<?php echo $banner->id; ?>" class="btn bg-teal btn-sm waves-effect generate_code" >Code</a>
                                                </div>
                                                <a @click.prevent="create_html_template" style="margin-top: 8px; display:block" href="#">Create email template</a>
                                            </td>
                                        </tr>
                                        </thead>
                                    </table>
                                    <div class="row">
                                        <div class="col-xs-5" style="margin-bottom: 0">
                                        </div>
                                        <div class="col-xs-7"  style="margin-bottom: 0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <div class="col-xs-8">
                               <table class="table table-condensed table-bordered text-center" id="table">
                                   <thead>
                                   <tr>
                                       <td>Image</td>
                                       <td>Content</td>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr v-if="loading">
                                       <td colspan="2">
                                           <img  style="height:50px"  src="<?php echo URL::to('public/images/loading_icon.gif'); ?>" alt="">
                                       </td>
                                   </tr>
                                   <tr v-for="(row, index) in products" class="table table-bordered table-condensed">
                                       <td>
                                           <img width="70" v-if="row.image_url_1 != ''" :src="row.image_url_1" alt="" style="">
                                       </td>
                                       <td class="text-left">
                                           <a target="_blank" :href="row.final_url" v-text="row.item_title" style="color:#0074ba;font-size:14px;margin:3px 0;text-decoration: none"></a>
                                           <p style="color: #009877;font-weight: 300;margin:0px;line-height:23px;font-size:12px">
                                               {{ row.l1_category }} /
                                               <small v-text="row.l2_category" style="color:#666666"></small>
                                               <span class="shop_name pull-right" v-text="row.shop ? row.shop.shop_name : ''"></span>
                                           </p>
                                           <p style="font-size: 12px;margin-bottom:1px;font-weight:700">
                                               {{ row.price }} {{ getCurrencyText() }}
                                               <span class="discount" v-if="row.discount_percentage > 0" v-text="row.original_price"></span>
                                               <span class="discount_percentage" v-if="row.discount_percentage > 0" v-text="row.discount_percentage+'%'"></span>
                                               <span class="pull-right" style="font-size: 10px;font-style: italic;font-weight: 300" v-text="moment_time_ago(row.published_date)"></span>
                                           </p>
                                       </td>
                                   </tr>
                                   </tbody>
                               </table>

                           </div>
                            <?php echo Form::close(); ?>

                        </div>

                    </div>
                </div>



            </div>
        </div>
        <!-- #END# Color Pickers -->

    </div>




<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_page_script'); ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.5/dist/latest/bootstrap-autocomplete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.4.3/dist/voerro-vue-tagsinput.js"></script>
    <script type="text/javascript">

        var app = new Vue({
            el:'#app',
            components: { VoerroTagsInput },
            data:{
                selectedShops: [],
                shopLists:[],
                typeheadStyle:'dropdown',
                banner_id:<?php echo $banner->id; ?>,
                title:'<?php echo $banner->title; ?>',
                message:false,
                disabled:false,
                market:'<?php echo $banner->market; ?>',
                product_ids:'<?php echo $banner->product_ids; ?>',
                category:`<?php echo $banner->category; ?>`,
                category_list_url: 'https://tracking.bikroyit.com:3500/get_category',
                all_category:[],
                loading:false,
                form_submit_animation:false,
                is_enable_price_range:false,
                is_enable_discount_range:false,
                product_keywords:'<?php echo $banner->product_keywords; ?>',
                shop_name:`<?php echo $banner->shop_name; ?>`,
                shop_id:'<?php echo $banner->shop_id; ?>'.split(','),
                min_price:'<?php echo $banner->min_price; ?>',
                max_price:'<?php echo $banner->max_price; ?>',
                min_discount:'<?php echo $banner->min_discount; ?>',
                max_discount:'<?php echo $banner->max_discount; ?>',
                utm_code:'<?php echo $banner->utm_code; ?>',
                sort_by:'<?php echo $banner->sort_by; ?>',
                cta_button_text: '<?php echo $banner->cta_button_text; ?>',
                product_search_url:'https://tracking.bikroyit.com:3500/product_list',
                shop_list_url: '<?php echo URL::to('module/get_shop'); ?>',
                banner_preview_url: '<?php echo URL::to("module/dsd",$banner->id); ?>',
                products:[],
                preview_products:[],
                enable_equalise_exposure:(<?php echo json_encode($banner->enable_equalise_exposure); ?>) == 1 ? true : false,
                size_for_preview:'300x250',
                product_ids:'<?php echo $banner->product_ids; ?>',
            },
            methods:{
                onTagsUpdated() {
                    this.product_search();
                },
                price_range_filter:function(e){
                    this.is_enable_price_range = e.target.checked;
                    $(e.target).closest('.panel-title').find('a').click();
                    if(!this.is_enable_price_range){
                        this.min_price = '';
                        this.max_price = '';
                        this.product_search();
                    }
                },
                discount_range_filter:function(e){
                    this.is_enable_discount_range = e.target.checked;
                    $(e.target).closest('.panel-title').find('a').click();
                    if(!this.is_enable_discount_range){
                        this.min_discount = '';
                        this.max_discount = '';
                        this.product_search();
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
                    axios.post(this.product_search_url,{
                        market:this.market,
                        product_ids:this.product_ids.split(','),
                        sort_by:this.sort_by,
                    }).then(function(res){
                        _this.loading = false
                        _this.products = res.data;
                        if(_this.enable_equalise_exposure){
                            _this.onChangeEqualiseExposure();
                        }
                    });
                },
                change_shop_name:function(){
                    console.log(this.shop_name.length);
                    if(this.shop_name.length === 0){
                        this.product_search();
                    }
                },


                get_shop_list:function(){
                    let _this = this;
                    axios.get('https://tracking.bikroyit.com:3500/get_shop_list?market='+this.market).then(res=>{
                        res.data = res.data.map((item)=>{
                            return {
                                key:item.id,
                                value:item.shop_name,
                            }
                        })
                        _this.shopLists = res.data;
                        //console.log(res.data);
                        _this.selectedShops = _.filter(res.data, (v) => _.includes(_this.shop_id, v.key));
                        if(_this.selectedShops.length === 0){
                            this.product_search();
                        }
                        //console.log(output)
                    })
                },
                get_category:function(){
                    let category_input_field =  $(".category_selectpicker");
                    let _this = this;
                    //this.category_input_field.selectpicker({noneSelectedText: 'Insert Placeholder text'});
                    axios.get(this.category_list_url+'?market='+this.market).then(res=>{
                        _this.all_category = res.data;
                        _this.$nextTick(function(){
                            category_input_field.val(_this.category)
                            category_input_field.selectpicker("refresh");
                        });
                    });
                },
                change_market:function(){
                    this.selectedShops = [];
                    this.category='';
                    this.get_shop_list();
                    this.product_search();
                    this.get_category();
                },
                submit:function () {
                    this.form_submit_animation = true;
                    let _this = this;
                    axios.patch("<?php echo URL::to('module/dsd/'); ?>/"+this.banner_id,{
                        _method:'patch',
                        _token:'<?php echo csrf_token(); ?>',
                        title:this.title,
                        market:this.market,
                        category:this.category,
                        shop_id:_.filter(this.selectedShops).map(v=>v.key),
                        product_ids:this.product_ids,
                        shop_name:this.shop_name,
                        product_keywords: this.product_keywords,
                        min_price: this.min_price,
                        max_price: this.max_price,
                        min_discount: this.min_discount,
                        max_discount:this.max_discount,
                        utm_code: this.utm_code,
                        cta_button_text: this.cta_button_text,
                        sort_by:this.sort_by,
                        enable_equalise_exposure: this.enable_equalise_exposure,
                    }).then(function(res){
                        _this.form_submit_animation = false
                        console.log(res.data);
                        location.reload();
                    });
                },
                load_preview:function () {
                    //console.log(this.size_for_preview);
                    window.open(this.banner_preview_url+'?banner_size='+this.size_for_preview,'_blank');
                },
                change_preview:function(){

                },
                redirect_to_url:function (url) {
                    window.location.href = url;
                },
                moment_time_ago:function(date){
                    return moment(date).fromNow();
                },
                onChangeEqualiseExposure:function(){
                    if(_.isEmpty(this.preview_products)){
                        this.preview_products = this.products;
                    }
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
                    }else{
                        this.products = this.preview_products;
                    }

                },
                create_html_template:function(){
                    axios.post('<?php echo URL::to("module/create_html_template"); ?>',{
                        products:this.products,
                        market:this.market,
                        utm_code:this.utm_code
                    }).then(function(res){
                        console.log(res.data);
                        let $temp = $("<input>");
                        $("body").append($temp);
                        $temp.val(res.data).select();
                        document.execCommand("copy");
                        $temp.remove();
                        toast('Code copied...');
                    });
                }
            },
            mounted:function(){
                //this.product_search();
                this.get_shop_list();
                this.get_category();
            },
            filters:{
                resize:function(value){
                    let split = value.split('/');
                    split[split.length-3] ='120';
                    split[split.length-2] ='101';
                    return split.join('/');
                }
            },
        });

        $(document).ready(function(){


            $('.generate_code').click(function(e){
                e.preventDefault();
                let size_arr = app.size_for_preview.split('x');
                let width = size_arr[0];
                let height = size_arr[1];
                let template = '<iframe src="<?php echo URL::to('html_contents/dsd/','',true); ?>/'+$(this).data("id")+'/'+app.size_for_preview+'.html" height="'+height+'" width="'+width+'" style="border:none" scrolling="no"></iframe>';
                let $temp = $("<input>");
                $("body").append($temp);
                $temp.val(template).select();
                document.execCommand("copy");
                $temp.remove();
                toast('Code copied...');
            })


        })

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>