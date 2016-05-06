<?php

/*
|--------------------------------------------------------------------------
| Statamic is a PHP Application
|--------------------------------------------------------------------------
|
| If you see this text, your server is not running PHP. You'll need to
| contact your system administrator, webhost, or Google to enable it,
| or maybe try a different host.
|
*/

$success = true;

/*
|--------------------------------------------------------------------------
| Error Reporting
|--------------------------------------------------------------------------
|
| Let's crank up that error reporting to catch the edge cases.
|
*/

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', '1');

/*
|--------------------------------------------------------------------------
| Requirements and recommendations
|--------------------------------------------------------------------------
|
| Let's take a look under the hood.
|
*/

$requirements = [
    'PHP >= 5.5.9' => version_compare(PHP_VERSION, '5.5.9', '>='),
    'OpenSSL PHP Extension' => extension_loaded('openssl'),
    'Mbstring PHP Extension' => extension_loaded('mbstring'),
    'Tokenizer PHP Extension' => extension_loaded('tokenizer')
];

$recommendations = [
    'Mod Rewrite' => hasModRewrite('mod_rewrite'),
    'Timezone Set' => ini_get('date.timezone') !== '',
    'GD Library for image manipulation' => (extension_loaded('gd') && function_exists('gd_info')),
    'FileInfo Extension for image manipulation' => extension_loaded('fileinfo'),
    'Zip archive support for updater backups' => class_exists('ZipArchive')
];

// PHP 5.6 does a stupid deprecation warning that breaks things. It needs to be disabled.
if (version_compare(PHP_VERSION, '7.0.0', '<') && version_compare(PHP_VERSION, '5.6.0', '>=')) {
    $recommendations['always_populate_raw_post_data=-1'] = ini_get('always_populate_raw_post_data') === '-1';
}

foreach ($requirements as $item) {
    if (! $item) {
        $success = false;
        break;
    }
}

function hasApacheModule($module)
{
    if (function_exists('apache_get_modules')) {
        return in_array($module, apache_get_modules());
    }
    return false;
}

function hasModRewrite()
{
    $check = hasApacheModule('mod_rewrite');

    if (! $check && function_exists('shell_exec')) {
        $check = strpos(shell_exec('/usr/local/apache/bin/apachectl -l'), 'mod_rewrite') !== false;
    }

    return $check;
}

function url($url)
{
    $url = pathinfo($_SERVER['SCRIPT_NAME'])['dirname'] . '/index.php' . $url;

    return str_replace('//', '/', $url);
}

/*
|--------------------------------------------------------------------------
| The moment of truth
|--------------------------------------------------------------------------
|
| Let's see how you went...
|
*/
?>
<!doctype html>
<head>
    <meta charset="utf-8" />
    <title>Statamic</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:700,400,400italic,700italic" rel="stylesheet" />
    <style>
        body { width: 555px; margin: 0 auto; font: normal 14px/1.42 "Lato", sans-serif; background: url(<?= url("/resources/cp/img/blurry.jpg"); ?>) top center no-repeat fixed; background-size: cover; padding-top: 75px; }
        .logo { width: 100px; display: block; fill: rgba(0, 0, 0, .3); margin: 0 auto 50px; }
        .card { background: white; border-radius: 2px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.24); margin: 0 auto; }
        h1 { font: normal 21px/1.1 "Lato", sans-serif; padding: 15px 30px; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        tr { border-top: 1px solid #e0e0e0; }
        td { padding: 10px 30px; }
        td.result { text-align: right; }
        th { text-align: left; padding: 30px 30px 10px 30px; }
        th small { display: block; font-weight: normal; color: #777; }
        p { text-align: center; }
        .fail { color: #E75650; font-weight: bold; }
        .asterisk { margin: 20px auto 0; font-size: 11px; }
        .install { font-size: 20px; text-decoration: none; display: block; margin-top: 20px; padding: 10px 20px; background: #3aa3e3; color: white; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.24); }
        .fail-message { font-size: 20px; background: #E75650; color: white; padding: 10px 20px; border-radius: 3px; }
    </style>
</head>
<body>

    <div class="logo">
        <?= file_get_contents(__DIR__.'/statamic/resources/dist/img/statamic-mark.svg') ?>
    </div>

    <div class="card">
        <h1>Checking Server Compatibility</h1>
        <table>
            <tr>
                <th colspan="2">
                    Requirements
                    <small>You aren't getting far without these.</small>
                </th>
            </tr>
            <?php foreach ($requirements as $name => $result): ?>
                <tr>
                    <td>
                        <?php echo $name;
                        if (substr( $name, 0, 3 ) === "PHP"){
                            echo ' (' . PHP_VERSION . ')';
                        } ?>
                    </td>
                    <td class="result"><?= ($result) ? 'Pass' : '<span class="fail">Fail</span>' ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="2">
                    Recommendations
                    <small>These aren't required, but you might encounter problems without them.</small>
                </th>
            </tr>
            <?php foreach ($recommendations as $name => $result): ?>
                <tr>
                    <td><?= $name ?></td>
                    <td class="result">
                        <?php if ($name == 'Mod Rewrite' && !$result): ?>
                            <span class="fail">Unknown*</span>
                        <?php else: ?>
                            <?= ($result) ? 'Pass' : '<span class="fail">Fail</span>' ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php if ($success): ?>
        <p><a href="<?= url('/installer'); ?>" class="install">Server looks compatible. Continue to next step &rarr;</a></p>
    <?php else: ?>
        <p class="fail-message">Uh oh, there's a problem.</p>
    <?php endif; ?>

    <?php if (!$recommendations['Mod Rewrite']): ?>
        <p class="asterisk">* mod_rewrite can't be detected when PHP is running as CGI</p>
    <?php endif; ?>

</body>
</html>
