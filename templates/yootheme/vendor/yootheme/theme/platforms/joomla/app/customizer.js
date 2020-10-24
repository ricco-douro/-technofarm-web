jQuery(function ($) {

    $('head').append('\
    <style>\
        .tm-editor {\
            width: 100%;\
            height: 400px;\
            background: #FFF;\
            border: solid #DDD 1px;\
            margin-top: 20px;\
            text-align: center;\
        }\
        .tm-active .label,\
        .tm-active .CodeMirror,\
        .tm-active #jform_articletext,\
        .tm-active #editor-xtd-buttons {\
            display: none;\
        }\
        .tm-link {\
            display: inline-block;\
            margin: 20px auto;\
            color: #888;\
            cursor: pointer;\
        }\
        .tm-button {\
            display: block;\
            box-sizing: border-box;\
            width: 280px;\
            max-width: 100%;\
            margin: 175px auto 0 auto;\
            padding: 20px 30px;\
            border-radius: 2px;\
            background: linear-gradient(140deg, #FE67D4, #4956E3);\
            box-shadow: inset 0 0 1px 0 rgba(0,0,0,0.5);\
            line-height: 10px;\
            vertical-align: middle;\
            color: #fff !important;\
            font-size: 11px;\
            font-weight: bold;\
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;\
            text-align: center;\
            text-decoration: none !important;\
            text-transform: uppercase;\
            letter-spacing: 2px;\
            -webkit-font-smoothing: antialiased;\
        }\
    </style>');

    var config = window.$customizer, dirty = false, setDirty = function() { dirty = true; };
    var article = $('#jform_articletext'), active = article.length && article.val().match(/<!-- {/);
    var editor = $('.editor'), editors = Joomla.editors.instances, wrapper = editor.length ? editor.parent() : article.parent();

    if (config.context == 'com_content.article') {

        var buttons = $('#editor-xtd-buttons');

        if (!buttons.length) {
            buttons = $('<div id="editor-xtd-buttons" class="btn-toolbar pull-left">').insertBefore('.toggle-editor');
        }

        buttons.prepend('<a class="btn js-customizer-open" href="#"><span class="icon-star"></span> Page Builder</a>');
    }

    $(wrapper).append('<div class="tm-editor" hidden><a href="#" class="tm-button js-customizer-open">Page Builder</a><a class="tm-link">&#8592; Back to Editor</a></div>');

    $('.tm-link').click(function (e) {
        e.preventDefault(); init();
    });

    function init(active) {

        $('.tm-editor').attr('hidden', !active);
        $('.editor').attr('hidden', !!active);
        $(wrapper).toggleClass('tm-active', !!active);

        // fix codemirror
        if (editors.jform_articletext) {
            editors.jform_articletext.scrollIntoView();
        }
    }

    init(active);

    $('.js-customizer-open')
        .attr('href', config.url + '&return=' + encodeURIComponent(window.location.href))
        .on('click', function (e) {
            if (dirty && !window.confirm('The changes you made will be lost if you navigate away from this page.')) {
                e.preventDefault();
            }
        });

    $('[name="adminForm"]').on('change', setDirty);

    if (window.tinyMCE && window.tinyMCE.editors) {

        // tinyMCE 4.x
        if (tinyMCE.on) {

            tinyMCE.on('AddEditor', function(e) {
                e.editor.on('change', setDirty);
            });

            tinyMCE.editors.forEach(function(ed){
                ed.on('change', setDirty);
            });
        }

        // tinyMCE 3.x
        if (tinyMCE.onAddEditor) {

            tinyMCE.onAddEditor.add(function(mgr, editor) {
                editor.onChange.add(setDirty);
            });

            tinyMCE.editors.forEach(function(ed){
                ed.onChange.add(setDirty);
            });
        }
    }

});
