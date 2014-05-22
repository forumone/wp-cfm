<?php
$configuration = $this->registry->get_configuration_items();
$namespaces = $this->registry->get_namespaces();
//echo '<pre>';var_dump($configuration);echo '</pre>';
?>

<script src="<?php echo WP_CFM_URL; ?>/assets/js/admin.js"></script>
<link href="<?php echo WP_CFM_URL; ?>/assets/css/admin.css" rel="stylesheet">


<div class="wrap">
    <h2>WP-CFM</h2>
    <p>Configuration management for WordPress (by <a href="http://forumone.com" target="_blank">Forum One</a>)</p>

    <div class="wpcfm-response"></div>

    <div class="wpcfm-action-buttons">
        <div style="float:right">
            <a class="button-primary wpcfm-save"><?php _e( 'Save Changes', 'wpcfm' ); ?></a>
        </div>
        <a class="button add-bundle"><?php _e( 'Add Bundle', 'wpcfm' ); ?></a>
        <div class="clear"></div>
    </div>

    <div class="wpcfm-content wpcfm-content-bundles">
        <div class="wpcfm-tabs">
            <ul></ul>
        </div>
        <div class="wpcfm-bundles"></div>
        <div class="clear"></div>
    </div>

    <!-- clone settings -->

    <div class="bundles-hidden">
        <div class="wpcfm-bundle">
            <table class="wpcfm-table">
                <tr>
                    <td style="width:175px"><?php _e( 'Label', 'wpcfm' ); ?>:</td>
                    <td>
                        <input type="text" class="bundle-label" value="" />
                        <input type="text" class="bundle-name" value="" />
                    </td>
                </tr>
                <tr>
                    <td><?php _e( 'Bundle Data', 'wpcfm' ); ?>:</td>
                    <td>
                        <?php foreach ( $configuration as $namespace => $settings ) : ?>
                        <h3><?php echo $namespaces[ $namespace ]; ?></h3>
                        <?php foreach ( $settings as $key => $val ) : ?>
                        <div><?php echo $key; ?></div>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </td>
                </tr>
            </table>
            <a class="remove-bundle"><?php _e( 'Delete Bundle', 'wpcfm' ); ?></a>
        </div>
    </div>
</div>
