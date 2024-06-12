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

  

// Reload the page every 2 minutes 
setInterval(function() {
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

  let inactivityTimeout;

  function resetInactivityTimeout() {
    clearTimeout(inactivityTimeout);

    inactivityTimeout = setTimeout(function () {
      // Redirect to logout page or perform logout action
      window.location.href = 'logout.php';
    }, 1 * 60 * 60 * 1000); // 2 hours in milliseconds
  }
  // Attach this event to any activity on the page (mousemove, keydown)
  document.addEventListener('mousemove', resetInactivityTimeout);
  document.addEventListener('keydown', resetInactivityTimeout);
  // Start the inactivity timeout on page load
  resetInactivityTimeout();
</script>
</body>





</html>
