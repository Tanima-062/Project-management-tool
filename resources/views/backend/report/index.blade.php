<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel report-page">
            <div class="content-wrapper" >
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <h3 class="users-list-title">Report</h3>
                <hr class="Dash-Line">
                <div class="row">
                  <div class="col-md-7 m-auto">
                    <div class="card">
                      <div class="card-body">
                        <form action="{{ route('report.export') }}" method="POST">
                          @csrf
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

                          {{-- <input type="date" name="start_date">
                          <input type="date" name="end_date">
                          <input type="submit" name="Export" value="Export"> --}}
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

@include('backend.layouts.scripts')

</body>

</html>
