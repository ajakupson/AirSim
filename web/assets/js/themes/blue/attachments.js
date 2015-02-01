define(
    'blue/attachments',
    ['jquery'],

    function($)
    {
        var attachmentsMenuTemplate =
            '<ul>' +
                '<li class = "has_sub attach_button attach_files_button">' +
                    'Attach file' +
                    '<ul><li class = "attach_button attach_images_button">' +
                        'Attach image' +
                        '<form class = "attach_images_form">' +
                            '<input type = "file" multiple = "" accept = "image/*" class = "input_file_transparent"' +
                                'name = "attached_images[]" id = "attached_images" value = ""/>' +
                        '</form>' +
                    '</li>' +
                    '<li class = "attach_button attach_documents_button">' +
                        'Attach documents' +
                        '<form class = "attach_documents_form">' +
                            '<input type = "file" multiple = "" accept = ".csv, application/msword,#}' +
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document,' +
                                'application/vnd.ms-excel, application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,' +
                                'text/plain, application/pdf, .zip, .rar, .7zip" class = "input_file_transparent"' +
                                'name = "attached_images[]" id = "attached_images" value = ""/>' +
                        '</form>' +
                    '</li></ul>' +
                '</li>' +
            '</ul>';
        // Default settings
        var settings = {};
        var defaults =
        {
            'context': null,
            'images': false,
            'imagesPreviewTarget': null,
            'imagesSavePath': null,
            'documents': false,
            'documentsPreviewTarget': null,
            'documentsSavePath': null,
            'audio': false,
            'audioPreviewTarget': null,
            'audioSavePath': null,
            'video': false,
            'videoPreviewTarget': null,
            'videoSavePath':null,
            'temporaryFilesPath': null
        };

        // Public functions
        var methods =
        {
            init: function(options)
            {
                return this.each(function()
                {
                    settings = $.extend
                    (true, {}, defaults, options);

                    $(this).data("settings", settings);

                    $(this).append(attachmentsMenuTemplate);
                });

            }
        };

        // Private functions


        $.fn.attachments = function(method)
        {
            if(methods[method])
            {
                return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
            }
            else if(typeof method === 'object' || !method)
            {
                return methods.init.apply(this, arguments);
            }
            else
            {
                $.error('Method with name ' + method + ' does not exist for jQuery.gallery');
            }
        };
    }
);