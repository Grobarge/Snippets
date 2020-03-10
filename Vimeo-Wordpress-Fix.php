// Override Vimeo show controls setting to always be enabled.


add_filter('ld_video_params', function($settings, $provider) {
if ( 'vimeo' === $provider ) {
// Show video player controls
$settings['controls'] = 1;
// Autostart video
//$settings['autoplay'] = 1;
};
// Always return $settings
return $settings;
}, 2, 10);