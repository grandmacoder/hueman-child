<?php
if(!isset($wpdb))
{
    require_once('../../../wp-config.php');
    require_once('../../../wp-load.php');
    require_once('../../../wp-includes/wp-db.php');
}
global $wpdb;
add_user_meta(23, 'completed_survey', 1); 

?>