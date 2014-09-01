	<div class="content-admin">
		<div class="head-area heading-active">
			<span class="title"><?php echo $title_page; ?></span>
			<span class="pull-right"><a href="#" class="link-chevron"><i class="icon-chevron-up"></i></a></span>
		</div>
		
		<div class="body-area">
			<div class="body-area-padding">
				<!--
				<iframe id="frameId" width="100%" scrolling="no" allowtransparency="true" frameborder="0" class="autoHeight" src="<?php echo $url;?>" style="height:900px; border:0px solid #cccccc;"></iframe>	
				-->
				
				<iframe id="frameId" width="100%" scrolling="no" allowtransparency="true" frameborder="0" class="iframe" src="<?php echo $url;?>" style="height:900px; border:0px solid #cccccc;"></iframe>	
			</div>
		</div>
	</div>
	
	
	<script type='text/javascript'>
    
    $(function(){
    
        var iFrames = $('iframe');
      
    	function iResize() {
    	
    		for (var i = 0, j = iFrames.length; i < j; i++) {
    		  iFrames[i].style.height = iFrames[i].contentWindow.document.body.offsetHeight + 'px';}
    	    }
    	    
        	if ($.browser.safari || $.browser.opera) { 
        	
        	   iFrames.load(function(){
        	       setTimeout(iResize, 0);
               });
            
        	   for (var i = 0, j = iFrames.length; i < j; i++) {
        			var iSource = iFrames[i].src;
        			iFrames[i].src = '';
        			iFrames[i].src = iSource;
               }
               
        	} else {
        	   iFrames.load(function() { 
        	       this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
        	   });
        	}
        
        });

</script>