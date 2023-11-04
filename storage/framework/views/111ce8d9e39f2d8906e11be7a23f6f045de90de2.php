<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="block-header">
            <a href="<?php echo URL::to('module/deals'); ?>" class="btn btn-sm btn-primary"> <i class="material-icons">list</i> ALL Deals Ad</a>
        </div>
        <!-- Color Pickers -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Create new deals-ad
                            <small>You may put multiple link one after one</small>
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
                        <div class="row clearfix">
                            <?php echo Form::open(['url'=>URL::to('module/deals'),'class'=>'form','files'=>'true']); ?>

                            <div class="col-xs-3">
                                <label for="">Title</label>
                                <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">person</i>
                                                </span>
                                    <div class="form-line">
                                        <input type="text" name="ad_title" class="form-control date" placeholder="Title.." autofocus>
                                    </div>
                                </div>
                                <label for="">UTM</label>
                                <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">link</i>
                                                </span>
                                    <div class="form-line">
                                        <input type="text" name="utm" class="form-control" placeholder="?example=something" autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="submit" name="submit" value="SAVE" class="btn btn-success btn-sm btn-block">
                                </div>

                            </div>
                           <div class="col-xs-9">
                               <table class="table table-condensed table-bordered text-center" id="table">
                                   <thead>
                                   <tr>
                                       <td  style="width:250px">Link</td>
                                       <td>Content</td>
                                       <td style="width:250px">Custom Title</td>
                                       <td style="width:50px">
                                           <a href="#" class="btn btn-success btn-xs" @click.prevent="addRow()"> <i class="material-icons">add</i></a>
                                       </td>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr v-for="(row, index) in table_data_arr" class="">
                                       <td style="width:250px">
                                           <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">link</i>
                                                </span>
                                               <div class="form-line">
                                                   <input @keyup="change_link(row,index)" type="text" v-model="row.link" name="link[]" class="form-control date" placeholder="Link" autofocus>
                                               </div>
                                           </div>
                                       </td>
                                       <td>
                                           <div class="row">
                                               <div class="col-xs-6" style="margin-bottom:5px;">
                                                   <img v-if="row.loading" style="height:50px" src="<?php echo URL::to('public/images/loading_icon.gif'); ?>"
                                                        alt="Loading icon">
                                                   <img v-if="row.image != ''" :src="row.image" alt="" style="height:60px">
                                                   <input type="hidden" name="image[]" v-model="row.image" value="">
                                                   <input type="hidden" name="current_price[]" v-model="row.current_price">
                                                   <input type="hidden" name="discount[]" v-model="row.discount">
                                                   <input type="hidden" name="title[]" v-model="row.title">
                                                   <input type="hidden" name="discounted_price[]" v-model="row.discounted_price">
                                               </div>
                                               <div class="col-xs-6" style="margin-bottom:0;padding-top:13px;padding-left:0px;">
                                                   <p v-if="row.current_price != ''" style="font-size: 12px;margin-bottom:1px">
                                                       Price: <span class="badge" >{{ row.current_price }}</span>
                                                       <span class="small" v-text="'('+row.discount+'%)'"></span>
                                                   </p>
                                                   <p v-if="row.discount != ''" style="font-size:12px;margin-bottom:0px">
                                                       <span class="font-line-through" style="font-style:italic" v-text="'MRP:'+ row.discounted_price"></span><br>
                                                   </p>
                                               </div>
                                           </div>
                                           <div class="row">
                                               <div class="col-xs-12" style="margin-bottom: 0px;">
                                                   <h5 style="margin:0px" v-html="row.raw_title" class="text-left text-muted font-13"></h5>
                                               </div>
                                           </div>
                                       </td>
                                       <td style="width:250px">
                                           <div class="input-group" style="margin-bottom: 1px;">
                                               <div class="form-line">
                                                   <input type="text" v-model="row.title" :maxlength="max" name="" class="form-control" placeholder="Title preview" autofocus>
                                               </div>
                                           </div>
                                           <p v-text="row.title" class="font-13 text-muted font-italic font-bold"></p>
                                       </td>
                                       <td style="width:50px">
                                           <a href="#" class="btn btn-xs btn-danger" @click.prevent="removeRow(index)"><i class="material-icons">remove</i></a>
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
    <script type="text/javascript">

        var app = new Vue({
            el:'#app',
            data:{
                table_data_arr:[
                    {
                        loading:false,
                        link:'',
                        raw_title:'',
                        title:'',
                        image:'',
                        current_price:'',
                        discount:'',
                        discounted_price:''
                    }
                ],
                max:27
            },
            filters: {
                character_filter:function(){

                }
            },
            methods:{
                addRow:function(){
                    this.table_data_arr.push({
                        loading:false,
                        link:'',
                        raw_title:'',
                        title:'',
                        image:'',
                        current_price:'',
                        discount:'',
                        discounted_price:''
                    });
                },
                removeRow:function(index){
                    this.table_data_arr.splice(index,1);
                },
                change_link:function (row, index) {
                    row.link = row.link.replace("bn", "en");
                    row.loading=true;
                    row.raw_title='',
                    row.title = '';
                    row.image = '';
                    row.current_price = '';
                    row.discount = '';
                    row.discounted_price = '';
                    var url = '<?php echo URL::to('module/scrap_data_for_deals_ad'); ?>?link='+row.link;
                    var req = axios.get(url);
                    req.then(function(res){
                        var res = res.data;
                        row.loading = false;
                        row.raw_title=res.raw_title,
                        row.title = res.title;
                        row.image = res.img;
                        row.current_price = res.current_price;
                        row.discount = res.discount;
                        row.discounted_price = Math.ceil(res.discounted_price/10)*10
                    });
                    req.catch(function(error){
                        row.loading = false;
                    });
                },

            }
        });


    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>