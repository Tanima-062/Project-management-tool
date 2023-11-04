@if ($errors->any())
    <div class="alert alert-danger">
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
@endif
@if(\Illuminate\Support\Facades\Session::has('success'))
    <div class="alert alert-success">
        <div>
            <p>{{\Illuminate\Support\Facades\Session::get('success')}}</p>
        </div>
    </div>
@endif


@if(\Illuminate\Support\Facades\Session::has('error'))
    <div class="alert alert-danger">
        <div>
            <p>{{\Illuminate\Support\Facades\Session::get('error')}}</p>
        </div>
    </div>
@endif

<script type="text/javascript">
    $(function () {
        $(".alert").delay(5000).slideUp(300);
    });
</script>


