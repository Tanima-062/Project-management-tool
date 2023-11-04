<?php $__env->startSection('custom_page_style'); ?>
    <style>
        .impression_badge{

        }
        .live_campaign_indicator{
            position: absolute;
            top: 16px;
            right: 2px;
            width: 30px;
        }
        .live_campaign_indicator{
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="block-header">
            <a href="<?php echo URL::to('module/dsd/create'); ?>" class="btn btn-sm btn-primary"> <i class="material-icons">add_circle_outline</i> Create New DSD Banner</a>
            <?php if(Session::has('message')): ?>
                <div class="alert bg-teal alert-dismissible m-t-20 animated fadeInDownBig" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <?php echo Session::get('message'); ?>

                </div>
            <?php endif; ?>
        </div>

        <!-- Striped Rows -->
        <div class="row clearfix" id="app">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <i class="material-icons btn btn-sm btn-warning">list</i> <span>All DSD-BANNER</span>
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
                    <div class="body table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th style="width: 500px">Title</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Created at</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="font-12" data-row_id="<?php echo $result->id; ?>">
                                <th scope="row"><?php echo ($i+1); ?></th>
                                <td style="position: relative" >
                                    <a data-toggle="tooltip" data-title="Edit" href="<?php echo URL::to('module/dsd/'.$result->id,'edit'); ?>"><?php echo $result->title; ?></a>
                                    <img class="live_campaign_indicator" src="<?php echo asset('public/images/dsd/live.gif'); ?>" alt="Live image icon">
                                </td>
                                <td><span class="impression_badge"></span></td>
                                <td><span class="click_badge"></span></td>
                                <td><span class="ctr_badge"></span></td>
                                <td><?php echo $result->created_at->toDateString(); ?></td>
                                <td>
                                    <a data-id="<?php echo $result->id; ?>" data-toggle="tooltip" data-title="More Analytics" class="btn btn-xs btn-success" href="#"><i class="material-icons">analytics</i></a>
                                    <a data-toggle="tooltip" data-title="Edit" class="btn btn-xs btn-warning" href="<?php echo URL::to('module/dsd/'.$result->id,'edit'); ?>"><i class="material-icons">edit</i></a>
                                    <a data-toggle="tooltip" data-title="Delete" class="btn btn-xs btn-danger delete_with_swal" href="<?php echo URL::to('module/dsd',$result->id); ?>"><i class="material-icons">remove</i></a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php echo $results->links(); ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Striped Rows -->
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_page_script'); ?>
    <script src="<?php echo URL::to('public/js/axios.js'); ?>"></script>

    <script type="text/javascript">
        let request_arr = [];
        let request_url = 'https://tracking.bikroyit.com:4000/dad_analytics_by_id/';
        let get_analytics = function(){
            //$('.live_campaign_indicator').css('display','none');
            $('table tbody tr').each(async function(item){
                let dsd_id = $(this).data('row_id');
                let response = await axios.get(request_url+dsd_id);
                let ctr = response.data[0] > 0 ? (response.data[1]/response.data[0])*100 : 0;
                $(this).find('.impression_badge').text(response.data[0].toLocaleString())
                $(this).find('.click_badge').text(response.data[1]);

                $(this).find('.ctr_badge').text(ctr.toFixed(2));
                if(response.data[2] === true){
                    $(this).find('.live_campaign_indicator').css('display','block');
                }else{
                    $(this).find('.live_campaign_indicator').css('display','none');
                }
            });

        }
        $(document).ready(function(){
            get_analytics();
            setInterval(function(){
                get_analytics();
            },5000);
        })

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.table', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>