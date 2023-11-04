<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel report-page">
            <div class="content-wrapper" >
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <h3 class="users-list-title">Report</h3>
                <hr class="Dash-Line">
                <div class="row">
                  <div class="col-md-7 m-auto">
                    <div class="card">
                      <div class="card-body">
                        <form action="<?php echo e(route('report.export')); ?>" method="POST">
                          <?php echo csrf_field(); ?>
                          <div class="form-group row px-5">
                            <div class="col-md-3">
                                <label for="start_date">Start Date :</label>
                            </div>
                            <div class="col-md-9">
                              <input type="date" name="start_date" class="form-control">
                            </div>
                          </div>

                          <div class="form-group row px-5">
                            <div class="col-md-3">
                                <label for="end_date">End Date :</label>
                            </div>
                            <div class="col-md-9">
                              <input type="date" name="end_date" class="form-control">
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12 text-center">
                              <input type="submit" name="Export" value="Export">
                            </div>
                          </div>

                          
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <!-- main-panel ends -->
        </div>
    </div>
</div>
<!-- page-body-wrapper ends -->
<!-- container-scroller -->

<?php echo $__env->make('backend.layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>

</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/report/index.blade.php ENDPATH**/ ?>