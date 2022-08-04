var uploader;
function upload_image(id) {

  //Extend the wp.media object
  uploader = wp.media.frames.file_frame = wp.media({
    title: 'Choose Image',
    button: {
      text: 'Choose Image'
    },
    multiple: false,
    states: [
      new wp.media.controller.Library({
          title:     'Select and Crop', // l10n.chooseImage,
          library:   wp.media.query({ type: 'image' }),
          multiple:  false,
          date:      false,
          priority:  20,
          suggestedWidth: 300,
          suggestedHeight: 200
      }),
      new wp.media.controller.CustomizeImageCropper({ 
          imgSelectOptions: myTheme_calculateImageSelectOptions,
          control: cropControl
      })
  ]
  });

  //When a file is selected, grab the URL and set it as the text field's value
  uploader.on('select', function() {
    attachment = uploader.state().get('selection').first().toJSON();
    console.log(attachment);
    var url = attachment['url'];
    var att_id = attachment['id'];
    jQuery('#'+id).val(att_id);
    jQuery('#'+id+'_preview').css('background-image', 'url('+url+')');
  });

  //Open the uploader dialog
  uploader.open();
}
jQuery(function($) {

    function legendary_toolkit_calculateImageSelectOptions(attachment, controller) {

        var control = controller.get( 'control' );

        var flexWidth = !! parseInt( control.params.flex_width, 10 );
        var flexHeight = !! parseInt( control.params.flex_height, 10 );

        var realWidth = attachment.get( 'width' );
        var realHeight = attachment.get( 'height' );

        var xInit = parseInt(control.params.width, 10);
        var yInit = parseInt(control.params.height, 10);

        var ratio = xInit / yInit;

        // controller.set( 'canSkipCrop', ! control.mustBeCropped( flexWidth, flexHeight, xInit, yInit, realWidth, realHeight ) );

        // skip cropping asset
        controller.set( 'canSkipCrop', true);

        var xImg = xInit;
        var yImg = yInit;

        if ( realWidth / realHeight > ratio ) {
            yInit = realHeight;
            xInit = yInit * ratio;
        } else {
            xInit = realWidth;
            yInit = xInit / ratio;
        }        

        var x1 = ( realWidth - xInit ) / 2;
        var y1 = ( realHeight - yInit ) / 2;        

        var imgSelectOptions = {
            handles: true,
            keys: true,
            instance: true,
            persistent: true,
            imageWidth: realWidth,
            imageHeight: realHeight,
            minWidth: xImg > xInit ? xInit : xImg,
            minHeight: yImg > yInit ? yInit : yImg,            
            x1: x1,
            y1: y1,
            x2: xInit + x1,
            y2: yInit + y1
        };

        return imgSelectOptions;
    }  

    function legendary_toolkit_setImageFromURL(url, attachmentId, width, height, handle) {
        var choice, data = {};

        data.url = url;
        data.thumbnail_url = url;
        data.timestamp = _.now();
        if (attachmentId) {
            data.attachment_id = attachmentId;
        }

        if (width) {
            data.width = width;
        }

        if (height) {
            data.height = height;
        }

        $("#"+handle).val( attachmentId );
        $("#"+handle+"_preview").css("background-image", 'url(' + url +')');
    }

    function legendary_toolkit_setImageFromAttachment(attachment, handle) {
        $("#"+handle).val( attachment.id );
        $("#"+handle+"_preview").css("background-image", 'url(' + attachment.url +')');
    }

    var mediaUploader;

    $(".btn-upload").on("click", function(e) {

        e.preventDefault(); 

        var handle = $(this).attr('data-id');

        /* We need to setup a Crop control that contains a few parameters
        and a method to indicate if the CropController can skip cropping the image.
        In this example I am just creating a control on the fly with the expected properties.
        However, the controls used by WordPress Admin are api.CroppedImageControl and api.SiteIconControl
        */

        var cropControl = {
            id: "control-id",
            params : {
                flex_width : false,  // set to true if the width of the cropped image can be different to the width defined here
                flex_height : true, // set to true if the height of the cropped image can be different to the height defined here
                width : 300,  // set the desired width of the destination image here
                height : 200, // set the desired height of the destination image here
            }
        };

        cropControl.mustBeCropped = function(flexW, flexH, dstW, dstH, imgW, imgH) {

            // If the width and height are both flexible
            // then the user does not need to crop the image.

            if ( true === flexW && true === flexH ) {
                return false;
            }

            // If the width is flexible and the cropped image height matches the current image height, 
            // then the user does not need to crop the image.
            if ( true === flexW && dstH === imgH ) {
                return false;
            }

            // If the height is flexible and the cropped image width matches the current image width, 
            // then the user does not need to crop the image.        
            if ( true === flexH && dstW === imgW ) {
                return false;
            }

            // If the cropped image width matches the current image width, 
            // and the cropped image height matches the current image height
            // then the user does not need to crop the image.               
            if ( dstW === imgW && dstH === imgH ) {
                return false;
            }

            // If the destination width is equal to or greater than the cropped image width
            // then the user does not need to crop the image...
            if ( imgW <= dstW ) {
                return false;
            }

            return true;        

        };      

        /* NOTE: Need to set this up every time instead of reusing if already there
                as the toolbar button does not get reset when doing the following:

                mediaUploader.setState('library');
                mediaUploader.open();

        */       

        mediaUploader = wp.media({
            button: {
                text: 'Select and Crop', // l10n.selectAndCrop,
                close: false
            },
            states: [
                new wp.media.controller.Library({
                    title:     'Select and Crop', // l10n.chooseImage,
                    library:   wp.media.query({ type: 'image' }),
                    multiple:  false,
                    date:      false,
                    priority:  20,
                    suggestedWidth: 300,
                    suggestedHeight: 200
                }),
                new wp.media.controller.CustomizeImageCropper({ 
                    imgSelectOptions: legendary_toolkit_calculateImageSelectOptions,
                    control: cropControl
                })
            ]
        });

        mediaUploader.on('cropped', function(croppedImage) {

            var url = croppedImage.url,
                attachmentId = croppedImage.id,
                w = croppedImage.width,
                h = croppedImage.height;

                legendary_toolkit_setImageFromURL(url, attachmentId, w, h, handle);            

        });

        mediaUploader.on('skippedcrop', function(selection) {

            var url = selection.get('url'),
                w = selection.get('width'),
                h = selection.get('height');

                legendary_toolkit_setImageFromURL(url, selection.id, w, h, handle);            

        });        

        mediaUploader.on("select", function() {

            var attachment = mediaUploader.state().get( 'selection' ).first().toJSON();

            if (     cropControl.params.width  === attachment.width 
                &&   cropControl.params.height === attachment.height 
                && ! cropControl.params.flex_width 
                && ! cropControl.params.flex_height ) {
                    legendary_toolkit_setImageFromAttachment( attachment, handle );
                mediaUploader.close();
            } else {
                mediaUploader.setState( 'cropper' );
            }

        });

        mediaUploader.open();

    });
});