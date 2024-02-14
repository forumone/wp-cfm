module.exports = function (grunt) {

	require('load-grunt-tasks')(grunt);

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		composerBin: 'vendor/bin',

		shell: {
			phpcs: {
				options: {
					stdout: true
				},
				command: '<%= composerBin %>/phpcs'
			},

			phpcbf: {
				options: {
					stdout: true
				},
				command: '<%= composerBin %>/phpcbf'
			},

			phpstan: {
				options: {
					stdout: true
				},
				command: '<%= composerBin %>/phpstan analyze .'
			},

			phpunit: {
				options: {
					stdout: true
				},
				command: '<%= composerBin %>/phpunit'
			},
		},

		gitinfo: {
			commands: {
				'local.tag.current.name': ['name-rev', '--tags', '--name-only', 'HEAD'],
				'local.tag.current.nameLong': ['describe', '--tags', '--long']
			}
		},

		clean: {
			main: ['dist'] //Clean up build folder
		},

		copy: {
			// Copy the plugin to a versioned release directory
			main: {
				src: [
					'**',
					'!*.xml', '!*.log', //any config/log files
					'!node_modules/**', '!Gruntfile.js', '!package.json', '!package-lock.json', //npm/Grunt
					'!.wordpress-org/**', //wp-org assets
					'!dist/**', //build directory
					'!.git/**', //version control
					'!.github/**', //GitHub platform files
					'!tests/**', '!scripts/**', '!phpunit.xml', '!phpunit.xml.dist', //unit testing
					'!wordpress/**',
					'!.*', '!**/*~', //hidden files
					'!CONTRIBUTING.md',
					'!README.md',
					'!HOWTO.md',
					'!patches/**',
					'!phpcs.xml', '!phpcs.xml.dist', '!phpstan.neon.dist', '!phpstan-baseline.neon', '!grumphp.yml.dist', // CodeSniffer Configuration.
					'!codecov.yml', // Code coverage configuration.
					'!tools/**', '!dev-cli', '!docker-compose.yml', // Local Development/Build tools configuration.
					'!vendor/wpackagist-theme/**',
					'!wp-cli.yml'
				],
				dest: 'dist/',
				options: {
					processContentExclude: ['**/*.{png,gif,jpg,ico,mo}'],
				},
			}
		},

		addtextdomain: {
			options: {
				textdomain: 'wp-cfm',    // Project text domain.
			},
			update_all_domains: {
				options: {
					updateDomains: true
				},
				src: ['*.php', '**/*.php', '!node_modules/**', '!tests/**', '!tools/**', '!scripts/**', '!vendor/**', '!wordpress/**']
			},
		},

		wp_readme_to_markdown: {
			dest: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages',         // Where to save the POT file.
					exclude: [
						'node_modules/.*',				//npm
						'.wordpress-org/.*', 			//wp-org assets
						'dist/.*', 								//build directory
						'.git/.*', 								//version control
						'.github/.*',							//GitHub platform
						'tests/.*', 'scripts/.*',	//unit testing
						'vendor/.*', 							//composer
						'tools/.*'
					],                                // List of files or directories to ignore.
					mainFile: 'wp-cfm.php',                     // Main project file.
					potFilename: 'wp-cfm.pot',                  // Name of the POT file.
					potHeaders: {
						poedit: true,                   // Includes common Poedit headers.
						'report-msgid-bugs-to': 'https://github.com/forumon/wp-cfm/issues',
						'x-poedit-keywordslist': true   // Include a list of all possible gettext functions.
					},                                // Headers to add to the generated POT file.
					type: 'wp-plugin',                // Type of project (wp-plugin or wp-theme).
					updateTimestamp: true,            // Whether the POT-Creation-Date should be updated without other changes.
					updatePoFiles: true               // Whether to update PO files in the same directory as the POT file.
				}
			}
		},

		po2mo: {
			plugin: {
				src: 'languages/*.po',
				expand: true
			}
		},

		checkrepo: {
			deploy: {
				tagged: true, // Check that the last commit (HEAD) is tagged
				tag: {
					eq: '<%= pkg.version %>' // Check if highest repo tag is equal to pkg.version
				}
			}
		},

		checktextdomain: {
			options: {
				text_domain: 'wp-cfm',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_x:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				],
			},
			files: {
				src: [
					'**/*.php',
					'!node_modules/**',
					'!dist/**',
					'!tests/**',
					'!tools/**',
					'!vendor/**',
					'!wordpress/**',
					'!*~',
				],
				expand: true,
			},
		},

		// Minify JavaScript files.
		uglify: {
			options: {
				mangle: false
			},
			my_target: {
				files: {
					'assets/js/admin.min.js': ['assets/js/admin.js']
				}
			}
		},

		// Bump version numbers
		version: {
			class: {
				options: {
					prefix: "const VERSION = '"
				},
				src: ['<%= pkg.name %>.php']
			},
			header: {
				options: {
					prefix: '\\* Version:\\s+'
				},
				src: ['<%= pkg.name %>.php']
			},
			readme: {
				options: {
					prefix: 'Stable tag:\\s+'
				},
				src: ['readme.txt']
			}
		}

	});

	grunt.registerTask('phpcs', ['shell:phpcs']);
	grunt.registerTask('phpcbf', ['shell:phpcbf']);
	grunt.registerTask('phpstan', ['shell:phpstan']);
	grunt.registerTask('phpunit', ['shell:phpunit']);
	grunt.registerTask('i18n', ['addtextdomain', 'makepot', 'po2mo']);
	grunt.registerTask('readme', ['wp_readme_to_markdown']);
	grunt.registerTask('test', ['checktextdomain']);
	grunt.registerTask('build', ['gitinfo', 'test', 'i18n', 'readme', 'uglify']);
	grunt.registerTask('release', ['checkrepo', 'gitinfo', 'checktextdomain', 'clean', 'copy']);

};

