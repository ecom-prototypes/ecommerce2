/*!
    * Start Bootstrap - SB Admin v7.0.4 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2021 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

$(document).on('click', '.ordernote-edit', function () {
    var td = $(this).closest('td');
    var span = td.find('.text-value');
    var input = td.find('.edit-input');
    var save = td.find('.ordernote-save');

    input.val(span.text());
    span.hide();
    $(this).hide();
    save.show();
    input.show().focus();
});

$(document).on('click', '.ordernote-save', function () {
    var td = $(this).closest('td');
    var span = td.find('.text-value');
    var input = td.find('.edit-input');
    var edit = td.find('.ordernote-edit');
    var newValue = input.val();

    $.post("view-sold-products.php", {
        oid: td.data('id'),
        updateordernote: 'true',
        ordernote: newValue
    });


    span.text(newValue);
    input.hide();
    $(this).hide();
    edit.show();
    span.show();
});
