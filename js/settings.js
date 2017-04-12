/**
 * Created by Admin on 12.04.2017.
 */
jQuery('#delete-marker-icon').click(function (e) {
    e.preventDefault();
    jQuery.ajax({
        url: 'admin-post.php?page=gmh_settings&action=gmh_delete_marker',
        method: 'POST',
        data: {action: 'gmh_delete_marker'}
    }).done(function () {
        jQuery('#gmh-marker-icon').attr('src', '');
    });
});