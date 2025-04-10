jQuery(document).ready(function ($) {
  $('.copy-icon').on('click', function () {
    var $strong = $(this).find('strong');
    var code = $strong.text();

    navigator.clipboard.writeText(code).then(function () {
        $strong.text('Copied!');
        setTimeout(function () {
            $strong.text(code);
        }, 1500);
    });
  });


  // for popup 
  $('.copy-icon').on('click', function () {
    const code = $(this).find('.coupon-code').text();

    navigator.clipboard.writeText(code).then(() => {
      $(this).find('.coupon-code').text('Copied!');
      setTimeout(() => {
        $(this).find('.coupon-code').text(code);
      }, 1500);
    });
  });

    // click counting
    let clickedFlag = false; 
    if (!clickedFlag) {
      const elementsToTrack = [
          { selector: '.homepageBannerPopup', type: 'popup' },
          { selector: '.headcuoponWrapper', type: 'header-ticker' },
          { selector: '.homepage-banner', type: 'header-banner' }
      ];
  
      const handleClick = function (event) {
          if (!sessionStorage.getItem('promoBannerClicked')) {
              sessionStorage.setItem('promoBannerClicked', 'true');
  
              // Determine which element was clicked
              let bannerType = 'popup'; // default
              elementsToTrack.forEach(function (element) {
                  if ($(event.currentTarget).is(element.selector)) {
                      bannerType = element.type;
                  }
              });
  
              // Send AJAX call
              $.ajax({
                  url: promoBannerData.ajax_url,
                  method: 'GET',
                  data: {
                      action: 'track_banner_click',
                      type: bannerType
                  },
                  success: function (response) {
                      console.log(response);
                      
                  },
                  error: function (error) {
                      console.error(error);
                      alert('nothing happened');
                  }
              });
          }
  
          // Remove all click listeners
          elementsToTrack.forEach(function (element) {
              $(element.selector).off('click', handleClick);
          });
      };
  
      // Attach click listeners
      elementsToTrack.forEach(function (element) {
          $(element.selector).on('click', handleClick);
      });
    }
  
    // code ended

    $('.header-ticker').each(function () {
      var speed = $(this).data('speed');
      var animationType = $(this).data('animation');
      var $span = $(this).find('span');
  
      if ($span.length) {
        $span.attr('data-animation', animationType);
        $span.css('--ticker-speed', speed + 'ms');
      }
    });
            
    // Display popup
    $('#homepageBannerPopup').fadeIn();
    // $('body').addClass('popup-active');
    
    // Close popup
    $('.close-btn').on('click', function () {
        $('#homepageBannerPopup').fadeOut();
        $('body').removeClass('popup-active'); // Remove the class
    });

    // Close on clicking outside the popup
    $(window).on('click', function (e) {
        if ($(e.target).is('#homepageBannerPopup')) {
            $('#homepageBannerPopup').fadeOut();
            $('body').removeClass('popup-active'); // Remove the class
        }
    });
});