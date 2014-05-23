<?php

$configuration = $this->registry->get_configuration_items();
$namespaces = $this->registry->get_namespaces();

?>

<?php add_thickbox(); ?>
<script src="<?php echo WPCFM_URL; ?>/assets/js/admin.js"></script>
<script src="<?php echo WPCFM_URL; ?>/assets/js/multiple-select/jquery.multiple.select.js"></script>
<link href="<?php echo WPCFM_URL; ?>/assets/css/admin.css" rel="stylesheet">
<link href="<?php echo WPCFM_URL; ?>/assets/js/multiple-select/multiple-select.css" rel="stylesheet">

<script src="<?php echo WPCFM_URL; ?>/assets/js/pretty-text-diff/diff_match_patch.js"></script>
<script src="<?php echo WPCFM_URL; ?>/assets/js/pretty-text-diff/jquery.pretty-text-diff.js"></script>

<div class="wrap">
    <h2>WP-CFM</h2>
    <p>Configuration management for WordPress (by <a href="http://forumone.com" target="_blank">Forum One</a>)</p>

    <h2 class="nav-tab-wrapper">
        <a class="nav-tab" rel="actions"><?php _e( 'Push / Pull', 'wpcfm' ); ?></a>
        <a class="nav-tab" rel="bundles"><?php _e( 'Bundles', 'wpcfm' ); ?></a>
    </h2>

    <div class="wpcfm-response"></div>

    <div class="wpcfm-content wpcfm-content-actions">
        <div class="bundle-row" data-bundle="widgets">
            <div class="bundle-actions">
                <a class="button diff-bundle"><?php _e( 'Diff', 'wpcfm' ); ?></a> &nbsp;
                <a class="button push-bundle"><?php _e( 'Push', 'wpcfm' ); ?></a> &nbsp;
                <a class="button pull-bundle"><?php _e( 'Pull', 'wpcfm' ); ?></a>
            </div>
            <div class="bundle-name">Widgets</div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="wpcfm-content wpcfm-content-bundles">
        <div class="wpcfm-action-buttons">
            <div style="float:right">
                <a class="button-primary wpcfm-save"><?php _e( 'Save Changes', 'wpcfm' ); ?></a>
            </div>
            <a class="button add-bundle"><?php _e( 'Add Bundle', 'wpcfm' ); ?></a>
            <div class="clear"></div>
        </div>

        <div class="wpcfm-tabs">
            <ul></ul>
        </div>
        <div class="wpcfm-bundles"></div>
        <div class="clear"></div>
    </div>

    <div class="wpcfm-diff" id="diff1">
        <p>Database diff:</p>
        <div class="original" style="display:none"></div>
        <div class="changed" style="display:none"></div>
        <div class="diff"></div>
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
                        <div class="bundle-select-wrapper">
                            <select class="bundle-select" multiple="multiple">
                            <?php foreach ( $configuration as $namespace => $settings ) : ?>
                                <optgroup label="<?php echo $namespaces[ $namespace ]; ?>">
                                    <?php foreach ( $settings as $key => $val ) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
            <a class="remove-bundle"><?php _e( 'Delete Bundle', 'wpcfm' ); ?></a>
        </div>
    </div>
</div>
