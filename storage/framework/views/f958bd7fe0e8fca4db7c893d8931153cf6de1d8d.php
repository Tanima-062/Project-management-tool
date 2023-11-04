<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="block-header">
            <a href="<?php echo URL::to('module/deals'); ?>" class="btn btn-sm btn-primary"> <i class="material-icons">list</i> ALL Deals Ad</a>
            <?php if(Session::has('message')): ?>
            <div class="alert bg-teal alert-dismissible m-t-20 animated fadeInDownBig" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo Session::get('message'); ?>

            </div>
            <?php endif; ?>
        </div>
        <!-- Color Pickers -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Update deals-ad
                            <small>You may put multiple link one after one</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body" id="app">
                        <div class="row clearfix">
                            <?php echo Form::model($result,['url'=>URL::to('module/deals',$result->id),'class'=>'form','method'=>'put','files'=>'true']); ?>

                            <div class="col-xs-3">
                                <label for="">Title of this ad</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">person</i>
                                    </span>
                                    <div class="form-line">
                                        <?php echo Form::text('ad_title',$result->title,['class'=>'form-control','placeholder'=>'Title..']); ?>

                                    </div>
                                </div>
                                <label for="">UTM</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">link</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" name="utm" value="<?php echo $result->utm; ?>" class="form-control" placeholder="?example=something" autofocus>
                                    </div>
                                </div>
                                <label for="">Size</label>
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                        <div class="input-group" style="margin-bottom:1px;">
                                            <span class="input-group-addon"><i class="material-icons">import_export</i></span>
                                            <div class="form-line">
                                                <?php echo Form::select('type',get_sizes(),null,['class'=>'form-control selectpicker','v-model'=>'type','@change'=>'create_preview_url']); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label for="">

                                    <a style="margin-bottom:10px;display:block" href="#" data-toggle="modal" data-target="#largeModal" >
                                        <i class="material-icons btn-xs">build</i> Campaign Setting
                                    </a>
                                </label>

                                <div class="row">
                                    <div class="col-xs-6" style="margin-bottom: 5px;">
                                        <a target="_blank" :href="prev_url" class="btn btn-warning btn-sm btn-block waves-effect waves-black"><i class="material-icons pull-left animated pulse infinite text-success" style="top:0px">details</i>View</a>
                                    </div>
                                    <div class="col-xs-6" style="margin-bottom: 5px;">
                                        <a href="#" class="btn btn-info btn-sm btn-block waves-effect waves-black" @click.prevent="generate_code()"><i class="material-icons pull-left animated pulse infinite text-info" style="top:0px">code</i> CODE</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" value="UPDATE" class="btn btn-success btn-block btn-sm waves-effect waves-black">
                                </div>

                            </div>
                           <div class="col-xs-9">
                               <table class="table table-condensed table-bordered text-center table-hover" id="table">
                                   <thead>
                                   <tr>
                                       <td  style="width:250px">Link</td>
                                       <td>Content</td>
                                       <td style="width:250px">Custom Title</td>
                                       <td>
                                           <a href="#" class="btn btn-success btn-xs waves-effect waves-block" @click.prevent="addRow()"> <i class="material-icons">add</i></a>
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
                                                   <input @keyup="change_link(row,index)" type="text" v-model="row.link" name="link[]" class="form-control date" placeholder="Link">
                                               </div>
                                           </div>
                                       </td>
                                       <td class="text-left">
                                           <div class="row">
                                               <div class="col-xs-6" style="margin-bottom:5px;">
                                                   <img v-if="row.loading" style="height:50px" src="<?php echo URL::to('public/images/loading_icon.gif'); ?>"
                                                        alt="Loading icon">
                                                   <img v-if="row.image != ''" :src="row.image" alt="" style="height:50px">
                                                   <input type="hidden" name="id[]" v-model="row.id" value="">
                                                   <input type="hidden" name="image[]" v-model="row.image" value="">
                                                   <input type="hidden" name="current_price[]" v-model="row.current_price">
                                                   <input type="hidden" name="discount[]" v-model="row.discount">
                                                   <input type="hidden" name="title[]" v-model="row.title">
                                                   <input type="hidden" name="discounted_price[]" v-model="row.discounted_price">
                                                   <img v-if="row.loading" style="height:50px" src="<?php echo URL::to('public/images/loading_icon.gif'); ?>"
                                                        alt="Loading icon">
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
                                                   <input type="text" v-model="row.title" name="" :maxlength="max" class="form-control" placeholder="Title preview" autofocus>
                                               </div>
                                           </div>
                                           <p v-text="row.title" class="font-13 text-muted font-italic font-bold"></p>
                                       </td>
                                       <td style="width:50px">
                                           <a href="#" class="btn btn-xs btn-danger waves-effect waves-black" @click.prevent="removeRow(row,index)"><i class="material-icons">remove</i></a>
                                       </td>
                                   </tr>
                                   </tbody>
                               </table>

                           </div>
                            <?php echo Form::close(); ?>

                        </div>
                        <!-- Large Size -->
                        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="largeModalLabel">Campaign setup</h4>
                                        <?php if(Session::has('campaign_save_message')): ?>
                                            <div class="alert bg-teal alert-dismissible m-t-20 animated fadeInDownBig" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <?php echo Session::get('campaign_save_message'); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <?php echo Form::open(['url'=>URL::to('module/post_creative_campaign',$result->id),'files'=>'true']); ?>

                                                <div class="row clearfix">
                                                    <div class="col-xs-12">
                                                        <label for="">Display Time</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">av_timer</i>
                                                            </span>
                                                            <div class="form-line">
                                                                <input type="text" value="10" name="campaign_display_time" v-model="edit_campaign_display_time" class="form-control" placeholder="EX: 4 (Second)" autofocus>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-xs-12">
                                                        <label for="">Size</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="material-icons">import_export</i></span>
                                                            <div class="form-inline">
                                                                <?php echo Form::select('type',get_sizes(),null,['class'=>'form-control selectpicker','v-model'=>'edit_type','@change'=>'create_preview_url']); ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-xs-12">
                                                        <div class="input-group">
                                                            <label for="">Enable Campaign Ad</label>
                                                            <div class="switch">
                                                                <label>
                                                                    <input name="enable_campaign" v-model="edit_enable_campaign" :checked="edit_enable_campaign==1 ? 'checked' : ''" type="checkbox">
                                                                    <span class="lever" style="margin-left:0px;"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label for="Creative">Creative File</label>
                                                            <input type="file" name="creative" class="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <?php echo Form::submit('SAVE',['class'=>'btn btn-sm btn-block btn-success']); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                                <?php echo Form::close(); ?>

                                            </div>
                                            <div class="col-xs-8">
                                                <h5 class="text-success">Previously used creative.</h5>
                                                <table class="table table-striped table-bordered table-hover table-condanced font-12 text-center">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">SL</th>
                                                        <th class="text-center">Size</th>
                                                        <th class="text-center">Display Time</th>
                                                        <th class="text-center">Active Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(row, index) in campaign_creatives" class="text-center">
                                                        <td v-text="(index+1)"></td>
                                                        <td v-text="row.size"></td>
                                                        <td v-text="row.time"></td>
                                                        <td>
                                                            <div class="input-group" style="margin-bottom: 0px;">
                                                                <div class="switch">
                                                                    <label>
                                                                        <input name="enable_campaign" type="checkbox" :checked="row.is_active==1 ? 'checked' : ''">
                                                                        <span class="lever" style="margin-left:0px;"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a data-toggle="tooltip" data-title="Show Campaign" href="#" @click.prevent="edit_campaign_creative(row)" class="btn btn-xs btn-primary waves-effect waves-black">
                                                                <i class="material-icons">edit</i>
                                                            </a>
                                                            <a data-toggle="tooltip" @click.prevent="removeCreativeCampaignRow(row,index)" data-title="Delete Campaign" href="#" class="btn btn-xs btn-danger waves-effect waves-black">
                                                                <i class="material-icons">remove</i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-link waves-effect btn-danger" data-dismiss="modal">CLOSE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Color Pickers -->

    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_page_script'); ?>
    <?php if(Session::has('campaign_save_message')): ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#largeModal').modal('show')
            })

        </script>
    <?php endif; ?>
    <script src="<?php echo URL::asset('public/js/pages/ui/modals.js'); ?>"></script>
    <script type="text/javascript">
        var app = new Vue({
            el:'#app',
            data:{
                table_data_arr:<?php echo $result->contents; ?>,
                campaign_creatives:<?php echo $result->creative; ?>,
                fix_prev_url: '<?php echo URL::to('module/deals',$result->id); ?>',
                prev_url : '<?php echo URL::to('module/deals?size=970x250',$result->id); ?>',
                type:'970x250',
                edit_campaign_display_time:5,
                edit_type:'970x250',
                edit_enable_campaign:'',
                max:27,

            },
            methods:{
                addRow:function(){
                    this.table_data_arr.push({
                        id:'',
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
                removeRow:function(row,index){
                    if(row.id != ''){
                        var _this = this;
                        is_confirm(function(){
                            var url = '<?php echo URL::to('module/delete_deals_content'); ?>/'+row.id;
                            var req = axios.get(url);
                            req.then(function(res){
                                swal_close('Data deleted');
                                _this.table_data_arr.splice(index,1);
                            });
                            req.catch(function(error){
                               swal_close('Server error found');
                            });

                        });
                    }else{
                        this.table_data_arr.splice(index,1);
                    }
                },
                removeCreativeCampaignRow:function(row,index){
                    if(row.id != ''){
                        var _this = this;
                        is_confirm(function(){
                            var url = '<?php echo URL::to('module/delete_creative_campaign'); ?>/'+row.id;
                            var req = axios.get(url);
                            req.then(function(res){
                                swal_close('Data deleted');
                                _this.campaign_creatives.splice(index,1);
                            });
                            req.catch(function(error){
                                swal_close('Server error found');
                            });

                        });
                    }else{
                        this.table_data_arr.splice(index,1);
                    }
                },
                change_link:function (row, index) {
                    row.link = row.link.replace("bn", "en");
                    row.loading=true;
                    row.raw_title = '',
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
                        row.raw_title=res.raw_title;
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
                create_preview_url:function(){
                    //console.log(this.type);
                    this.prev_url = this.fix_prev_url+'?size='+this.type;
                },
                generate_code:function(){
                    var _this= this;
                    var req = axios.get('<?php echo URL::to('create_html_file',$result->id); ?>'+'?size='+_this.type);
                    req.then(function(res){
                        
                    });
                    req.catch(function(err){
                        console.log(err);
                        toast('There is a problem with server');
                    });
                    
                    var split = _this.prev_url.split('size=');
                    var size = split[1].split('x');
                    var template = '<iframe src="<?php echo URL::to('html_contents',$result->id,true); ?>/'+_this.type+'.html" height="'+size[1]+'" width="'+size[0]+'" style="border:none" scrolling="no"></iframe>';
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(template).select();
                    document.execCommand("copy");
                    $temp.remove();
                    toast();
                },
                edit_campaign_creative:function(row){
                    //console.log(row);
                    this.edit_campaign_display_time = row.time;
                    this.edit_type = row.size;
                    this.edit_enable_campaign = row.is_active;
                    $('.filter-option.pull-left').text(row.size);
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>