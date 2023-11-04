<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <style>
        #input_container {
            position:relative;
            padding:0 0 0 5px;
            margin-left: 5px;
        }
        #input {
            height:20px;
            margin:0;
            padding-right: 30px;
            border:none;
            width: 100%;
        }
        #input_img {
            position:absolute;
            bottom:2px;
            right:5px;
            width:24px;
            height:24px;
        }
    </style>
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel bulk-upload-panel">
            <div class="content-wrapper">
                <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="BulkUploadBox card-body">
                    <h5 class="my-2">Upload Bulk File</h5>
                   <hr>
                   <div class="bulk-upload-form">
                       <form method="POST" action="<?php echo e(route('users.import')); ?>" enctype="multipart/form-data">
                           <?php echo csrf_field(); ?>
                           <div class="form-row ">
                               <div class="col-md-12">
                                   <label class="file-up-label mb-3" for="customFileLang">File Upload</label>
                                   <div class="custom-file">
                                       <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="select_file">
                                       <label class="custom-file-label" for="customFileLang"></label>
                                   </div>
                               </div>
                               <div class="col-md-12">
                                   <div class="form-group row">
                                       <div class="col-md-12 py-4 text-center"> 
                                           <a class="bulk-download" href="<?php echo e(route('users.download.excelTemplate')); ?>" style="color:#ec008c;">Download Template</a>
                                       </div>
                                       <div class="col-md-12 text-center">
                                           <a href="<?php echo e(route('users.index')); ?>" class="btn custom-outline-btn">Cancel</a>
                                           <button class="btn custom-btn">Upload</button>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </form>
                   </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->
        <?php echo $__env->make('backend.layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <script>
            $('input[type="file"]').change(function(e){
                const fileName = e.target.files[0].name;
                $('.custom-file-label').html(fileName);
            });
        </script>

</body>

</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/users/bulk_upload.blade.php ENDPATH**/ ?>