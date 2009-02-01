<?php
class SourceUpdate
{
	function activated() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$check= $wpdb->get_results( "SELECT updates FROM $table" );
		return $check;
	}
	
	function select() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$newest= $wpdb->get_results( "SELECT * FROM $table WHERE updates = 1" );
		return $newest;
	}
	
	function display() {
		require_once('simplepie.php');
		$feed= $this->select();
        
        if( preg_match('/twitter\.com/',$feed[0]->feed_url) == true ) {
            $istwitter = 1;
        }
        
		$feed_path= $feed[0]->feed_url;
		$feed= new SimplePie( (string) $feed_path, ABSPATH . (string) 'wp-content/uploads/activity' );

		SourceAdmin::clean_dir();

		$feed->handle_content_type();
		if( $feed->data ) {
			foreach( $feed->get_items() as $entry ) {
				$name= $stream->title;
				$update[]['name']= (string) $name;
				$update[]['title']= $entry->get_title();
				$update[]['link']= $entry->get_permalink();
				$update[]['date']= strtotime( substr( $entry->get_date(), 0, 25 ) );
			}
			
            if($update == null && $istwitter == 1) {
                return "Twitter is experiencing issues, please try again later.";
            }
                $return= array_slice( $update, 0, 5);	
            
            $return[1]['title'] = preg_replace('((?:\S)+://\S+[[:alnum:]]/?)', '<a href="\0">\0</a>', $return[1]['title']);
          
            if( $istwitter == 1 ) {
                $return[1]['title'] = preg_replace('/(@)([A-Za-z0-9_-]+)/', '<a href="http://twitter.com/\2">\0</a>', $return[1]['title']);
            }
            
			return substr($return[1]['title'], 0, 1000) . ' &mdash; <a href="' . $return[2]['link'] . '" title="">' . date( 'g:ia', $return[3]['date'] ) . '</a>';
		} else {
			return 'Thanks for exploring my world! Can you believe this avatar is talking to you?';
		}
	}
}
?>