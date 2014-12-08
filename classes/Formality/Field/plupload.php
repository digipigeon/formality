<?php
 
class Formality_Field_Plupload extends Formality_Field{

	public function pull(){
		$r = Array();
		$i = 0;
		while (isset($_POST["{$this->config["id"]}_{$i}_name"])) {
			$r[] = preg_replace('/[^\w\._]+/', '_', $_POST["{$this->config["id"]}_{$i}_name"]);
			$i++;
		}
		$this->config['value'] = $r;
	}

	public function __construct($config = Array(), &$parent = false){
		parent::__construct($config, $parent);
	}	

	public function render($label=true, $container=true){
		$this->check_php_max_upload_size();
		if (isset($_POST['chunks'])){
			$this->process_upload();
		}

		if (isset($this->config['include']['js'])){
			foreach($this->config['include']['js'] as $js){
				HTML::add_file($js);
			}
		}
		if (isset($this->config['include']['css'])){
			foreach($this->config['include']['css'] as $js){
				HTML::add_file($js,'css');
			}
		}
		HTML::add_js($this->js());
		return '<div id="' . $this->config['id'] . '"><p>You browser doesnt have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p></div>';
	}
	
	private function check_php_max_upload_size(){
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
		if ($upload_mb < $this->config['max.file.size']){
			throw new Exception("Max file allowed by PHP ($upload_mb MB) is less than set in config ({$this->config['max.file.size']})");
		}
	}

	private function js(){
		$runtimes = Array('html5');
		if (isset($this->config['include']['flash'])) array_unshift($runtimes, 'flash');
		$runtimes[] = 'gears';		
		if (isset($this->config['include']['silverlight'])) array_push($runtimes, 'silverlight');
		$runtimes[] = 'browserplus';
		$runtimes[] = 'html4';
		$json = Array(
			'drop_element'	=> $this->config['id'],
//			'runtimes'		=> implode(',', $runtimes),
			'url'			=> $this->parent->config['action'],
			'max_file_size'	=> $this->config['max.file.size'] . 'mb',
			'chunk_size'	=> $this->config['chunk.size'] . 'mb',
			'resize'		=> Array(
				'width'		=> 320,
				'height'	=> 240,
				'quality'	=> 90,
			),
			'filters'	=> $this->config['filters'],
		);
		if (isset($this->config['include']['flash']))		$json['flash_swf_url'] = $this->config['include']['flash'];
		if (isset($this->config['include']['silverlight']))	$json['silverlight_xap_url'] = $this->config['include']['silverlight'];

		$standard = "
			$('form').submit(function(e) {
		        var uploader = $('#{$this->config['id']}').pluploadQueue();
		
		        // Files in queue upload them first
		        if (uploader.files.length > 0) {
		            // When all files are uploaded submit form
		            uploader.bind('StateChanged', function() {
		                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
		                    $('form')[0].submit();
		                }
		            });
		                
		            uploader.start();
		        } else {
		            alert('You must queue at least one file.');
		        }
		
		        return false;
		    });";
		return "$(function() {
			$('#{$this->config['id']}').pluploadQueue(" . json_encode($json) . ")
	        $('#{$this->config['id']}').pluploadQueue().bind('UploadComplete', function(uploader, files) {
	        	for (var i = 0; i < files.length; i++){
	        		//Need to use a better selector here:
					$('form').append('<input type=\"hidden\" name=\"{$this->config['id']}_file_name[]\" value=\"' + files[i].name + '\" />')
					$('form').append('<input type=\"hidden\" name=\"{$this->config['id']}_file_size[]\" value=\"' + files[i].size + '\" />')
				}
			});
			
			$standard
		});";
	}

	public function process_upload(){
		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$file_name = isset($_REQUEST["name"]) ? preg_replace('/[^\w\._]+/', '_', $_REQUEST["name"]) : '';
		
		$file_path = "/tmp/" . $file_name;

		if ($chunks < 2 && file_exists($file_path)) {
			//Possibly cause a problem if 2 people try to upload a file of the same name at the same time.
			unlink($file_path);
		}

		$ct = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : $_SERVER["HTTP_CONTENT_TYPE"];
		
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($ct, "multipart") !== false) {
			if (!isset($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
		
			$fp_in = $_FILES['file']['tmp_name'];
		} else {
			$fp_in = "php://input";
		}
		
		
		// Open temp file
		$out = fopen("$file_path.part", $chunk == 0 ? "wb" : "ab");
		if (!$out) die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		$in = @fopen($fp_in, "rb");

		if (!$in) die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		while ($buff = fread($in, 4096)){
			fwrite($out, $buff);
		}
			
		@fclose($in);
		@fclose($out);
		
		if (file_exists($_FILES['file']['tmp_name'])) @unlink($_FILES['file']['tmp_name']);
		
		if (!$chunks || $chunk == $chunks - 1) rename("$file_path.part", $file_path);

		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}
}