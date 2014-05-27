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
    <h2>
        Configuration Management <span>by <a href="http://forumone.com/" target="_blank">Forum One Communications</a></span>
    </h2>

    <?php if ( !empty ( $this->readwrite->error ) ) : ?>
    <div class="wpcfm-error"><?php echo $this->readwrite->error; ?></div>
    <?php endif; ?>

    <div class="wpcfm-response"></div>

    <div class="wpcfm-bundles">
        <div class="wpcfm-action-buttons">
            <div style="float:right">
                <a class="button-primary wpcfm-save"><?php _e( 'Save Changes', 'wpcfm' ); ?></a>
            </div>
            <a class="button add-bundle"><?php _e( 'Add Bundle', 'wpcfm' ); ?></a>
            <div class="clear"></div>
        </div>

        <div class="bundle-row row-default" data-bundle="all">
            <div class="bundle-header">
                <div class="bundle-actions">
                    <a class="button push-bundle"><?php _e( 'Push', 'wpcfm' ); ?></a> &nbsp;
                    <a class="button pull-bundle"><?php _e( 'Pull', 'wpcfm' ); ?></a>
                </div>
                <div class="bundle-toggle">All Bundles</div>
                <div class="clear"></div>
            </div>
            <div class="bundle-row-inner">
                No actions are available.
            </div>
        </div>
    </div>

    <!-- diff modal -->

    <div style="display:none">
        <div id="mydiff">
            <div class="wpcfm-diff">
                <pre class="original"></pre>
                <pre class="changed"></pre>
                <pre class="diff"></pre>
            </div>
        </div>
        <a class="trigger-modal thickbox" href="#TB_inline?width=600&height=400&inlineId=mydiff" title="Original (file); changed (database)"></a>
    </div>

    <!-- clone settings -->

    <div class="bundles-hidden">
        <div class="bundle-row">
            <div class="bundle-header">
                <div class="bundle-actions">
                    <a class="button diff-bundle"><?php _e( 'Diff', 'wpcfm' ); ?></a> &nbsp;
                    <a class="button push-bundle"><?php _e( 'Push', 'wpcfm' ); ?></a> &nbsp;
                    <a class="button pull-bundle"><?php _e( 'Pull', 'wpcfm' ); ?></a>
                </div>
                <div class="bundle-toggle">New bundle</div>
                <div class="clear"></div>
            </div>
            <div class="bundle-row-inner">
                <input type="text" class="bundle-label" value="New bundle" />
                <input type="text" class="bundle-name" value="new_bundle" />
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
                <a class="remove-bundle"><?php _e( 'Delete Bundle', 'wpcfm' ); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
