<?php
	class Twitter_shortcode{
	  public $tweets = array();
	  public function __construct($user, $limit = 5)
	  {
		$url = 'http://twitter.com/statuses/user_timeline/' . $user . '.rss';

		$ch = curl_init();
		
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$content = curl_exec($ch);
		
		curl_close($ch);

		$doc = new DOMDocument();
		$doc->loadXML($content);
		
		$num_tweet = 0;
		foreach ($doc->getElementsByTagName('item') as $node){
			$tweet = new stdClass();

			$tweet->title = $node->getElementsByTagName('title')->item(0)->nodeValue;
			$tweet->desc = preg_replace('/^\w+:/i','',$node->getElementsByTagName('description')->item(0)->nodeValue);
			$tweet->link = $node->getElementsByTagName('link')->item(0)->nodeValue;
			$tweet->date = $node->getElementsByTagName('pubDate')->item(0)->nodeValue;

			array_push($this->tweets, $tweet);
			
			$num_tweet++;
			if( $num_tweet == $limit ){ break; }
		
		}	  
		
	  }
	  public function getTweets() { return $this->tweets; }
	}
	
	function twitter_feed_old($atts, $content = null){

		extract(shortcode_atts(array( "user"  => '', "limit"  => "5", "width"=> '350'), $atts));

		$feed = new Twitter_shortcode($user , $limit);
		$tweets = $feed->getTweets();
		
		$my_feed = '<div class="twitter-shortcode-wrapper">';
		$my_feed = $my_feed . '<div class="jcarousellite twitter"><ul class="twitter-shortcode">';
		foreach ($tweets as $tweet) {

			$my_feed = $my_feed . '<li style="width: ' . $width . 'px;">'. linkify_tweet($tweet->desc) .' by <a href="http://twitter.com/'. $user .'">'. $user .'</a></li>';

		}
		$my_feed = $my_feed . '</ul></div>';
		$my_feed = $my_feed . '<div class="jcarousellite-nav twitter"><div class="prev"></div><div class="next"></div></div>';
		$my_feed = $my_feed . '</div>';
		
		return $my_feed;
	}
 
    function linkify_tweet($v){
        $v = ' ' . $v;
 
        $v = preg_replace('/(^|\s)@(\w+)/', '\1@<a href="http://www.twitter.com/\2">\2</a>', $v);
        $v = preg_replace('/(^|\s)#(\w+)/', '\1#<a href="http://search.twitter.com/search?q=%23\2">\2</a>', $v);
 
        $v = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" >\\2</a>'", $v);
        $v = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" >\\2</a>'", $v);
        $v = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $v);
 
        return trim($v);
    }	
	
	add_shortcode("twitter", "twitter_feed");	
	function twitter_feed($atts, $content = null){
	
		extract(shortcode_atts(array( "user"  => '', "limit"  => "5", "width"=> '350'), $atts));
		
		$twitter_string = '<div class="twitter-shortcode-wrapper">'; 
		$twitter_string = $twitter_string . '<div id="gdl-twitter-shortcode" class="jcarousellite twitter">';
		$twitter_string = $twitter_string . '<ul class="twitter-shortcode"><ul>';
		$twitter_string = $twitter_string . '</div>'; // gdl-twitter-shortcode
		$twitter_string = $twitter_string . '<div class="jcarousellite-nav twitter"><div class="prev"></div><div class="next"></div></div>';
		$twitter_string = $twitter_string . '</div>'; // twitter-shortcode-wrapper

		?>
		<script type="text/javascript">
			
			function gdl_twitter_callback(twitters){			
				var statusHTML = '';
				for (var i=0; i<twitters.length; i++){
					var username = twitters[i].user.screen_name;
					var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
						return '<a href="'+url+'">'+url+'</a>';
					}).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
						return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
					});
					statusHTML = statusHTML + '<li style="width: <?php echo $width; ?>px;"><span>'+status+' by <a href="http://twitter.com/'+username+'">'+username+'</a></span></li>';
				}
				
				jQuery(document).ready(function(){
					var twitter_shortcode_wrapper = jQuery('#gdl-twitter-shortcode').children('ul');
					twitter_shortcode_wrapper.html(statusHTML);
					twitter_shortcode_wrapper.each(function(){
						var fetch_num = jQuery(this).children().length;
						jQuery(this).parent(".columns, .column").css('overflow', 'hidden');
						if( fetch_num > 0 ){ 
							twitter_shortcode_wrapper.cycle({ fx: 'fade', speed:  'fast', timeout: 7000, 
								next:   '.jcarousellite-nav.twitter .next',  prev:   '.jcarousellite-nav.twitter .prev' });
						}
					});				
				});				
			}
		</script>
		<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $user;?>.json?callback=gdl_twitter_callback&count=<?php echo $limit;?>"></script>

		<?php
		
		return $twitter_string;
	}
	
?>