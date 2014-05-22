<?php
$configuration = $this->registry->get_configuration_items();
$namespaces = $this->registry->get_namespaces();
//echo '<pre>';var_dump($configuration);echo '</pre>';
?>

<div class="wrap">
    <h2>WP-CFM</h2>
    <?php foreach ( $configuration as $namespace => $settings ) : ?>
    <h3><?php echo $namespaces[ $namespace ]; ?></h3>
    <?php foreach ( $settings as $key => $val ) : ?>
    <div><?php echo $key; ?></div>
    <?php endforeach; ?>
    <?php endforeach; ?>
</div>
