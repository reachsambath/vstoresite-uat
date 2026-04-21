(function ( $ ) {
    "use strict";

    $(document).ready(function(){

        // ENTER
        $(document).on('mouseenter','.itwpt-tooltip',function(){

            // VARIABLE
            var _this = $(this);
            var _text = _this.data('tooltip-text');
            var _style = {
                'position': 'absolute',
                'transform': 'translate(-50%,-100%)',
                'top': '0',
                'left': '50%'
            };

            if(_this.hasClass('itwpt-tooltip-right')){
                _style.transform = 'translate(0,-50%)';
                _style.top = '50%';
                _style.left = '100%';
            }

            // CREATE POPUP
            _this.append('<div class="itwpt-tooltip-popup">' + _text + '</div>');
            _this.find('.itwpt-tooltip-popup').css(_style);

        });

        // LEAVE
        $(document).on('mouseleave','.itwpt-tooltip',function(){

            // REMOVE ELEMENTS
            $(this).find('.itwpt-tooltip-popup').remove();

        });

    });

})(jQuery);