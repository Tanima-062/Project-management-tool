<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="background-color:#009877;">
        <a class="navbar-brand brand-logo" href="<?php echo e(route('dashboard')); ?>"><img src="<?php echo URL::to('public/backend/assets/img/logo_stl.png'); ?>" class="mr-2 img-fluid" alt="logo"/></a>
        <a class="navbar-brand nav-logo" href="<?php echo e(route('dashboard')); ?>"><img src="<?php echo URL::to('public/backend/assets/img/stl_icon.png'); ?>" class="mr-2 img-fluid" alt="logo"/></a>
    </div>
   

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="background-color:#009877;">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu" style="color:#ffffff;"></span>
        </button>
        <h4 class="Task-Management-Platform Task-Management-Platform-big-screen" style="color:#ffffff;">Bikroy Task Management Platform</h4>
        <h4 class="Task-Management-Platform Task-Management-Platform-short-screen pl-2" style="color:#ffffff;">TMP</h4>
        <ul class="navbar-nav navbar-nav-right">

           
            <div class="Timestamp-BG"><span id="date"></span><span id="clock"><span></div>
            <li class="nav-item header-create-button">
                <a class="btn-hover" href="<?php echo e(route('tasks.create')); ?>">
                    <button class="Rectangle-btn">
                        <i class="far fa-plus-square"></i>
                        NEW TASK
                    </button>
                </a>
                <a class="btn-hover" href="<?php echo e(route('myRequestTasks.create')); ?>">
                    <button class="Rectangle-btn" style="width:150px;">
                        
                        <i class="far fa-plus-square"></i>
                        PROPOSE TASK
                    </button>
                </a>
            </li>

            <li class="dropdown-notifications nav-item dropdown">
                <!-- <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="fa fa-bell" onclick="reset()"></i>
                    <span id="notification-icon"></span>
                    <i data-count="0" class="glyphicon glyphicon-bell notification-icon"></i>
                </a> -->
                <div class="dropdown-menu notification-menu dropdown-container dropdown-menu-right navbar-dropdown notification-top-section" aria-labelledby="notificationDropdown">
                    <div class="dropdown-container">
                        <div class="dropdown-toolbar">
                          <div class="dropdown-toolbar-actions">
                            <a id="clear">Clear</a>
                          </div>
                          <p class="dropdown-toolbar-title">Notifications (<span class="notif-count">0</span>)</p>
                        </div>
                        <hr>
                        <div class="row">
                            
                        </div>
                        <!-- <div class="dropdown-footer text-center">
                          <a href="#">View All</a>
                        </div> -->
                    </div>
                </div>
            </li>

            <a class="nav-item nav-profile" href="<?php echo e(route('users.show',\Illuminate\Support\Facades\Auth::user()['id'])); ?>">
                <?php if(!empty(\Illuminate\Support\Facades\Auth::user()['image']) && (file_exists(public_path('backend/uploads/profile_images/'.\Illuminate\Support\Facades\Auth::user()['id'].'/'.\Illuminate\Support\Facades\Auth::user()['image'])))): ?>
                    <img src="{!!URL::to('public/backend/uploads/profile_images/'.\Illuminate\Support\Facades\Auth::user()['id'].'/'.\Illuminate\Support\Facades\Auth::user()['image'])!}}" alt="profile"/>
                <?php else: ?>
                    <!-- <img class="profile-picture" src="{!!URL::to('public/backend/uploads/profile_images/user-svgrepo-com.svg')!}}" alt="profile" height="150px;" width="150px"/>     -->
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px"
                        height="25px" viewBox="0 0 25 25" style="overflow:visible;enable-background:new 0 0 25 25;" xml:space="preserve">
                    <style type="text/css">
                        .st0{fill:#FFFFFF;}
                    </style>
                    <defs>
                    </defs>
                    <path class="st0" d="M12.5,0C5.6,0,0,5.6,0,12.5S5.6,25,12.5,25S25,19.4,25,12.5S19.4,0,12.5,0z M12.5,3.7c2.1,0,3.8,1.7,3.8,3.8
                        s-1.7,3.8-3.8,3.8S8.7,9.6,8.7,7.5S10.4,3.7,12.5,3.7z M12.5,21.5c-3.1,0-5.9-1.6-7.5-4c0-2.5,5-3.9,7.5-3.9S20,15,20,17.5
                        C18.4,19.9,15.6,21.5,12.5,21.5z"/>
                    </svg>
                <?php endif; ?>
            </a>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>

<script>
    $(document).ready(function(){
        setInterval('updateClock()', 1000);
        getTodayDate();
    });

function getTodayDate(){
    const monthNames = ["Jan", "Feb", "March", "April", "May", "June",
          "July", "August", "Sept", "Oct", "Nov", "Dec"
    ];
    const d = new Date();
    const date = d.getDate() + " " +monthNames[d.getMonth()] + ', '+ d.getFullYear()+ ' ';
    $("#date").html(date);
}

function updateClock (){
 	var currentTime = new Date ( );
  	var currentHours = currentTime.getHours ( );
  	var currentMinutes = currentTime.getMinutes ( );
  	var currentSeconds = currentTime.getSeconds ( );

  	// Pad the minutes and seconds with leading zeros, if required
  	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  	// Choose either "AM" or "PM" as appropriate
  	var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  	// Convert the hours component to 12-hour format if needed
  	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  	// Convert an hours component of "0" to "12"
  	currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  	// Compose the string for display
  	var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;


   	$("#clock").html(currentTimeString);
 }
</script>

<script type="text/javascript">

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var notificationsWrapper = $('.dropdown-notifications');
    var notificationsCount = 0;
    var notifications = notificationsWrapper.find('div.dropdown-menu');
    var notificationCounterWrapper =  notifications;
    var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
    var notificationsCountElem = notificationsToggle.find('i[data-count]');
    var notificationsCount     = parseInt(notificationsCountElem.data('count'));
    var notifications = notificationsWrapper.find('div.preview-list');

    let notificationIcon = document.getElementById("notification-icon");
    let unShowedNotification = 0;

    if(notificationsCount <= 0) {
        notificationsWrapper.hide();
    }

    var pusher = new Pusher('bc8e1acc34c6ea62c726', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('tmp_public');
    channel.bind('general_notification', function(data) {
        notificationsCount += 1;
        unShowedNotification += 1;
        console.log("data value:", data);
        var existingNotifications = notifications.html();

        var newNotificationHtml = `
            <a href="<?php echo e(route('tasks.show',1)); ?>">
                <div class="media List-Item-BG-White media">
                    <div class="media-left count-indicator" style="padding-left: 10px;">
                        <i class="icon-bell mx-0"></i>
                    </div>
                    <div class="media-body" style="padding-left: 10px;"><p style="color: #ec008c;">
                        <strong class="notification-title">`+data.message+`</strong>
                    </div>
                </div>
            </a> 
        `;
        
        notifications.html(newNotificationHtml + existingNotifications);
        notificationsCountElem.attr('data-count', notificationsCount);
        notificationCounterWrapper.find('.notif-count').text(notificationsCount);
        notificationsWrapper.show();

        notificationIcon.style.display = "inline";
        notificationIcon.innerHTML = unShowedNotification;
    });

    channel.bind(JSON.parse("<?php echo e(json_encode(Auth::user()->id)); ?>")+"_user_notification", function(data) {
        console.log("work");
        notificationsCount += 1;
        unShowedNotification += 1;
        var existingNotifications = notifications.html();

        var newNotificationHtml = `
            <a href="<?php echo e(route('tasks.show',1)); ?>"> 
                <div class="media List-Item-BG-White media">
                    <div class="media-left count-indicator" style="padding-left: 10px;">
                        <i class="icon-bell mx-0"></i>
                    </div>
                    <div class="media-body" style="padding-left: 10px;"><p style="color: #ec008c;">
                        <strong class="notification-title">`+data.message+`</strong>
                    </div>
                </div>
            </a>
        `;
        notifications.html(newNotificationHtml + existingNotifications);
        notificationsCountElem.attr('data-count', notificationsCount);
        notificationCounterWrapper.find('.notif-count').text(notificationsCount);
        notificationsWrapper.show();

        notificationIcon.style.display = "inline";
        notificationIcon.innerHTML = unShowedNotification;
    });

    function reset(){
        unShowedNotification = 0;
        notificationIcon.style.display = "none";
    }

    $(".navbar-toggler").click(function(){
        $(".nav-logo").toggle();
    });

  $('#clear').click(function(){
    event.preventDefault();
    if($('.notification-preview-list-div').children().length > 0){
        swal({
                title: "Are you sure?",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "YES",
                cancelButtonText: "NO",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function() {
                $.ajax({
                    type:'GET',
                    url:'/clear/notification',
                    success:function(data) {
                        $('.notification-preview-list-div').html('');
                        $('.notif-count').html('0');
                    }
                });
            }
        );
    }
  });

    function changeOfficeStatus(This){
        var status = 'office';
        if(This.is(':checked') == false){
            status = 'wfh';
        }     
        $.ajax({
            type: 'GET',
            url: '/change-office-status',
            data:{status:status},
            success:function(data){
                $("#alert-show").html("<div class='alert alert-success'><div><p>"+data.msg+"</p></div></div>");
                $("#alert-show").show().delay(5000).fadeOut();
            }
        });
    }
</script>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/layouts/header.blade.php ENDPATH**/ ?>