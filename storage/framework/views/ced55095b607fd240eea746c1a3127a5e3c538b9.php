<script>    
function include(filename, onload) {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.src = filename;
    script.type = 'text/javascript';
    script.onload = script.onreadystatechange = function() {
        if (script.readyState) {
            if (script.readyState === 'complete' || script.readyState === 'loaded') {
                script.onreadystatechange = null;
                onload();
            }
        }
        else {
            onload();
        }
    };
    head.appendChild(script);
}

var ajax = {
    get:function(url,call_back){
        var request = new XMLHttpRequest();
        request.open("GET", url);
        request.onreadystatechange = function() {
            if(this.readyState === 4 && this.status === 200) {
                //console.log(this.responseText);
                return call_back(this.responseText);
            }
        };
        request.send();
    },
    post:function(url){
        var request = new XMLHttpRequest();
        request.open("POST", url);
        request.onreadystatechange = function() {
            if(this.readyState === 4 && this.status === 200) {
                console.log(this.responseText);
            }
        };
        request.send();
    }
}


include('https://bikroyit.com/assets/js/jStorage.js', function() {
    include('https://cdn.jsdelivr.net/npm/moment@2.24.0/moment.min.js',function(){
        let ad_var_name = 'bikroy-airtel-desktop';
        let bikroy_ad_data = $.jStorage.get(ad_var_name);
        let ad_id  = 1;
        let ad_visibility_limit = 200000;
        if(!bikroy_ad_data){
            let ad_data = {
                date:moment().format('Y-MM-DD'),
                ad_id:ad_id,
                no_of_show:  1,
            };
            $.jStorage.set(ad_var_name, ad_data,{TTL: 60000*60*12});
        }else{
            let ad_data = {
                date:moment().format('Y-MM-DD'),
                ad_id:ad_id,
                no_of_show: bikroy_ad_data.no_of_show +1,
            };
            $.jStorage.set(ad_var_name, ad_data,{TTL: 60000*60*12});
        }
        bikroy_ad_data = $.jStorage.get(ad_var_name);
        if(bikroy_ad_data.no_of_show > ad_visibility_limit){
            //$.jStorage.deleteKey(ad_var_name);
            console.log(bikroy_ad_data);
        }else{
            //config
            var base_url = "<?php echo e(asset('public/banners/roadblock_file.html')); ?>";
            //console.log(base_url);
            //var base_url = '';
            var mainBanner = 'index.html';					                        //main banner source
            var mainBannerWidth = '100%';									                        //main banner width
            var mainBannerHeight = '100%';									                        //main banner height

            var skinBannerHolder = window.parent.document.body;


            var closeButton = document.createElement('a');
            closeButton.href='#';
            closeButton.setAttribute('style',"display:none;display:block;width:28px;position:absolute;top:16px;right:7px")
            closeButton.id='close_button_parent';
            var closeImg = document.createElement('img');
            closeImg.src = 'https://bikroyit.com/assets/images/close.png';
            closeImg.style.width = '28px';
            closeButton.appendChild(closeImg);

            var logo = document.createElement('img');
            logo.src='https://bikroyit.com/assets/images/bikroy-logo.png';
            logo.setAttribute('style',"width:135px;top:18px;position:absolute;left:43%");

            var left_info_img = document.createElement('img');
            left_info_img.src="https://bikroyit.com/assets/images/left.png";
            left_info_img.setAttribute('style',"position:absolute;top:21px;left:7px;width:140px")

            var top_bar = document.createElement('div');
            top_bar.setAttribute('style',"width:100%;height:63px;position:fixed;left:0;top:0;background:#009877;z-index:10")
            top_bar.appendChild(left_info_img);
            top_bar.appendChild(logo);
            top_bar.appendChild(closeButton);

            var skinBaner = document.createElement('iframe');
            skinBaner.width = mainBannerWidth;
            skinBaner.height = mainBannerHeight;
            skinBaner.frameBorder = '0';
            skinBaner.scrolling = 'no';
            skinBaner.id = 'skinBaner';
            skinBaner.className = 'skinBaner';
            skinBaner.src = base_url;
            skinBaner.style.position = 'fixed';
            skinBaner.style.top = '0';
            skinBaner.style.marginTop = '10%';
            skinBaner.style.marginLeft = '15%';
            // skinBaner.style.backgroundColor = <?php echo json_encode($banner->background_color); ?>;
            skinBaner.style.zIndex = '9';
            console.log('ad live');
            closeButton.onclick = function(e){
                e.preventDefault();
                skinBaner.src ='';
                skinBaner.style.display = 'none';
                this.style.display = 'none';
                closeButton.remove();
                top_bar.remove();
                clearInterval(interval);
            };

            function closeIFrame(){
                console.log('Banner Closed');
                skinBaner.remove();
            }

            var sec = 1;
            var interval = setInterval(function () {
                sec++;
                console.log(<?php echo json_encode($banner->end_time); ?>);
                if(sec > <?php echo json_encode($banner->end_time); ?>){
                    document.getElementById("showHideIframe").style.display='';
                    skinBannerHolder.style.backgroundColor = '';
                    closeButton.click();
                    skinBaner.src ='';
                    skinBaner.style.display = 'none';
                    closeImg.remove();
                    closeIFrame();
                    clearInterval(interval);
                }else if(sec == <?php echo json_encode($banner->start_time); ?>){
                    document.getElementById("showHideIframe").style.display='none';
                    
                    skinBannerHolder.appendChild(skinBaner);
                    skinBannerHolder.appendChild(top_bar);

                    skinBannerHolder.style.backgroundColor = <?php echo json_encode($banner->background_color); ?>;
                }
            },1000);
        }



    });


});

</script>