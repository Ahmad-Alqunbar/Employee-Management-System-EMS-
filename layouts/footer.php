<?php ?>
</div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

<script>

$(document).ready(()=>{
  $('#open-sidebar').click(()=>{
      // add class active on #sidebar
      $('#sidebar').addClass('active');
      // show sidebar overlay
      $('#sidebar-overlay').removeClass('d-none');
   });
   $('#sidebar-overlay').click(function(){
      // add class active on #sidebar
      $('#sidebar').removeClass('active');
      // show sidebar overlay
      $(this).addClass('d-none');
   });
});


// Reload the page every 10 minutes (600,000 milliseconds)
setInterval(function() {
    // Use jQuery to load the current page content without a full refresh
    $.ajax({
        url: window.location.href,
        type: 'GET',
        success: function(data) {
            // Replace the current page content with the updated content
            $('body').html(data);
        },
        error: function(error) {
            console.log('Error reloading page:', error);
        }
    });
}, 100000); // 600,000 milliseconds = 10 minutes
</script>

</body>

</html>