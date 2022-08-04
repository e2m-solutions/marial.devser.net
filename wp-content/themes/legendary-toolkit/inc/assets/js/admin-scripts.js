function open_settings_tab(evt, tabName) {
    evt.preventDefault();
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
    
    // push the anchor to the window location
    window.location.href = "#"+tabName;

}

// reset window to top on click
jQuery('.tablinks').on('click', function(e){
    if(window.scrollY > 1){
        window.scroll(0, 0)
    }
});


jQuery(document).ready(function( $ ) {

    // after settings page loads, check to see which tab was visited and display it
    let default_tab = 'legendary_toolkit_general_tab';
    let default_page = 'legendary_toolkit_general';

    if(window.location.hash){
        url = window.location.href;
        default_tab = url.substring(url.indexOf("#")+1);
        default_page = url.substring(url.indexOf("#")+1);
    }
    // reset window to top on loac and set display and active tab highlight
    window.scroll(0, 0)
    document.getElementById(default_page).style.display = "block";
    document.getElementById(default_page).addClass = "active";

    // show the save button
    $('#submit').removeClass('hidden');

    $('[name="theme_options[footer_columns]"]').on('input', function(ev) {
        var num_columns = $(this).val();
        $('[id^="footer_column_row_"]').each(function(i,el) {
            var id = $(el).attr('id');
            var col_num = id[id.length -1];
            if (num_columns < col_num) {
                $(el).addClass('hidden');
            }
            else {
                $(el).removeClass('hidden');
            }
            
        });
    });
    $('[name="theme_options[page_title]"]').on('click', function(ev) {
        if ($(this).is(':checked')) {
            $('#page_title_content_row').removeClass('hidden');
        }
        else {
            $('#page_title_content_row').addClass('hidden');
        }
    });
    $('[name="theme_options[transparent_header]"]').on('click', function(ev) {
        if ($(this).is(':checked')) {
            $('#header_background_row').addClass('hidden');
        }
        else {
            $('#header_background_row').removeClass('hidden');
        }
    });
    $(document).on("submit", "form#legendary_toolkit_form", function(event) {
        $('.save-toast').hide();
        $('.save-toast.save-loading').fadeIn();
        var btn = $(document.activeElement);
        btn.prop('disabled', true);
        var name =  btn.attr("name");
        if (name == "submit") {
            event.preventDefault();
            var settings = $(this).serialize();
            console.log('settings: ', settings)
            $.post( 'options.php', settings ).error( 
                function() {
                    $('.save-toast').hide();
                    $('.save-toast.save-loading').fadeOut();
                    $('.save-toast.save-success').hide();
                    $('.save-toast.save-error').fadeIn();
                    $('.save-toast.save-error').delay(3000).fadeOut();
                }).success( function() {
                    $('.save-toast').hide();
                    $('.save-toast.save-loading').fadeOut();
                    $('.save-toast.save-error').hide();
                    $('.save-toast.save-success').fadeIn();
                    $('.save-toast.save-success').delay(3000).fadeOut();
                    btn.prop('disabled', false);
                });
            return false; 
        }
   });	

    // ADD REPEATER FOR SIDEBAR CONTENT
    var sidebar_id = 1;
    $(document).on('click', '#add_sidebar', function(e) {
        e.preventDefault();
        var editor_id = 'sidebar_editor_' + sidebar_id;
        var sidebar_markup = `
            <tr valign="top">
                <th scope="row">Sidebar Content<button class="right button button-flat delete-sidebar delete-button" data-id="${editor_id}">Remove Sidebar <span class="dashicons dashicons-trash"></span></button></th>
            </tr>
            <tr>
                <td>
                    <textarea class="wp-editor-area" name="${editor_id}" id="${editor_id}"></textarea>
                    <hr/>
                </td>
            </tr>
        `;
        $('#sidebar_content_repeater').append(sidebar_markup);
        wp.editor.initialize(editor_id, {
            tinymce: {
                wpautop: true,
                plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
                toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
                toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
            },
            quicktags: true,
            mediaButtons: true
        });
        sidebar_id++;
    });
    $(document).on('click', '.delete-sidebar', function(e){
        e.preventDefault();
    });
});