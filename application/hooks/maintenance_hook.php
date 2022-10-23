<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Check whether the site is offline or not.
 *
 */
class Maintenance_hook
{
    public function __construct(){
        log_message('debug','Accessing maintenance hook!');
    }
    
    public function offline_check(){
        if(file_exists(APPPATH.'config/config.php')){
            include(APPPATH.'config/config.php');
            
            /*if(isset($config['maintenance_mode']) && $config['maintenance_mode'] === TRUE){
                include(APPPATH.'views/maintenance.php');
                exit;
            }*/

            $current_ip_address = $_SERVER['REMOTE_ADDR'];
            $allowed_ip_addresses = $config['allowed_ip_addresses'];
            if(isset($config['maintenance_mode']) && $config['maintenance_mode'] === TRUE 
            && strpos($allowed_ip_addresses, $current_ip_address) === FALSE){
                include(APPPATH.'views/maintenance.php');
                exit;
            }
        }
    }
}