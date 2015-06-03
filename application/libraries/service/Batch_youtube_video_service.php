<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_batch_service.php";

class Batch_youtube_video_service extends Base_batch_service {

	const BATCH_REQUEST_URI = 'http://gdata.youtube.com/feeds/api/videos/batch';

	const XMLNS_ATOM_URI = 'http://www.w3.org/2005/Atom';
	const XMLNS_BATCH_URI = 'http://schemas.google.com/gdata/batch';
	const XMLNS_MEDIA_URI = 'http://search.yahoo.com/mrss/';
	const XMLNS_GD_URI = 'http://schemas.google.com/g/2005';
	const XMLNS_YT_URI = 'http://gdata.youtube.com/schemas/2007';

	const RESPONSE_CODE_OK = 200;
	const RESPONSE_CODE_VIDEO_NOTFOUND = 404;

	function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Batch_dao.php");
		$this->set_dao(new Batch_dao());
		include_once(APPPATH."libraries/dao/Interface_product_video_dao.php");
		$this->set_yt_dao(new Interface_product_video_dao());
		include_once(APPPATH . 'libraries/service/Context_config_service.php');
		$this->set_cc_srv(new Context_config_service());


	}

	public function query_by_video_url($video_urls = array())
	{
		if(!is_array($video_urls) && !empty($video_urls))
		{
			$video_urls = (array)$video_urls;
		}

		$video_ids = array();
		foreach($video_urls as $url)
		{
			$video_ids[] = $this->_get_video_id_from_url($url);
		}

		return $this->query_by_videoid($video_ids);
	}

	public function query_by_video_ids($video_ids = array())
	{
		if(!is_array($video_ids) && !empty($video_ids))
		{
			$video_ids = (array)$video_ids;
		}

		if(count($video_ids) < 1)
		{
			return false;
		}

		$batch_vo = $this->get_dao()->get();

		$payload =
			'<feed
				xmlns="'			. self::XMLNS_ATOM_URI	. '"
				xmlns:media="' . self::XMLNS_MEDIA_URI . '"
				xmlns:batch="' . self::XMLNS_BATCH_URI . '"
				xmlns:yt="'		. self::XMLNS_YT_URI		. '">

				<batch:operation type="query"/>';

		foreach($video_ids as $video_id)
		{
			$payload .= '
				<entry>
					<id>http://gdata.youtube.com/feeds/api/videos/' . $video_id . '</id>
				</entry>';
		}

		$payload .= '
			</feed>';

		if(!($response = $this->_post_payload($payload)))
		{
			return false;
		}
		else
		{
			if($this->_save_response_xml($response));
		}

		return $this->_parse_response($response);
	}

	private function _post_payload($payload)
	{
		if(!function_exists('curl_init'))
			return false;

		$ch = curl_init(self::BATCH_REQUEST_URI);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);

		curl_close($ch);

		return $response;
	}

	private function _save_response_xml($response)
	{
		$path = $this->get_cc_srv()->value_of("batch_youtube_info_path");
		$xml_file = "$path/batch_video_".date("YmdHis").".xml";
		file_put_contents($xml_file, $response);
		chown($xml_file, "apache");
		chgrp($xml_file, "users");
		chmod($xml_file, 0664);
	}

	private function _parse_response($response)
	{
		if(!class_exists('SimpleXMLElement'))
		{
			return false;
		}

		$xml = new SimpleXMLElement($response);

		$feed = $xml->children(self::XMLNS_ATOM_URI);

		if(count($feed) < 1)
		{
			return false;
		}

		$entries = array();

		foreach($feed->entry as $entry)
		{
			$batch = $entry->children(self::XMLNS_BATCH_URI);

			if(count($batch) < 1)
				continue;

			$batch_attributes = $batch->status->attributes();

			if($batch_attributes['code'] != self::RESPONSE_CODE_OK)
			{
				$yt_error = new YT_error();
				$yt_error->id = $this->_parse_entry_id((string)$entry->id);
				$yt_error->code = (string)$batch_attributes['code'];
				$yt_error->message = (string)$batch_attributes['reason'];

				$entries["$yt_error->id"] = $yt_error;

				continue;
			}

			$media = $entry->children(self::XMLNS_MEDIA_URI);

			if(count($media) < 1)
				continue;

			$yt_entry = new YT_entry();

			$yt_entry->id = $this->_parse_entry_id((string)$entry->id);

			$yt_entry->title = (string)$media->group->title;

			$yt_entry->published = (string)$entry->published;
			$yt_entry->updated = (string)$entry->updated;
			$yt_entry->category = (array)explode(',', (string)$media->group->category);

			foreach($media->group->content as $content)
			{
				$attributes = $content->attributes();

				$entry_content = array(
					'url' => (string)$attributes['url'],
					'type' => (string)$attributes['type'],
					'medium' => (string)$attributes['medium'],
					'isDefault' => (string)$attributes['isDefault'],
					'duration' => (string)$attributes['duration']
				);

				if($attributes['isDefault'])
				{
					$yt_entry->duration = array(
						'value' => (string)$attributes['duration'],
						'unit' => 'seconds'
					);

					$yt_entry->url = $entry_content['url'];
				}

				$yt_entry->content[] = $entry_content;
			}

			$yt_entry->decription = (string)$media->group->description;
			$yt_entry->tags = (array)explode(',', (string)$media->group->keywords);

			$player = $media->group->player->attributes();
			$yt_entry->player = (string)$player['url'];

			foreach($media->group->thumbnail as $thumbnail)
			{
				$attributes = $thumbnail->attributes();

				$entry_thumbnail = array(
					'url' => (string)$attributes['url'],
					'height' => (string)$attributes['height'],
					'width' => (string)$attributes['width']
				);

				$yt_entry->thumbnails[] = $entry_thumbnail;
			}

			$yt_entry->author = array(
				'name' => (string)$entry->author->name,
				'uri' => (string)$entry->author->uri
			);

			$gd = $entry->children(self::XMLNS_GD_URI);

			if(count($gd) > 0)
			{
				if(count($gd->rating) > 0)
				{
					$rating = $gd->rating->attributes();
					$yt_entry->rating = array(
						'average' => (string)$rating['average'],
						'max' => (string)$rating['max'],
						'min' => (string)$rating['min'],
						'numRaters' => (string)$rating['numRaters']
					);
				}

				if(count($gd->comments) > 0 && count($gd->comments->feedLink) > 0)
				{
					$comments = $gd->comments->feedLink->attributes();
					$yt_entry->comments = array(
						'uri' => (string)$comments['href'],
						'count' => (string)$comments['countHint']
					);
				}
			}

			$yt = $entry->children(self::XMLNS_YT_URI);

			if(count($yt) > 0 && count($yt->statistics) > 0)
			{
				$stats = $yt->statistics->attributes();
				$yt_entry->statistics = array(
					'favoriteCount' => (string)$stats['favoriteCount'],
					'viewCount' => (string)$stats['viewCount']
				);
			}

			$entries["$yt_entry->id"] = $yt_entry;
		}

		return (count($entries) < 1) ? false : $entries;
	}

	private function _parse_entry_id($id)
	{
		if(empty($id))
			return false;

		// id is in the format http://gdata.youtube.com/feeds/api/videos/{video_id}
		return substr($id, strrpos($id, '/') + 1);
	}

	private function _get_video_id_from_url($url)
	{
		$protocol = '(http://)|(http://www.)|(www.)';
		$protocol = str_replace('.', '\.', str_replace('/', '\/', $protocol)); // escape those reg exp characters
		$protocol = ($protocol != '') ? '^(' . $protocol . ')' : $protocol; //if empty arg passed, let it it match anything at beginning
		$match_str = '/' . $protocol . 'youtube\.com\/(.+)(v=.+)/'; //build the match string

		preg_match($match_str, $url, $matches); // find the matches and put them in $matches variable

		if($matches != null)
		{
			if(count($matches) >= 3)
			{
				$qs = explode('&',$matches[count($matches)-1]); //the last match will be the querystring - split them at amperstands
				$vid = false; //default the video ID to false
				for($i=0; $i<count($qs); $i++)
				{ //loop through the params
					$x = explode('=', $qs[$i]); //split at = to find key/value pairs
					if($x[0] == 'v' && $x[1])
					{ //if the param is 'v', and it has a value associated, we want it
						$vid = $x[1]; // set the video id to the val
						return $vid;
					}
					else
					{
						return false;
					}
				}
			}
		}

		return false;
	}

	public function get_batch_dao()
	{
		return $this->batch_dao;
	}

	public function set_batch_dao(Base_dao $dao)
	{
		$this->batch_dao = $dao;
	}

	public function get_yt_dao()
	{
		return $this->yt_dao;
	}

	public function set_yt_dao(Base_dao $dao)
	{
		$this->yt_dao = $dao;
	}

	public function set_cc_srv($serv)
	{
		$this->cc_srv = $serv;
	}

	public function get_cc_srv()
	{
		return $this->cc_srv;
	}
}

class YT_entry
{
	public $id;
	public $published;
	public $updated;

	public $url;

	public $category;
	public $content;
	public $decription;
	public $tags;
	public $player;
	public $thumbnails;
	public $title;

	public $duration;

	public $statistics;

	public $author;

	public $comments;
}

class YT_error
{
	public $code;
	public $message;
}
/* End of file batch_inventory_service.php */
/* Location: ./system/application/libraries/service/Batch_inventory_service.php */
