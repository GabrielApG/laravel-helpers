<?php
/** @TODO - rewrite to simplify these using Laravel features

/**
 * Perform an array multisort, based on 1 or 2 columns being passed
 * (defaults to sorting by first column ascendingly then second column ascendingly unless otherwise specified)
 *
 * @param $data        multidimensional array to be sorted
 * @param $columnName1 string representing the named column to sort by as first criteria
 * @param $order1      either SORT_ASC or SORT_DESC (default SORT_ASC)
 * @param $columnName2 string representing named column as second criteria
 * @param $order2      either SORT_ASC or SORT_DESC (default SORT_ASC)
 * @return array   Original array sorted as specified
 */
function sortMultiArray($data, $columnName1 = '', $order1 = SORT_ASC, $columnName2 = '', $order2 = SORT_ASC)
{
  // simple validations
  $keys = array_keys($data);
  if ($columnName1 == '') {
    $columnName1 = $keys[0];
  }
  if (!in_array($order1, array(SORT_ASC, SORT_DESC))) $order1=SORT_ASC;
  if ($columnName2 == '') {
    $columnName2 = $keys[1];
  }
  if (!in_array($order2, array(SORT_ASC, SORT_DESC))) $order2=SORT_ASC;

  // prepare sub-arrays for aiding in sorting
  foreach($data as $key=>$val)
  {
    $sort1[] = $val[$columnName1];
    $sort2[] = $val[$columnName2];
  }
  // do actual sort based on specified fields.
  array_multisort($sort1, $order1, $sort2, $order2, $data);
  return $data;
}

/**
 * Determine visitor's IP address, resolving any proxies where possible.
 *
 * @return string
 */
function getCleanIpAddress() {
  $ip = '';
  /**
   * resolve any proxies
   */
  if (isset($_SERVER)) {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
      $ip = $_SERVER['HTTP_FORWARDED'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
  }
  if (trim($ip) == '') {
    if (getenv('HTTP_X_FORWARDED_FOR')) {
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_CLIENT_IP')) {
      $ip = getenv('HTTP_CLIENT_IP');
    } else {
      $ip = getenv('REMOTE_ADDR');
    }
  }

  /**
   * sanitize for validity as an IPv4 or IPv6 address
   */
  $ip = preg_replace('~[^a-fA-F0-9.:%/]~', '', $ip);

  /**
   *  if it's still blank, set to a single dot
   */
  if (trim($ip) == '') $ip = '.';

  return $ip;
}
