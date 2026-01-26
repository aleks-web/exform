


document.addEventListener('alpine:init', () => {
    Alpine.store('apiThemes', {
        themes: [],
        activeTheme: null,
        isLoading: false,
        editorInit() {
            document.querySelectorAll('.exform-content__file').forEach((el, i) => {
                if (!window.editors) {
                    window.editors = new Map();
                }

                let editor = ace.edit(el);
                editor.setTheme("ace/theme/chrome");
                editor.session.setMode("ace/mode/" + el.dataset.type);

                window.editors.set(el.dataset.theme + '_' + el.dataset.file, editor);
            });
        },
        async saveFiles() {
            if (window.editors) {
                for (let theme of this.themes) {
                    for (let editor of window.editors) {
                        let file = editor[0].split('_')[1];
                        let themeName = theme.name + '_' + file;
                        let fileContent = editor[1].getValue();

                        let data = await fetch(window.location.origin + '/exform/admin/api/saveform.php', {
                            method: 'POST',
                            body: JSON.stringify({ 'test': 23423 })
                        });
                        data = await data.json();

                        console.log(data);

                    }
                }
            }
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
    });


    Alpine.data('accordion', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        }
    }));

});