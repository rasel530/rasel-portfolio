@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Rich-text editor shared by every admin form. Activates on any
    // <textarea class="rich-text">. Toolbar is limited to formatting the
    // server-side HtmlSanitizer keeps, so output is always safe to render.
    (function () {
        function initEditors() {
            if (typeof tinymce === 'undefined') {
                return setTimeout(initEditors, 50);
            }
            tinymce.init({
                selector: 'textarea.rich-text',
                menubar: false,
                statusbar: false,
                plugins: 'lists',
                toolbar: 'bold italic underline | bullist numlist',
                branding: false,
                promotion: false,
                valid_elements: 'p,strong,b,em,i,u,br,ul,ol,li',
                // Write edited HTML back into the underlying textarea so
                // normal form submission and "old()" repopulation work.
                setup: function (editor) {
                    editor.on('change', function () { editor.save(); });
                }
            });
        }
        initEditors();
    })();
</script>
@endpush
@endonce
