<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');

$rrDescription = __('die schnelle und einfache RaumReservierung');

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
        <?php echo Configure::read('display.Short'); ?>:
		<?php echo $rrDescription; ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
    $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon', 'inline' => false));

    $this->AssetCompress->addCss('bootstrap.css');
    $this->AssetCompress->addCss('bootstrap.user.css');
    $this->AssetCompress->addCss('bootstrap-calendar.css');
    $this->AssetCompress->addCss('bootstrap-clockpicker.css');
    $this->AssetCompress->addCss('bootstrap-datetimepicker.css');
    $this->AssetCompress->addCss('bootstrap-lightbox.css');

    $this->AssetCompress->addScript('jquery.js');
    $this->AssetCompress->addScript('jquery.user.js');
    $this->AssetCompress->addScript('typeahead.bundle.js');
    $this->AssetCompress->addScript('bootstrap.js');
    $this->AssetCompress->addScript('bootstrap-clockpicker.js');
    $this->AssetCompress->addScript('bootstrap-lightbox.js');
    $this->AssetCompress->addScript('underscore.js');
    $this->AssetCompress->addScript('serhioromano/calendar.js');
    $this->AssetCompress->addScript('serhioromano/language/de-DE.js');
    $this->AssetCompress->addScript('smalot/bootstrap-datetimepicker.js');
    $this->AssetCompress->addScript('smalot/locales/bootstrap-datetimepicker.de.js');

    echo $this->fetch('meta') . "\r\n";

    if (Configure::read('debug') > 0) {
        echo "\t" . $this->AssetCompress->includeCss(array('raw' => true)) . "\r\n";
    } else {
        echo "\t" . $this->AssetCompress->includeCss() . "\r\n";
    }


    if (Configure::read('debug') > 0) {
        echo "\t" . $this->AssetCompress->includeJs(array('raw' => true)) . "\r\n";
    } else {
        echo "\t" . $this->AssetCompress->includeJs() . "\r\n";
    }

	?>
</head>
<body>
    <script type="text/javascript">
        var rr_base_url = '<?php echo Router::url('/', true); ?>';
    </script>
    <div class="wrapper">
        <div id="container">
            <?php echo $this->element('header'); ?>
            <div class="container-sm">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Session->flash('info'); ?>
            </div>
            <div id="content">
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
        <div class="push"></div>
    </div>
    <?php echo $this->element('sql_dump'); ?>
    <?php echo $this->element('footer', array(), array('cache' => true)); ?>

    <script type="text/javascript">
        $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
        $('[data-toggle=tooltip]').tooltip();
    </script>

    <?php
    echo $this->Js->writeBuffer();
    ?>
</body>
</html>
