(function($) {
    $(function() {

        // Load
        $.post(compare_env.ajax_url, {
            action: 'wpcfm_load',
            compare_env: compare_env.env,
            _ajax_nonce: compare_env.wpcfm_ajax_nonce
        }, function(response) {
            $.each(response.data.bundles, function(idx, obj) {
                var $this = $('.bundles-hidden .bundle-row').clone();
                $this.find('.bundle-label').val(obj.label);
                $this.find('.bundle-name').val(obj.name);
                $this.find('.bundle-select').val(obj.config);
                $this.find('.bundle-toggle').html(obj.label);
                $this.attr('data-bundle', obj.name);

                if (obj.is_file) {
                    $this.find('.pull-bundle').removeClass('disabled');
                    $this.find('.download-bundle').attr('href', obj.url);
                    $this.find('.download-bundle').attr('download', obj.url.split('/').reverse()[0]);
                    $this.find('.download-bundle').removeClass('hidden');
                }

                if (obj.is_db) {
                    $this.find('.push-bundle').removeClass('disabled');
                }

                $('.wpcfm-bundles').append($this);

                // Trigger jQuery Multi Select
                $this.find('.bundle-select').multiSelect();
            });
        }, 'json').fail(function(response) {
            $('.wpcfm-response').html(response.responseJSON.data.message);
        });


        // Save
        $(document).on('click', '.wpcfm-save', function() {
            $('.wpcfm-response').html('Saving...');

            var data = {
                'bundles': []
            };

            $('.wpcfm-bundles .bundle-row:not(.row-all)').each(function() {
                var $this = $(this);

                var obj = {
                    'label': $this.find('.bundle-label').val(),
                    'name': $this.find('.bundle-name').val(),
                    'config': $this.find('.bundle-select').val()
                };

                data.bundles.push(obj);
            });

            $.post(compare_env.ajax_url, {
                action: 'wpcfm_save',
                compare_env: compare_env.env,
                _ajax_nonce: compare_env.wpcfm_ajax_nonce,
                data: JSON.stringify(data)
            }, function(response) {
                $('.wpcfm-bundles .bundle-row').removeClass('unsaved');
                $('.wpcfm-bundles .push-bundle').removeClass('disabled');
                $('.wpcfm-response').html(response.data.message);
            }).fail(function(response) {
                $('.wpcfm-response').html(response.responseJSON.data.message);
            });
        });


        // "Add bundle" button
        $(document).on('click', '.add-bundle', function() {
            var html = $('.bundles-hidden').html();
            $('.wpcfm-bundles').append(html);

            var $row = $('.wpcfm-bundles .bundle-row:last');
            $row.find('.bundle-select').multiSelect();
            $row.addClass('unsaved');
            $row.find('.bundle-toggle').trigger('click');
        });

        $(document).on('click', '.hide-registered', function(e) {
          e.preventDefault();
          var $row = $(this).closest('.bundle-row');
          $('.wpcfm-bundles').find('input[type=checkbox]:checked').each(function() {
            $row.find('input[type=checkbox][value="'+this.value+'"]').not(':checked').parent().hide();
        });
          $row.find('.hide-registered').hide();
          $row.find('.show-all').show();
      });
        $(document).on('click', '.show-all', function(e) {
          e.preventDefault();
          var $row = $(this).closest('.bundle-row');
          $row.find('input[type=checkbox]').parent().show();
          $row.find('.hide-registered').show();
          $row.find('.show-all').hide();
      });

        // Toggle bundle details
        $(document).on('click', '.bundle-row:not(.row-all) .bundle-toggle', function() {
            var $row = $(this).closest('.bundle-row');
            $row.toggleClass('active');
            $row.find('.bundle-row-inner').animate({ height: 'toggle' }, 150);
        });


        // "Delete bundle" button
        $(document).on('click', '.remove-bundle', function() {
            if (confirm('You are about to delete this bundle. Continue?')) {
                $(this).closest('.bundle-row').remove();
            }
        });


        // "Push" button
        $(document).on('click', '.push-bundle:not(.disabled)', function() {
            $('.wpcfm-response').html('Exporting to file...');
            var bundle_name = $(this).closest('.bundle-row').attr('data-bundle');

            $.post(compare_env.ajax_url, {
                action: 'wpcfm_push',
                compare_env: compare_env.env,
                _ajax_nonce: compare_env.wpcfm_ajax_nonce,
                data: { 'bundle_name': bundle_name }
            }, function(response) {
                $('.wpcfm-response').html(response.data.message);
            }).fail(function(response) {
                $('.wpcfm-response').html(response.responseJSON.data.message);
            });
        });


        // "Pull" button
        $(document).on('click', '.pull-bundle:not(.disabled)', function() {
            if (confirm('Import file settings to DB?')) {
                $('.wpcfm-response').html('Importing into DB...');
                var bundle_name = $(this).closest('.bundle-row').attr('data-bundle');

                $.post(compare_env.ajax_url, {
                    action: 'wpcfm_pull',
                    compare_env: compare_env.env,
                    _ajax_nonce: compare_env.wpcfm_ajax_nonce,
                    data: { 'bundle_name': bundle_name }
                }, function(response) {
                    $('.wpcfm-response').html(response.data.message);
                }).fail(function(response) {
                    $('.wpcfm-response').html(response.responseJSON.data.message);
                });
            }
        });


        // "Diff" button
        $(document).on('click', '.diff-bundle:not(.disabled)', function() {
            var bundle_name = $(this).closest('.bundle-row').attr('data-bundle');
            $.post(compare_env.ajax_url, {
                action: 'wpcfm_diff',
                compare_env: compare_env.env,
                _ajax_nonce: compare_env.wpcfm_ajax_nonce,
                data: { 'bundle_name': bundle_name }
            }, function(response) {
                if ('' != response.data.file) {
                    $('.wpcfm-diff .original').text(response.data.file);
                    $('.wpcfm-diff .changed').text(response.data.db);
                    $('.wpcfm-diff').prettyTextDiff();
                }
                $('.media-modal').show();
                $('.media-modal-backdrop').show();
            }, 'json').fail(function(response) {
                $('.wpcfm-response').html(response.responseJSON.data.message);
            });
        });


        // Close the Diff viewer
        $(document).on('click', '.media-modal-close', function() {
            $('.media-modal').hide();
            $('.media-modal-backdrop').hide();
        });


        // Change the sidebar link label
        $(document).on('keyup', '.bundle-label', function() {
            var label = $(this).val();
            var val = label;
            val = $.trim(val).toLowerCase();
            val = val.replace(/[^\w- ]/g, ''); // strip invalid characters
            val = val.replace(/[- ]/g, '_'); // replace space and hyphen with underscore
            val = val.replace(/[_]{2,}/g, '_'); // strip consecutive underscores
            $(this).siblings('.bundle-name').val(val);
            $(this).closest('.bundle-row').attr('data-bundle', val);
            $(this).closest('.bundle-row').find('.bundle-toggle').html(label);
        });

        // Toggle between environments.
        $('#wpcfm_env_switch').on('change', function() {
            window.location = window.location.href + '&compare_env=' + $(this).val();
        });


    });
})(jQuery);
