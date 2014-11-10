<?php
/**
 * @version 1.2
 * @package dinke.net
 * @copyright &copy; 2008 Dinke.net
 * @author Dragan Dinic <dragan@dinke.net>
 */
class CRefactorCURL
{
	/**
	 * Curl handler
	 * @access private
	 * @var resource
	 */
	var $ch ;

	/**
	 * set debug to true in order to get usefull output
	 * @access private
	 * @var string
	 */
	var $debug = false;

	/**
	 * Contain last error message if error occured
	 * @access private
	 * @var string
	 */
	var $error_msg;


	/**
	 * CRefactorCURL constructor
	 * @param boolean debug
	 * @access public
	 */
	function CRefactorCURL($debug = false)
	{
		$this->debug = $debug;
		$this->init();
	}
	
	/**
	 * Init Curl session	 
	 * @access public
	 */
	function init()
	{
		// initialize curl handle
		$this->ch = curl_init();

		//set various options

		//set error in case http return code bigger than 300
		curl_setopt($this->ch, CURLOPT_FAILONERROR, true);

		// allow redirects
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		// use gzip if possible
		curl_setopt($this->ch,CURLOPT_ENCODING , 'gzip, deflate');

		// do not veryfy ssl
		// this is important for windows
		// as well for being able to access pages with non valid cert
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
	}

	/**
	 * Set username/pass for basic http auth
	 * @param string user
	 * @param string pass
	 * @access public
	 */
	function set_credentials($username,$password)
	{
		curl_setopt($this->ch, CURLOPT_USERPWD, "$username:$password");
	}

	/**
	 * Set referrer
	 * @param string referrer url 
	 * @access public
	 */
	function set_referrer($referrer_url)
	{
		curl_setopt($this->ch, CURLOPT_REFERER, $referrer_url);
	}

	/**
	 * Set client's useragent
	 * @param string user agent
	 * @access public
	 */
	function set_user_agent($useragent)
	{
		curl_setopt($this->ch, CURLOPT_USERAGENT, $useragent);
	}
    
