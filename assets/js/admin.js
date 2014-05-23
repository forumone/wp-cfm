(function($) {
    $(function() {

        var bundle_count = 0;

        // Load
        $.post(ajaxurl, {
            action: 'wpcfm_load'
        }, function(response) {
            $.each(response.bundles, function(idx, obj) {
                var $this = $('.bundles-hidden .wpcfm-bundle').clone();
                $this.attr('data-id', bundle_count);
                $this.find('.bundle-label').val(obj.label);
                $this.find('.bundle-name').val(obj.name);
                $this.find('.bundle-select').val(obj.config);

                $('.wpcfm-bundles').append($this);
                $('.wpcfm-content-bundles .wpcfm-tabs ul').append('<li data-id="' + bundle_count + '">' + obj.label + '</li>');
                bundle_count++;
            });

            // Set the UI elements
            $('.wpcfm-content-bundles .wpcfm-tabs li:first').click();
        }, 'json');


        // Save
        $(document).on('click', '.wpcfm-save', function() {
            $('.wpcfm-response').html('Saving...');
            $('.wpcfm-response').show();

            var data = {
                'bundles': []
            };

            $('.wpcfm-bundles .wpcfm-bundle').each(function() {
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
            $('.wpcfm-bundles .wpcfm-bundle:last').attr('data-id', bundle_count);
            $('.wpcfm-content-bundles .wpcfm-tabs ul').append('<li data-id="' + bundle_count + '">New bundle</li>');
            $('.wpcfm-content-bundles .wpcfm-tabs li:last').click();
            bundle_count++;
        });


        // "Delete bundle" button
        $(document).on('click', '.remove-bundle', function() {
            if (confirm('You are about to delete this bundle. Continue?')) {
                var id = $(this).closest('.wpcfm-bundle').attr('data-id');
                $(this).closest('.wpcfm-bundle').remove();
                $('.wpcfm-content-bundles .wpcfm-tabs li[data-id=' + id + ']').remove();
                $('.wpcfm-content-bundles .wpcfm-tabs li:first').click();
            }
        });


        // "Push" button
        $(document).on('click', '.push-bundle', function() {
            $('.wpcfm-response').html('Pushing from DB to file...');
            $('.wpcfm-response').show();

            $.post(ajaxurl, { 'action': 'wpcfm_push' }, function(response) {
                $('.wpcfm-response').html(response);
            });
        });


        // "Pull" button
        $(document).on('click', '.pull-bundle', function() {
            if (confirm('Import file settings to DB?')) {
                $('.wpcfm-response').html('Pulling from file into DB...');
                $('.wpcfm-response').show();

                $.post(ajaxurl, { 'action': 'wpcfm_pull' }, function(response) {
                    $('.wpcfm-response').html(response);
                });
            }
        });


        // Sidebar link click
        $(document).on('click', '.wpcfm-tabs li', function() {
            var id = $(this).attr('data-id');
            $(this).siblings('li').removeClass('active');
            $(this).addClass('active');
            $bundle = $('.wpcfm-bundle[data-id=' + id + ']');
            $('.wpcfm-bundle').hide();
            $bundle.show();

            // Trigger jQuery Multi Select
            $bundle.find('.bundle-select').multipleSelect({
                width: 500,
                multiple: true,
                multipleWidth: 220,
                keepOpen: true,
                isOpen: true
            });

            // Make sure the content area is tall enough
            var nav_height = $(this).closest('.wpcfm-tabs').height();
            var content_height = $bundle.height();
            if (content_height < nav_height) {
                $bundle.height(nav_height - 40);
            }
        });


        // Change the sidebar link label
        $(document).on('keyup', '.bundle-label', function() {
            var val = $(this).val();
            var $tab = $(this).closest('.wpcfm-content').find('.wpcfm-tabs li.active');
            $tab.html(val);

            val = $.trim(val).toLowerCase();
            val = val.replace(/[^\w- ]/g, ''); // strip invalid characters
            val = val.replace(/[- ]/g, '_'); // replace space and hyphen with underscore
            val = val.replace(/[_]{2,}/g, '_'); // strip consecutive underscores
            $(this).siblings('.bundle-name').val(val);
        });
    });
})(jQuery);