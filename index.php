<?php

$start = microtime(true);

include "config.php";
include "bot/Telegram.php";
require "query/MinecraftQuery.php";
require "query/MinecraftQueryException.php";
include "lang/" . $config["lang"] . ".php";

use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;

$live_playerlist = get_live_playerlist();
$cached_playerlist = get_cached_playerlist();
$message_to_chat = "";

// Compare live playerlist to cached playerlist. If there is a change, generate a message
if ($live_playerlist != $cached_playerlist) {
  $message_to_chat = generate_message($live_playerlist, $cached_playerlist);
}

// If there's a message, post it
if ($message_to_chat) {
  post_message_to_chat($message_to_chat);
  echo $message_to_chat; // also echo the result for easier debugging without chat spam
}

// When done, cache current playerlist
cache_playerlist($live_playerlist);

// Display execution time. Useful for adjusting the interval to call this script
$execution_time = microtime(true) - $start;
echo "\r\nExecution time: " . $execution_time;

function get_live_playerlist()
{
  global $config;
  $Query = new MinecraftQuery();

  try {
    $Query->Connect($config["server_url"], $config["server_port"]);
    $playerlist = $Query->GetPlayers();
    $playerlist = !is_array($playerlist) ? [] : $playerlist;
    sort($playerlist);

    return $playerlist;
  } catch (MinecraftQueryException $e) {
    echo $e->getMessage();
  }
}

function get_cached_playerlist()
{
  return unserialize(file_get_contents("cache"));
}

function cache_playerlist($playerlist)
{
  file_put_contents("cache", serialize($playerlist));
}

function generate_message($live_playerlist, $cached_playerlist)
{
  global $lang;

  // The playerlist is an empty string if no players joined but we ALWAYS need an array for the following functions
  $live_playerlist_array = is_array($live_playerlist) ? $live_playerlist : [];
  $cached_playerlist_array = is_array($cached_playerlist)
    ? $cached_playerlist
    : [];

  $players_joined = array_diff(
    $live_playerlist_array,
    $cached_playerlist_array
  );
  $players_disconnected = array_diff(
    $cached_playerlist_array,
    $live_playerlist_array
  );

  $message = "";

  foreach ($players_joined as $player_joined) {
    $message .= $player_joined . " " . $lang["joined"] . " ";
  }

  foreach ($players_disconnected as $player_disconnected) {
    $message .= $player_disconnected . " " . $lang["disconnected"] . " ";
  }

  $player_count = count($live_playerlist_array);

  switch ($player_count) {
    case 0:
      $message .= $lang["no_players_connected"];
      break;
    case 1:
      $message .= $lang["one_player_connected"];
      break;
    default:
      $message .= $player_count . " " . $lang["players_connected"];
  }
  return $message;
}

function post_message_to_chat($message)
{
  global $config;

  $telegram = new Telegram($config["bot_token"]);
  $content = ["chat_id" => $config["chat_id"], "text" => $message];
  $result = $telegram->sendMessage($content);

  if ($result["error_code"] >= 400) 
  {
    echo "Failed to send telegram message (" . $result["error_code"] . ").";
    echo "Message: " . $result["description"];
    echo "";
    echo "Full Response:";
    var_dump($result);
  }
}
?>
