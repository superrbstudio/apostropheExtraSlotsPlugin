<?php
class aBetterTwitterSlotActions extends BaseaBetterTwitterSlotActions
{

  // What's all this?
  // Twitter updated their API to v1.1, introducing significant changes to their retrieval methods and response structure.
  // Rather than refactor all of the client-side JS (which lives in jquery.tweet.js) we've instead pointed it to a route
  // on the server ( /api/twitter ) that brings us to executeTwitter() in this class.
  // All this class does is format a v1.1-style twitter url, use aTwitterLegacyConverter to grab tweets, and rearrange the
  // resulting object to mirror a v1.0-style response.

  protected $twitterUserTimelineUrl = "https://api.twitter.com/1.1/statuses/user_timeline.json?";
  protected $twitterSearchUrl = "https://api.twitter.com/1.1/search/tweets.json?";
  protected $usingSearchQuery = null;

  public function executeTwitter(sfRequest $request)
  {
    $query = $this->getRequestParameter('q');
    $screenname = $this->getRequestParameter('screen_name');
    $count = $this->getRequestParameter('count');
    $rts = $this->getRequestParameter('include_rts');

    $url = $this->buildTwitterApiUrl($query, $screenname, $count, $rts);
    $json = $this->callTwitterApi($url);

    // reformat json
    if($this->usingSearchQuery)
    {
      $tweets = $this->reformatJsonFromSearchQuery($json);
    }
    else
    {
      $tweets = $this->reformatJsonFromScreenNameQuery($json);
    }
    
    $tweets = json_encode($tweets);

    $this->getResponse()->setContentType('application/json');
    return $this->renderText($tweets);
  }

  public function buildTwitterApiUrl($query, $screenname, $count, $rts)
  {
    if($query)
    {
      $url = $this->twitterSearchUrl.http_build_query(array('q' => $query));
      $this->usingSearchQuery = true;
    }
    else if($screenname)
    {
      $url = $this->twitterUserTimelineUrl.http_build_query(array('screen_name' => $screenname));
      $this->usingSearchQuery = false;
    }
    $url .= ($count !== null) ? '&count='.$count : '';
    $url .= ($rts !== null) ? '&include_rts='.$rts : '';

    return $url;
  }

  public function callTwitterApi($url)
  {
    $options = sfConfig::get('app_a_sfFeedAdapter');
    $key = $options['adapter_options']['consumer_key'];
    $secret = $options['adapter_options']['consumer_secret'];
    $userAgent = $options['adapter_options']['user_agent'];
    $legacyConverter = new aTwitterLegacyConverter($key, $secret, $userAgent);

    return $legacyConverter->getRawJsonResponse($url);
  }

  public function reformatJsonFromScreenNameQuery($json)
  {
    $jsonObject = json_decode($json);
    $reformatted = array('completed_in' => '', 'max_id' => '', 'max_id_str' => '', 'next_page' => '', 'page' => '', 'query' => '', 'refresh_url' => '', 'results' => array());

    for($i = 0; $i < count($jsonObject); $i++)
    {
      $tweet = $jsonObject[$i];
      $reformatted['results'][$i]['created_at'] = $tweet->created_at;
      $reformatted['results'][$i]['from_user'] = $tweet->user->screen_name;
      $reformatted['results'][$i]['from_user_id'] = $tweet->user->id;
      $reformatted['results'][$i]['from_user_id_str'] = $tweet->user->id_str;
      $reformatted['results'][$i]['from_user_name'] = $tweet->user->name;
      $reformatted['results'][$i]['geo'] = null;
      $reformatted['results'][$i]['id'] = $tweet->id;
      $reformatted['results'][$i]['id_str'] = $tweet->id_str;
      $reformatted['results'][$i]['in_reply_to_status_id'] = $tweet->in_reply_to_status_id;
      $reformatted['results'][$i]['in_reply_to_status_id_str'] = $tweet->in_reply_to_status_id_str;
      $reformatted['results'][$i]['iso_language_code'] = $tweet->user->lang;
      $reformatted['results'][$i]['metadata'] = null;
      $reformatted['results'][$i]['profile_image_url'] = $tweet->user->profile_image_url;
      $reformatted['results'][$i]['profile_image_url_https'] = $tweet->user->profile_image_url_https;
      $reformatted['results'][$i]['source'] = $tweet->source;
      $reformatted['results'][$i]['text'] = $tweet->text;
    }

    return $reformatted;
  }

  public function reformatJsonFromSearchQuery($json)
  {
    $jsonObject = json_decode($json);
    $reformatted = array(
      'completed_in' => $jsonObject->search_metadata->completed_in,
      'max_id' => $jsonObject->search_metadata->max_id,
      'max_id_str' => $jsonObject->search_metadata->max_id_str,
      'next_page' => $jsonObject->search_metadata->next_results,
      'page' => '',
      'query' => $jsonObject->search_metadata->query,
      'refresh_url' => $jsonObject->search_metadata->refresh_url,
      'results' => array()
    );

    for($i = 0; $i < count($jsonObject->statuses); $i++)
    {
      $tweet = $jsonObject->statuses[$i];
      $reformatted['results'][$i]['created_at'] = $tweet->created_at;
      $reformatted['results'][$i]['from_user'] = $tweet->user->screen_name;
      $reformatted['results'][$i]['from_user_id'] = $tweet->user->id;
      $reformatted['results'][$i]['from_user_id_str'] = $tweet->user->id_str;
      $reformatted['results'][$i]['from_user_name'] = $tweet->user->name;
      $reformatted['results'][$i]['geo'] = null;
      $reformatted['results'][$i]['id'] = $tweet->id;
      $reformatted['results'][$i]['id_str'] = $tweet->id_str;
      $reformatted['results'][$i]['in_reply_to_status_id'] = $tweet->in_reply_to_status_id;
      $reformatted['results'][$i]['in_reply_to_status_id_str'] = $tweet->in_reply_to_status_id_str;
      $reformatted['results'][$i]['iso_language_code'] = $tweet->user->lang;
      $reformatted['results'][$i]['metadata'] = null;
      $reformatted['results'][$i]['profile_image_url'] = $tweet->user->profile_image_url;
      $reformatted['results'][$i]['profile_image_url_https'] = $tweet->user->profile_image_url_https;
      $reformatted['results'][$i]['source'] = $tweet->source;
      $reformatted['results'][$i]['text'] = $tweet->text;
    }

    return $reformatted;
  }

}