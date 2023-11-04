<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="block-header">
            <a href="<?php echo URL::to('module/banners'); ?>" class="btn btn-sm btn-primary"> <i class="material-icons">list</i> ALL Banners Ad</a>
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
                            Edit banners-ad - <?php echo e($banner->title); ?>

                        </h2>
                    </div>
                    <div class="body" id="app">
                        <div class="row clearfix">
                            <?php echo Form::model($banner,['url'=>URL::to('module/banners',$banner->id),'class'=>'form','method'=>'put','files'=>'true']); ?>

                                <div class="col-sm-4">
                                    <label for="">Title</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="title" class="form-control date" value="<?php echo e($banner->title); ?>" autofocus>
                                        </div>
                                    </div>

                                    <label for="">Start Time</label>
                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">watch_later</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="start_time" class="form-control date" value="<?php echo e($banner->start_time); ?>" autofocus>
                                        </div>
                                    </div>

                                    <label for="">End Time</label>
                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">watch_later</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="end_time" class="form-control date" value="<?php echo e($banner->end_time); ?>" autofocus>
                                        </div>
                                    </div>

                                    <label for="">No Of Visibility</label>
                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="no_of_visibility" class="form-control date" value="<?php echo e($banner->no_of_visibility); ?>" autofocus>
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">palette</i>
                                        </span>
                                        <div>
                                            <input type="color" name="background_color" value="<?php echo e($banner->color); ?>">
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">opacity</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="background_opacity" class="form-control date" value="<?php echo e($banner->opacity); ?>" autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" name="submit" value="SAVE" class="btn btn-success btn-sm btn-block">
                                    </div>
                                </div>
                                <div class="col-sm-8" style="overflow-x:auto;">
                                    <table class="table table-condensed table-bordered text-center" id="table">
                                        <thead>
                                            <tr>
                                                <td  style="width:30%">Link</td>
                                                <td style="width:45%">Content</td>
                                                <td style="width:25%">Site URL</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="">
                                                        <small>For roadblock file upload</small>
                                                    </label><br>
                                                    <input type="file" name="file" style="display:none;">
                                                    <a onClick="fileUpload($(this),'ToHideR')" style="margin-bottom:10px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                                    </a>
                                                    
                                                    <?php if(isset($banner->link)): ?>
                                                        <textarea name="link" rows="5" cols="20" class="link"><?php echo e($banner->link); ?></textarea>
                                                    <?php else: ?>
                                                        <textarea name="link" rows="5" cols="20" class="link"></textarea>
                                                    <?php endif; ?>
                                                    <?php if($errors->has('link')): ?>
                                                        <span class="invalid feedback"role="alert" style="color:red;">
                                                            <strong><?php echo e($errors->first('link')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                        
                                                </td>
                                                
                                                <td>
                                                    <?php if((isset($banner->link)) && (validImage($banner->link) == 0)): ?>
                                                        <iframe src="<?php echo e($banner->link); ?>" class="ToHideR" style="overflow:hidden;"></iframe>
                                                    <?php elseif((isset($banner->link)) && (validImage($banner->link) == 1)): ?>
                                                        <img src="<?php echo e($banner->link); ?>" class="ToHideR" height="150" width="200">
                                                    <?php endif; ?>
                                                        <iframe src="<?php echo e($banner->link); ?>" class="content-show" style="overflow:hidden; display:none;"></iframe>
                                                        <img src="<?php echo e($banner->link); ?>" class="img-show" height="150" width="200" style="display:none;">
                                                </td>
                                                <td>
                                                    <label for="">
                                                        <small>For roadblock site URL</small>
                                                    </label><br>
                                                    <textarea name="roadblock_site_url" rows="5" cols="20"><?php echo e($banner->roadblock_site_url); ?></textarea>
                                                    <?php if($errors->has('roadblock_site_url')): ?>
                                                        <span class="invalid feedback"role="alert" style="color:red;">
                                                            <strong><?php echo e($errors->first('roadblock_site_url')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="">
                                                        <small>For background file upload</small>
                                                    </label><br>
                                                    <input type="file" name="background_file" style="display:none;">
                                                    <a onClick="fileUpload($(this),'ToHideB')" style="margin-bottom:10px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                                    </a>
                                                    <?php if(isset($banner->background_link)): ?>
                                                        <textarea name="background_link" rows="5" cols="20" class="link"><?php echo e($banner->background_link); ?></textarea>
                                                    <?php else: ?>
                                                        <textarea name="background_link" rows="5" cols="20" class="link"></textarea>
                                                    <?php endif; ?>
                                                    <?php if($errors->has('background_link')): ?>
                                                        <span class="invalid feedback"role="alert" style="color:red;">
                                                            <strong><?php echo e($errors->first('background_link')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <td>
                                                    <?php if((isset($banner->background_link)) && (validImage($banner->background_link) == 0)): ?>
                                                        <iframe src="<?php echo e($banner->background_link); ?>" class="ToHideB" style="overflow:hidden;"></iframe>
                                                    <?php elseif((isset($banner->background_link)) && (validImage($banner->background_link) == 1)): ?>
                                                        <img src="<?php echo e($banner->background_link); ?>" class="ToHideB" height="150" width="200">
                                                    <?php endif; ?>
                                                        <iframe src="<?php echo e($banner->background_link); ?>" class="content-show" style="overflow:hidden; display:none;"></iframe>
                                                        <img src="<?php echo e($banner->background_link); ?>" class="img-show" height="150" width="200" style="display:none;">
                                                </td>
                                                <td>
                                                    <label for="">
                                                        <small>For background site URL</small>
                                                    </label><br>
                                                    <textarea name="background_site_url" rows="5" cols="20"><?php echo e($banner->background_site_url); ?></textarea>
                                                    <?php if($errors->has('background_site_url')): ?>
                                                        <span class="invalid feedback"role="alert" style="color:red;">
                                                            <strong><?php echo e($errors->first('background_site_url')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div> 
                        <div class="row clearfix">   
                            <div class="col-sm-4"> 
                                <label for="">
                                    <small>Changes won't reflect until you save it </small>
                                </label>
                            
                                <div class="form-group" style="margin-bottom: 2px;padding:10px;background:#ddd;">
                                    <table class="table" style="margin-bottom: 0;">
                                        <thead>
                                        <tr>
                                            <td style="padding-bottom: 0">
                                                <div class="desktop-size">
                                                    <?php echo Form::select('size_for_preview',['970x90'=>'970x90', '300x250'=>'300x250', '160x600'=>'160x600'],null,['class'=>'selectpicker', 'data-width'=>'100%','v-model'=>'size_for_preview']); ?>

                                                </div>
                                                <div class="mobile-size" style="display:none;">
                                                    <?php echo Form::select('size_for_preview',['320x100'=>'320x100','250x250'=>'250x250','320x250'=>'320x250'],null,['class'=>'selectpickerMobile','data-width'=>'100%','v-model'=>'size_for_preview']); ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-bottom: 0">
                                                <?php echo Form::select('version',['desktop'=>'Desktop','mobile'=>'Mobile'],null,['class'=>'version', 'data-width'=>'100%', 'v-model'=>'version', '@change'=>'change_version']); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="btn-group btn-group-justified" role="group">
                                                    <a @click.prevent="load_preview" class="btn btn-warning btn-sm waves-effect" data-toggle="modal" data-target="#defaultModal">Preview Banner</a>
                                                    <a @click.prevent data-id="<?php echo $banner->id; ?>" class="btn bg-teal btn-sm waves-effect generate_code" >Code</a>
                                                </div>
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
                banner_preview_url: '<?php echo URL::to("module/banners",$banner->id); ?>',
                banner_id:<?php echo $banner->id; ?>,
                size_for_preview:'970x90',
                version:'desktop',
            },
            methods:{
                load_preview:function () {
                   
                   window.open(this.banner_preview_url+'?banner_size='+this.size_for_preview+'&version='+this.version,'_blank');
                },
                change_version:function(){
                    if(this.version == 'mobile'){
                        this.size_for_preview = '320x100';
                        $('.desktop-size').hide();
                        $('.mobile-size').show();
                    }else if(this.version == 'desktop'){
                        this.size_for_preview = '970x90';
                        $('.desktop-size').show();
                        $('.mobile-size').hide();
                    }
                },
            }
        });

        $(document).ready(function(){

            $('.generate_code').click(function(e){
                e.preventDefault();
                let size_arr = app.size_for_preview.split('x');
                let width = size_arr[0];
                let height = size_arr[1];
                let template = '<iframe src="<?php echo URL::to('public/banners/background_file.html'); ?>" height="'+height+'" width="'+width+'" style="border:none" scrolling="no"></iframe><script src="<?php echo URL::to('public/banners/','',true); ?>/rich_media_ad_banner_'+app.version+'.js"><'+'/script>';
                let $temp = $("<input>");
                $("body").append($temp);
                $temp.val(template).select();
                document.execCommand("copy");
                $temp.remove();
                toast('Code copied...');
            });
        });

        function fileUpload(This,classToHide){
            This.prev().trigger('click');
            This.prev().change(function(){
                $('.'+classToHide).hide();
                This.next().val('');
                const file = this.files[0];
                if (file){
                    let reader = new FileReader();
                    reader.onload = function(event){
                        This.closest('td').next().find('.img-show').attr('src', event.target.result);
                        This.closest('td').next().find('.img-show').show();
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
        $('.link').keyup(function(){
            if($this.val()){
                $('.ToHide').hide();
            }
            if(url.match(/\.(jpeg|jpg|gif|png)$/) != null){
                $(this).closest('td').next().find('.img-show').attr('src',$(this).val());
                $(this).closest('td').next().find('.img-show').show();
            }else{
                $(this).closest('td').next().find('.content-show').attr('src',$(this).val());
                $(this).closest('td').next().find('.content-show').show();
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>