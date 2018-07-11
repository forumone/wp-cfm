<?php

	/**
	 * @codeCoverageIgnore
	 */
	function apache_mod_loaded() {
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function apache_get_version() {
		return true;
	}

	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../vendor/antecedent/patchwork/Patchwork.php';
	require_once __DIR__ . '/../src/index.php';
