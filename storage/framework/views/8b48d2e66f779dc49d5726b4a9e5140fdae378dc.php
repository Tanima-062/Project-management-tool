<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="block-header">
            <a href="<?php echo URL::to('module/banners'); ?>" class="btn btn-sm btn-primary"> <i class="material-icons">list</i> ALL Banners Ad</a>
        </div>
        <!-- Color Pickers -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Create new banners-ad
                        </h2>
                    </div>
                    <div class="body" id="app">
                        <div class="row clearfix">
                            <?php echo Form::open(['url'=>URL::to('module/banners'),'class'=>'form','files'=>'true']); ?>

                                <div class="col-sm-3">
                                    <label for="">Title</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="title" class="form-control" placeholder="Title.." value="<?php echo e(old('title')); ?>" autofocus>
                                        </div>
                                        <?php if($errors->has('title')): ?>
                                            <span class="invalid feedback"role="alert" style="color:red;">
                                                <strong><?php echo e($errors->first('title')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <label for="">Start Time</label>
                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">watch_later</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="start_time" class="form-control date" placeholder="Start Time.." value="<?php echo e(old('start_time')); ?>" autofocus>
                                        </div>
                                    </div>

                                    <label for="">End Time</label>
                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">watch_later</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="end_time" class="form-control date" placeholder="End Time.." value="<?php echo e(old('end_time')); ?>" autofocus>
                                        </div>
                                    </div>

                                    <label for="">No Of Visibility</label>
                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="no_of_visibility" class="form-control date" value="<?php echo e(old('no_of_visibility')); ?>" autofocus>
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">palette</i>
                                        </span>
                                        <div>
                                            <input type="color" name="background_color" value="<?php echo e(!empty(old('color'))? old('color'): '#ff0000'); ?>">
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon material-icons-outlined">
                                            <i class="material-icons">opacity</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="background_opacity" class="form-control date" placeholder="Opacity.." value="<?php echo e(old('opacity')); ?>" autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" name="submit" value="SAVE" class="btn btn-success btn-sm btn-block">
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <table class="table table-condensed table-bordered text-center" id="table">
                                        <thead>
                                        <tr>
                                            <td  style="width:28%">Link</td>
                                            <td style="width:47%">Content</td>
                                            <td style="width:25%">Site URL</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="">
                                                        <small>For roadblock file upload/link</small>
                                                    </label><br>
                                                    <input type="file" name="file" style="display:none;">
                                                    <a onClick="fileUpload($(this))" style="margin-bottom:10px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                                    </a>
                                                    <textarea name="link" rows="5" cols="20" class="link"><?php echo e(old('link')); ?></textarea>
                                                    <?php if($errors->has('link')): ?>
                                                        <span class="invalid feedback"role="alert" style="color:red;">
                                                            <strong><?php echo e($errors->first('link')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <td>
                                                    <iframe src="#" class="content-show" style="overflow:hidden; display:none;"></iframe>
                                                    <img src="#" class="img-show" height="100" width="200" style="display:none;">
                                                </td>
                                                <td>
                                                    <label for="">
                                                        <small>For roadblock site URL</small>
                                                    </label><br>
                                                    <textarea name="roadblock_site_url" rows="5" cols="20"><?php echo e(old('roadblock_site_url')); ?></textarea>
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
                                                        <small>For background file upload/link</small>
                                                    </label><br>
                                                    <input type="file" name="background_file" style="display:none;">
                                                    <a onClick="fileUpload($(this))" style="margin-bottom:10px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                                    </a>
                                                    <textarea name="background_link" rows="5" cols="20" class="link"><?php echo e(old('background_link')); ?></textarea>
                                                    <?php if($errors->has('background_link')): ?>
                                                        <span class="invalid feedback"role="alert" style="color:red;">
                                                            <strong><?php echo e($errors->first('background_link')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <td>
                                                    <iframe src="#" class="content-show" style="overflow:hidden; display:none;"></iframe>
                                                    <img src="#" class="img-show" height="100" width="200" style="display:none;">
                                                </td>
                                                <td>
                                                    <label for="">
                                                        <small>For background site URL</small>
                                                    </label><br>
                                                    <textarea name="background_site_url" rows="5" cols="20"><?php echo e(old('background_site_url')); ?></textarea>
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
            function fileUpload(This){
                This.prev().trigger('click');
                This.prev().change(function(){
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