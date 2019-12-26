<?php



include_once 'inc/functions.php';
include_once 'inc/config.php';

$message = '';
$domainList = getAllDocumentRoots();
$message .= '<h2>Domains</h2>';
$message .= arrayToList($domainList);


$message .= "<p>";
$message .= '<h2>Cron Status</h2>';
$message .= arrayToList(getCronStatus());
$message .=  "</p>";

$message .= "<p>";
foreach ($domainList as $domain) {
    $status = '';
    $status = git_status_get_status($domain);
    if ($status['dirty'] == '*') {
        $message .= "<h2>{ $domain}</h2>";
        $message .= arrayToList($status['status']);
    }
}
$message .=  "</p>";

sendEmail($message);

function arrayToList($list)
{
    if (is_array($list)) {
        $message = '<ul>';
        foreach ($list as $item) {
            $item = str_replace("Untracked files:", "<strong>Untracked files:</strong>", $item);
            if (strpos($item, "On branch ") === false && strpos($item, "Changes not staged ") === false  && strpos($item, "(use \"git ") === false && !empty($item)) {
                $message .= "<li>" . $item . "</li>";
            }
        }
        $message .= '</ul>';
        return $message;
    }
    return '';
}
