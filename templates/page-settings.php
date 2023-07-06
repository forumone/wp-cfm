<?php

$configuration = $this->registry->get_configuration_items();
$configuration = $this->helper->group_items( $configuration );

?>

<div class="wrap">
    <h2>WP-CFM</h2>

    <?php if ( defined( 'WPCFM_CURRENT_ENV' ) && !empty( WPCFM_CURRENT_ENV ) ): ?>
    <div class="wpcfm-info">
        <?php esc_html_e( 'Current environment', 'wp-cfm' ); ?>: <code><?php echo ucfirst( WPCFM_CURRENT_ENV ); ?></code>
    </div>

    <!-- Environment Switcher -->
    <div class="wpcfm-env-switch">
        <?php esc_html_e( 'Switch config to', 'wp-cfm' ); ?>: <select name="wpcfm_env_switch" id="wpcfm_env_switch">
            <?php foreach ( WPCFM_REGISTER_MULTI_ENV as $env ): ?>
                <option value="<?php echo $env; ?>"
                    <?php if ( ( WPCFM_CURRENT_ENV == $env && !defined( 'WPCFM_COMPARE_ENV' ) ) || ( defined( 'WPCFM_COMPARE_ENV' ) && WPCFM_COMPARE_ENV == $env ) ): ?>
                    <?php echo 'selected="selected"'; ?>
                <?php endif ?>
                ><?php echo ucfirst( $env ); ?></option>
            <?php endforeach ?>
        </select>
    </div>
<?php endif; ?>

<?php if (defined('WPCFM_CONFIG_FORMAT_REQUESTED') && in_array(WPCFM_CONFIG_FORMAT_REQUESTED, array('yml', 'yaml'))): ?>
<div class="wpcfm-error"><?php esc_html_e( 'Your PHP version is not compatible with Yaml export format. Upgrade to at least PHP 5.6.4.', 'wp-cfm' ); ?></div>
<?php endif; ?>

<?php if ( !empty ( $this->readwrite->error ) ) : ?>
    <div class="wpcfm-error"><?php echo $this->readwrite->error; ?></div>
<?php endif; ?>

<div class="wpcfm-warnings">
    <?php foreach ( $this->registry->get_duplicates() as $option => $bundles ): ?>
        <div class="wpcfm-warning">
            <?php esc_html_e( 'Warning: ', 'wp-cfm' ); ?>
            <?php echo( $option ); ?>
            <?php esc_html_e( 'is tracked by multiple bundles: ', 'wp-cfm' ); ?>
            <?php echo( implode( ', ', $bundles ) ); ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="wpcfm-bundles">
    <div class="wpcfm-action-buttons">
        <div style="float:right">
            <span class="wpcfm-response"></span>
            <a class="button-primary wpcfm-save"><?php esc_html_e( 'Save Changes', 'wp-cfm' ); ?></a>
        </div>
        <a class="button add-bundle"><?php esc_html_e( 'Add Bundle', 'wp-cfm' ); ?></a>
        <div class="clear"></div>
    </div>

    <div class="bundle-row row-all" data-bundle="all">
        <div class="bundle-header">
            <div class="bundle-actions">
                <a class="button diff-bundle" title="<?php esc_attr_e( 'Compare differences', 'wp-cfm' ); ?>"><?php esc_html_e( 'Diff', 'wp-cfm' ); ?></a> &nbsp;
                <a class="button push-bundle" title="<?php esc_attr_e( 'Write database changes to the filesystem', 'wp-cfm' ); ?>"><?php esc_html_e( 'Push', 'wp-cfm' ); ?></a> &nbsp;
                <a class="button pull-bundle" title="<?php esc_attr_e( 'Import file changes into the database', 'wp-cfm' ); ?>"><?php esc_html_e( 'Pull', 'wp-cfm' ); ?></a>
            </div>
            <div class="bundle-toggle"><?php esc_html_e( 'All Bundles', 'wp-cfm' ); ?></div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<div class="attribution">
    <?php printf(esc_html__( 'Created by %1$sForum One%2$s', 'wp-cfm' ),'<a href="http://forumone.com/" target="_blank">','</a>'); ?>
</div>

<!-- clone settings -->

<div class="bundles-hidden">
    <div class="bundle-row" data-bundle="new_bundle">
        <div class="bundle-header">
            <div class="bundle-actions">
                <span class="no-actions"><?php esc_html_e( 'Save to see actions', 'wp-cfm' ); ?></span>
                <a class="button diff-bundle" title="<?php esc_attr_e( 'Compare differences', 'wp-cfm' ); ?>"><?php esc_html_e( 'Diff', 'wp-cfm' ); ?></a> &nbsp;
                <a class="button push-bundle disabled" title="<?php esc_attr_e( 'Write database changes to the filesystem', 'wp-cfm' ); ?>"><?php esc_html_e( 'Push', 'wp-cfm' ); ?></a> &nbsp;
                <a class="button pull-bundle disabled" title="<?php esc_attr_e( 'Import file changes into the database', 'wp-cfm' ); ?>"><?php esc_html_e( 'Pull', 'wp-cfm' ); ?></a>
            </div>
            <div class="bundle-toggle"><?php esc_html_e( 'New bundle', 'wp-cfm' ); ?></div>
            <div class="clear"></div>
        </div>
        <div class="bundle-row-inner">
            <input type="text" class="bundle-label" value="<?php esc_attr_e( 'New bundle', 'wp-cfm' ); ?>" />
            <input type="text" class="bundle-name" value="new_bundle" />
            <a href="#" class="hide-registered"><?php esc_html_e( 'Hide registered', 'wp-cfm' ); ?></a>
            <a href="#" class="show-all hidden"><?php esc_html_e( 'Show all', 'wp-cfm' ); ?></a>
            <div class="bundle-select-wrapper">
                <select class="bundle-select" multiple="multiple">
                    <?php foreach ( $configuration as $group => $config ) : ?>
                        <optgroup label="<?php echo $group; ?>">
                            <?php foreach ( $config as $key => $data ) : ?>
                                <?php $label = isset( $data['label'] ) ? $data['label'] : $key ; ?>
                                <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <a class="remove-bundle"><?php esc_html_e( 'Delete Bundle', 'wp-cfm' ); ?></a>
            <a class="download-bundle hidden"><?php esc_html_e( 'Download', 'wp-cfm' ); ?></a>
            <div class="clear"></div>
        </div>
    </div>
</div>
</div>

<!-- diff modal -->

<div class="media-modal">
    <a class="media-modal-close"><span class="media-modal-icon"></span></a>
    <div class="media-modal-content">
        <div class="media-frame">
            <div class="media-frame-title">
                <h1><?php esc_html_e( 'Diff Viewer', 'wp-cfm' ); ?></h1>
            </div>
            <div class="media-frame-router">
                <div class="media-router">
                    <?php printf(esc_html__( 'Compare file and database versions. Changes marked in %1$sGREEN%2$s exist in the database but not the filesystem.', 'wp-cfm' ),'<span style="background:#c6ffc6">','</span>'); ?>
                </div>
            </div>
            <div class="media-frame-content">
                <div class="wpcfm-diff">
                    <pre class="original"></pre>
                    <pre class="changed"></pre>
                    <pre class="diff"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="media-modal-backdrop"></div>
