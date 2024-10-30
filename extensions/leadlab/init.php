<?php

use WMIP2C\Common\Enums\WordpressOptions;

function wmip2c_leadlab__pixel()
{
    $trackingToken = get_option(WordpressOptions::TRACKING_TOKEN);
    $trackingActive = (bool) get_option(WordpressOptions::TRACKING_ACTIVE);

    if (!!$trackingToken && $trackingActive) {
        ?>
<script type="text/javascript">
(function(d, s) {
    var l = d.createElement(s),
        e = d.getElementsByTagName(s)[0];
    l.async = true;
    l.type = 'text/javascript';
    l.src = 'https://c.leadlab.click/<?php echo esc_js($trackingToken); ?>.js';
    e.parentNode.insertBefore(l, e);
})(document, 'script');
</script>
<?php

        ?>
<script type="text/javascript">
(function(d, s) {
    var l = d.createElement(s),
        e = d.getElementsByTagName(s)[0];
    l.async = true;
    l.type = 'text/javascript';
    l.src = 'https://c.leadlab.click/consent.min.js';
    e.parentNode.insertBefore(l, e);
})(document, 'script');
</script>
<?php
}
}

add_action('wp_footer', 'wmip2c_leadlab__pixel');