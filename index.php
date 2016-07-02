<?php
/**
 * @package DanceHub_Schedule
 * @version 1.6
 */
/*
Plugin Name: Dancehub Schedule for Wordpress
Plugin URI: http://dancehub.com
Description: Dancehub Schedule Plugin
Author: Clark Alesna
Version: 1.0
Author URI: http://twitter.com/clarkalesna
*/

add_action('admin_menu', 'setup_menu');
add_action( 'admin_init', 'register_settings' );
add_action( 'init', 'register_shortcodes');



function register_settings()
{
	//register our settings
	register_setting( 'dh_schedule_options', 'public_key' );
	register_setting( 'dh_schedule_options', 'private_key' );
	register_setting( 'dh_schedule_options', 'endpoint' );
	register_setting( 'dh_schedule_options', 'auth_token' );
}

function setup_menu()
{
        add_menu_page( 'Dancehub Schedule Settings', 'DH Schedule', 'manage_options', 'dh-settings', 'main_setting' );
}
 
function main_setting()
{

        if(isset($_GET['action']) && $_GET['action'] == 'save')
        {
                update_option('public_key', $_GET['public_key']);
                update_option('secret_key', $_GET['secret_key']);
                update_option('endpoint', $_GET['endpoint']);
                update_option('auth_token', $_GET['auth_token']);
        }

        if(empty(get_option('endpoint')))
                update_option('endpoint', 'http://dancehub.newspick.net/api/');
        include('views/main_setting.php');
}

function register_shortcodes()
{
        add_shortcode('dh-schedule', 'render_schedule');
}

function render_schedule()
{
        if(!empty(get_option('auth_token')))
        {
                // create curl resource
                $ch = curl_init();

                // set url
                curl_setopt($ch, CURLOPT_URL, get_option('endpoint') . "me");

                //return the transfer as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                
                curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/storage/cookie.txt');
                curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/storage/cookie.txt');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'ApiKey: '.get_option('auth_token')
                ));

                // $output contains the output string
                $output = curl_exec($ch);

                // close curl resource to free up system resources
                curl_close($ch);
                $meData = json_decode($output);

                // create curl resource
                $ch = curl_init();

                // set url
                curl_setopt($ch, CURLOPT_URL, get_option('endpoint') . "schedule/user/".$meData->user_id);
                //return the transfer as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'ApiKey: '.get_option('auth_token')
                ));

                curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/storage/cookie.txt');
                curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/storage/cookie.txt');
                // $output contains the output string
                $output = curl_exec($ch);

                // close curl resource to free up system resources
                curl_close($ch);
                $scheduleData = json_decode($output);
                $days = [];

                foreach($scheduleData->data as $schedule)
                {
                        if(!isset($days[$schedule->week_day]))
                                $days[$schedule->week_day] = [];
                        
                        array_push($days[$schedule->week_day],$schedule);
                }
                $result = "<table>";
                $result .= "<tr>";
                $result .= "<th>Class</th>";
                $result .= "<th>Time</th>";
                $result .= "<th>Start</th>";
                $result .= "<th>End</th>";
                $result .= "</tr>";
                foreach($days as $key => $day)
                { 
                        $result .= "<tr>";
                        $result .= "<th colspan='4'>". $key ."</th>";
                        $result .= "</tr>";
                        foreach($day as $schedule)
                        {
                                $result .= "<tr>";
                                $result .= "<td>". $schedule->dclass_title ."</td>";
                                $result .= "<td>". $schedule->start_time ."-". $schedule->end_time ."</td>";
                                $result .= "<td>". date_format(date_create($schedule->start_date),"M d, Y") ."</td>";
                                $result .= "<td>". date_format(date_create($schedule->end_date),"M d, Y") ."</td>";
                                $result .= "</tr>";
                        }
                }
                $result .= "</table>";
                return $result;
        }
        else 
        {
                $result = "<table>";
                $result .= "<tr>";
                $result .= "<th>Class</th>";
                $result .= "<th>Time</th>";
                $result .= "<th>Start</th>";
                $result .= "<th>End</th>";
                $result .= "</tr>";
                $result .= "<tr><td colspan='4'>You must configure the Dancehub Schedule Plugin</td></tr>";
                $result .= "</table>";
                return $result;
        }
       
}

?>