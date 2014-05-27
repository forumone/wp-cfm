(function($) {
    $(function() {

        // Load
        $.post(ajaxurl, {
            action: 'wpcfm_load'
        }, function(response) {
            $.each(response.bundles, function(idx, obj) {
                var $this = $('.bundles-hidden .bundle-row').clone();
                $this.find('.bundle-label').val(obj.label);
                $this.find('.bundle-name').val(obj.name);
                $this.find('.bundle-select').val(obj.config);
                $this.find('.bundle-toggle').html(obj.label);
                $this.attr('data-bundle', obj.name);
                $('.wpcfm-bundles').append($this);

                // Trigger jQuery Multi Select
                $this.find('.bundle-select').multipleSelect({
                    width: 500,
                    multiple: true,
                    multipleWidth: 220,
                    keepOpen: true,
                    isOpen: true
                });
            });
        }, 'json');


        // Save
        $(document).on('click', '.wpcfm-save', function() {
            $('.wpcfm-response').html('Saving...');
            $('.wpcfm-response').show();

            var data = {
                'bundles': []
            };

            $('.wpcfm-bundles .bundle-row:not(.row-default)').each(function() {
                var $this = $(this);

                var obj = {
                    'label': $this.find('.bundle-label').val(),
                    'name': $this.find('.bundle-name').val(),
                    'config': $this.find('.bundle-select').multipleSelect('getSelects')
                };

                data.bundles.push(obj);
            });

            $.post(ajaxurl, {
                'action': 'wpcfm_save',
                'data': JSON.stringify(data)
            }, function(response) {
                $('.wpcfm-response').html(response);
            });
        });


        // "Add bundle" button
        $(document).on('click', '.add-bundle', function() {
            var html = $('.bundles-hidden').html();
            $('.wpcfm-bundles').append(html);
        });


        // Toggle bundle details
        $(document).on('click', '.bundle-toggle', function() {
            $(this).closest('.bundle-row').toggleClass('active');
        });


        // "Delete bundle" button
        $(document).on('click', '.remove-bundle', function() {
            if (confirm('You are about to delete this bundle. Continue?')) {
                $(this).closest('.bundle-row').remove();
            }
        });


        // "Push" button
        $(document).on('click', '.push-bundle', function() {
            $('.wpcfm-response').html('Pushing from DB to file...');
            $('.wpcfm-response').show();
            var bundle_name = $(this).closest('.bundle-row').attr('data-bundle');

            $.post(ajaxurl, {
                'action': 'wpcfm_push',
                'data': { 'bundle_name': bundle_name }
            }, function(response) {
                $('.wpcfm-response').html(response);
            });
        });


        // "Pull" button
        $(document).on('click', '.pull-bundle', function() {
            if (confirm('Import file settings to DB?')) {
                $('.wpcfm-response').html('Pulling from file into DB...');
                $('.wpcfm-response').show();
                var bundle_name = $(this).closest('.bundle-row').attr('data-bundle');

                $.post(ajaxurl, {
                    'action': 'wpcfm_pull',
                    'data': { 'bundle_name': bundle_name }
                }, function(response) {
                    $('.wpcfm-response').html(response);
                });
            }
        });


        // "Diff" button
        $(document).on('click', '.diff-bundle', function() {
            var bundle_name = $(this).closest('.bundle-row').attr('data-bundle');
            $.post(ajaxurl, {
                'action': 'wpcfm_diff',
                'data': { 'bundle_name': bundle_name }
            }, function(response) {
                $('.wpcfm-diff .original').text(response.file);
                $('.wpcfm-diff .changed').text(response.db);
                $('.wpcfm-diff').prettyTextDiff({
                    cleanup: true
                });
                $('.trigger-modal').click();
            }, 'json');
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
            $(this).closest('.bundle-row').find('.bundle-toggle').html(label);
        });
    });
})(jQuery);