    /**
     * Set header
     * @param array header
     * @access public
     */
    function set_header($header)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
    }

	/**
	 * Set to receive output headers in all output functions
	 * @param boolean true to include all response headers with output, false otherwise
	 * @access public
	 */
	function include_response_headers($value)
	{
		curl_setopt($this->ch, CURLOPT_HEADER, $value);
	}

    /**
	 * set_allow_redirects
	 * @param boolean true to include all response headers with output, false otherwise
	 * @access public
	 */
	function set_allow_redirects($value)
	{
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $value);
	}

	/**
	 * Set proxy to use for each curl request
	 * @param string proxy
     * @param int type 3 is http proxy, 4 is socks4 5 is socks5 (default 5)
	 * @access public
	 */
	function set_proxy($proxy, $type=5)
	{
		curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
        if($type == 5) {
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }
	}
	/**
	 * Send post data to target URL	 
	 * return data returned from url or false if error occured
	 * @param string url
	 * @param mixed post data (assoc array ie. $foo['post_var_name'] = $value or as string like var=val1&var2=val2)
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 10)
	 * @return string data
	 * @access public
	 */
	function send_post_data($url, $postdata, $ip=null, $timeout=10,$put=false)
	{
		//set various curl options first
	
		// set url to post to
	
		curl_setopt($this->ch, CURLOPT_URL,$url);
	
		// return into a variable rather than displaying it
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);
	
		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}
	
		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
	
		//set method to post
		curl_setopt($this->ch, CURLOPT_POST, true);
		if($put==true)
		{
		curl_setopt($this->ch, CURLOPT_HEADER, true);
		curl_setopt($this->ch, CURLOPT_PUT, true);
		}
		//generate post string
		$post_array = array();
		if(is_array($postdata))
		{
			foreach($postdata as $key=>$value)
			{
			$post_array[] = urlencode($key) . "=" . urlencode($value);
			}
	
			$post_string = implode("&",$post_array);
	
					if($this->debug)
					{
					echo "Url: $url\nPost String: $post_string\n";
					}
					}
					else
					{
					$post_string = $postdata;
					}
	
					// set post string
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);
	
	
					//and finally send curl request
					$result = curl_exec($this->ch);
	
					if(curl_errno($this->ch))
					{
						if($this->debug)
						{
						echo "Error Occured in Curl\n";
						echo "Error number: " .curl_errno($this->ch) ."\n";
						echo "Error message: " .curl_error($this->ch)."\n";
					}
	
					return false;
		}
		else
		{
		return $result;
			}
		}

	/**
	 * fetch data from target URL	 
	 * return data returned from url or false if error occured
	 * @param string url	 
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 5)
	 * @return string data
	 * @access public
	 */
	function fetch_url($url, $ip=null, $timeout=5, $nobody=false)
	{
		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL,$url);

		//set method to get
		curl_setopt($this->ch, CURLOPT_HTTPGET,true);

		// return into a variable rather than displaying it
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);
        
        //Nobody
        if ($nobody) curl_setopt($this->ch, CURLOPT_NOBODY, true);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}

		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        
		//and finally send curl request
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Fetch data from target URL
	 * and store it directly to file	 	 
	 * @param string url	 
	 * @param resource value stream resource(ie. fopen)
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 5)
	 * @return boolean true on success false othervise
	 * @access public
	 */
	function fetch_into_file($url, $fp, $ip=null, $timeout=5)
	{
		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL,$url);

		//set method to get
		curl_setopt($this->ch, CURLOPT_HTTPGET, true);

		// store data into file rather than displaying it
		curl_setopt($this->ch, CURLOPT_FILE, $fp);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch, CURLOPT_INTERFACE, $ip);
		}

		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		//and finally send curl request
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Send multipart post data to the target URL	 
	 * return data returned from url or false if error occured
	 * (contribution by vule nikolic, vule@dinke.net)
	 * @param string url
	 * @param array assoc post data array ie. $foo['post_var_name'] = $value
	 * @param array assoc $file_field_array, contains file_field name = value - path pairs
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 30 sec)
	 * @return string data
	 * @access public
	 */
	function send_multipart_post_data($url, $postdata, $file_field_array=array(), $ip=null, $timeout=30)
	{
		//set various curl options first

		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL, $url);

		// return into a variable rather than displaying it
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}

		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		//set method to post
		curl_setopt($this->ch, CURLOPT_POST, true);

		// disable Expect header
		// hack to make it working
		$headers = array("Expect: ");
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);

		// initialize result post array
		$result_post = array();

		//generate post string
		$post_array = array();
		$post_string_array = array();
		if(!is_array($postdata))
		{
			return false;
		}

		foreach($postdata as $key=>$value)
		{
			$post_array[$key] = $value;
			$post_string_array[] = urlencode($key)."=".urlencode($value);
		}

		$post_string = implode("&",$post_string_array);


		if($this->debug)
		{
			echo "Post String: $post_string\n";
		}

		// set post string
		//curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);


		// set multipart form data - file array field-value pairs
		if(!empty($file_field_array))
		{
			foreach($file_field_array as $var_name => $var_value)
			{
				if(strpos(PHP_OS, "WIN") !== false) $var_value = str_replace("/", "\\", $var_value); // win hack
				$file_field_array[$var_name] = "@".$var_value;
			}
		}

		// set post data
		$result_post = array_merge($post_array, $file_field_array);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $result_post);


		//and finally send curl request
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Set file location where cookie data will be stored and send on each new request
	 * @param string absolute path to cookie file (must be in writable dir)
	 * @access public
	 */
	function store_cookies($cookie_file)
	{
		// use cookies on each request (cookies stored in $cookie_file)
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
	}
	
	/**
	 * Set custom cookie
	 * @param string cookie
	 * @access public
	 */
	function set_cookie($cookie)
	{		
		curl_setopt ($this->ch, CURLOPT_COOKIE, $cookie);
	}

	/**
	 * Get last URL info 
	 * usefull when original url was redirected to other location	
	 * @access public
	 * @return string url
	 */
	function get_effective_url()
	{
		return curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
	}

	/**
	 * Get http response code	 
	 * @access public
	 * @return int
	 */
	function get_http_response_code()
	{
		return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
	}

	/**
	 * Return last error message and error number
	 * @return string error msg
	 * @access public
	 */
	function get_error_msg()
	{
		$err = "Error number: " .@curl_errno($this->ch) ."\n";
		$err .="Error message: " .@curl_error($this->ch)."\n";

		return $err;
	}
	
	/**
	 * Close curl session and free resource
	 * Usually no need to call this function directly
	 * in case you do you have to call init() to recreate curl
	 * @access public
	 */
	function close()
	{
		//close curl session and free up resources
		curl_close($this->ch);
	}
}
?>