<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author benedikt
 *
 *
 */
class twitter_api_test extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('twitter_api');
        
        // If user is not yet logged in, (re)authorize
        if ($this->twitter_api->logged_in() === false)
        {
            $this->twitter_api->set_callback(site_url('tweet_test/login'));
            $this->twitter_api->login();
        }
        else
        {
            // Use this to store your Tokens in a database or so together with the userdata for later reuse
            // $this->twitter_api->get_token();
        }
    }
    
    function index()
    {
        echo 'hi there';
    }
    
    function auth()
    {
        $tokens = $this->twitter_api->get_token();
        
        // $user = $this->twitter_api->call('get', 'account/verify_credentiaaaaaaaaals');
        // 
        // Will throw an error with a stacktrace.
        
        
        $data = array_values($tokens);
        // put token info from database in expected format
        $tokenKeys = array('tw_token', 'tw_secret', 'tw_screenname', 'tw_userid');
        $tokenVals = $data;
        $token = array_combine($tokenKeys, $tokenVals);
        $this->twitter_api->set_token($token);
        
        $user = $this->twitter_api->call('get', 'account/verify_credentials');
        var_dump($user);
        
        $friendship = $this->twitter_api->call('get', 'friendships/show', array('source_screen_name' => $user->screen_name, 'target_screen_name' => 'mastacheata'));
        var_dump($friendship);
        
        if ( $friendship->relationship->target->following === FALSE )
        {
            $this->twitter_api->call('post', 'friendships/create', array('screen_name' => 'mastacheata', 'follow' => TRUE));
        }
        
        //$this->twitter_api->call('post', 'statuses/update', array('status' => 'Testing #CodeIgniter Twitter library by @elliothaughin - http://bit.ly/grHmua'));
        
        $options = array(
                    'count' => 10,
                    'page' 	=> 2,
                    'include_entities' => 1
        );
        
        $timeline = $this->twitter_api->call('get', 'statuses/home_timeline');
        
        var_dump($timeline);
    }
}