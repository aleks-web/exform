


document.addEventListener('alpine:init', () => {
    Alpine.store('apiThemes', {
        themes: [],
        activeTheme: null,
        isLoading: false,
        editorInit() {
            document.querySelectorAll('.exform-content__tab').forEach((el, i) => {
                let editor = ace.edit(el);
                editor.setTheme("ace/theme/monokai");
                editor.session.setMode("ace/mode/php");
            });
        },
        async fetchThemes() {
            setTimeout(async () => {
                this.isLoading = true;
                let data = await fetch(window.location.origin + '/exform/admin/api', { method: 'POST' });
                data = await data.json();
                this.themes = data.data;
                if (Object.values(data.data).length > 0) { this.activeTheme = Object.values(data.data)[0].name };
                this.isLoading = false;
            }, 500);
        }
    })

});