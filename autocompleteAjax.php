<?php
if(!isset($wpdb))
{
    require_once('../../../wp-config.php');
    require_once('../../../wp-load.php');
    require_once('../../../wp-includes/wp-db.php');
}
global $wpdb;
$sql="select id, display_name, user_email from wp_users where display_name like '%".$_REQUEST['term']."%' or user_email like '%".$_REQUEST['term']."%'"; 
$userinfo = $wpdb->get_results($sql, OBJECT);
foreach ($userinfo as $info){
$thelabel = $info->display_name ."(". $info->user_email .")";
$results[]=array('id'=>$info->user_email,'label' =>$thelabel);
}
echo json_encode($results);
?>