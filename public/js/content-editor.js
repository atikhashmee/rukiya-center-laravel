/**
 * Runs inside the preview frame of the admin's visual editor.
 * Double-click any highlighted text to edit it; click a highlighted image to replace it.
 * Changes are reported to the admin window, which does the saving.
 */
(function () {
    'use strict';

    var send = function (message) {
        if (window.parent !== window) {
            window.parent.postMessage(Object.assign({ source: 'content-editor' }, message), '*');
        }
    };

    var hint = function (text) {
        var el = document.querySelector('.cf-hint');
        if (!el) {
            el = document.createElement('div');
            el.className = 'cf-hint';
            document.body.appendChild(el);
        }
        el.textContent = text;
        clearTimeout(el._timer);
        el._timer = setTimeout(function () { el.remove(); }, 2600);
    };

    var valueOf = function (field) {
        return field.dataset.cfType === 'html' ? field.innerHTML.trim() : field.innerText.trim();
    };

    var startEditing = function (field) {
        if (field.getAttribute('contenteditable') === 'true') return;

        field.dataset.cfOriginal = valueOf(field);
        field.setAttribute('contenteditable', 'true');
        field.focus();

        var range = document.createRange();
        range.selectNodeContents(field);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        hint('Editing — click outside or press Esc when done');
    };

    var stopEditing = function (field, revert) {
        if (field.getAttribute('contenteditable') !== 'true') return;

        if (revert) {
            if (field.dataset.cfType === 'html') field.innerHTML = field.dataset.cfOriginal || '';
            else field.innerText = field.dataset.cfOriginal || '';
        }

        field.setAttribute('contenteditable', 'false');

        var value = valueOf(field);
        if (!revert && value !== field.dataset.cfOriginal) {
            send({ type: 'change', page: field.dataset.cfPage, key: field.dataset.cfKey, fieldType: field.dataset.cfType, value: value });
        }
    };

    document.addEventListener('dblclick', function (event) {
        var field = event.target.closest('.cf-editable');
        if (field) {
            event.preventDefault();
            startEditing(field);
        }
    });

    document.addEventListener('focusout', function (event) {
        var field = event.target.closest && event.target.closest('.cf-editable');
        if (field) stopEditing(field, false);
    });

    document.addEventListener('keydown', function (event) {
        var field = event.target.closest && event.target.closest('.cf-editable');
        if (!field) return;

        if (event.key === 'Escape') {
            event.preventDefault();
            stopEditing(field, true);
        }
        // Single-line fields should not gain line breaks
        if (event.key === 'Enter' && field.dataset.cfType !== 'html') {
            event.preventDefault();
            field.blur();
        }
    });

    // Links must not navigate away mid-edit.
    document.addEventListener('click', function (event) {
        var image = event.target.closest('img[src*="cf="]');
        if (image) {
            event.preventDefault();
            var marker = (image.getAttribute('src').split('cf=')[1] || '').split('&')[0];
            var parts = decodeURIComponent(marker).split(':');
            send({ type: 'image', page: parts[0], key: parts[1] });
            return;
        }

        var link = event.target.closest('a');
        if (link && !event.target.closest('.cf-editable')) {
            event.preventDefault();
            hint('Links are disabled while editing');
        }
    }, true);

    var markImages = function () {
        document.querySelectorAll('img[src*="cf="]').forEach(function (image) {
            image.classList.add('cf-image-editable');
            image.title = 'Click to replace this image';
        });
    };

    var announce = function () {
        markImages();
        var fields = Array.prototype.map.call(document.querySelectorAll('.cf-editable'), function (field) {
            return { page: field.dataset.cfPage, key: field.dataset.cfKey, type: field.dataset.cfType };
        });
        send({ type: 'ready', fields: fields, images: document.querySelectorAll('img[src*="cf="]').length });
    };

    // The admin can ask for a field to be highlighted and scrolled into view.
    window.addEventListener('message', function (event) {
        var data = event.data || {};
        if (data.source !== 'content-admin') return;

        if (data.type === 'focus') {
            var field = document.querySelector('.cf-editable[data-cf-key="' + data.key + '"]');
            if (field) {
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                startEditing(field);
            }
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', announce);
    } else {
        announce();
    }
})();
