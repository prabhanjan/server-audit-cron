<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__FILE__).'/../vendor/autoload.php';

function getCronStatus()
{

  if (!function_exists('exec'))
    return false;

  exec('crontab -u www-data -l | grep \'* *\'', $status); 

  if (empty($status) or (false !== strpos($status[0], 'fatal')))
    return false;
  $end = end($status);

  return $status;
}

function getAllDocumentRoots()
{

  if (!function_exists('exec'))
    return false;
  exec('grep DocumentRoot /etc/apache2/sites-enabled/* -RiI  -RiI | sed \'s/.*DocumentRoot \(.*\)/\1/\'', $status);
  if (empty($status) or (false !== strpos($status[0], 'fatal')))
    return false;
  $end = end($status);

  return (array_unique($status));
}


function git_status_get_status($path)
{
  if (!function_exists('exec'))
    return false;
  exec(sprintf('cd %s; git status 2>&1', escapeshellarg($path)), $status);

 /*  if (empty($status) or (false !== strpos($status[0], 'fatal')))
    return false; */
  $end = end($status);
  $return = array(
    'dirty'  => '*',
    'branch' => 'detached',
    'status' => $status,
  );
  if (preg_match('/On branch (.+)$/', $status[0], $matches))
    $return['branch'] = trim($matches[1]);
  if (empty($end) or (false !== strpos($end, 'nothing to commit')))
    $return['dirty'] = '';
  return $return;
}


function sendEmail($message)
{

  global $config;
  $mail = new PHPMailer(true);
  try {
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = $config['Host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['Username'];
    $mail->Password = $config['Password'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($config['from'], $config['from_name']);
    $mail->addAddress($config['to'], $config['to_name']);

    //Content
    $mail->isHTML(true);
    $mail->Subject = $config['subject'];
    $mail->Body    = $message;

    $mail->send();
    echo 'Message has been sent';
  } catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  }
}
