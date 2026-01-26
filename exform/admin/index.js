


document.addEventListener('alpine:init', () => {
    Alpine.store('apiThemes', {
        themes: [],
        activeTheme: null,
        isLoading: false,
        globalConfig: [],
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
            let errors = false;
            if (window.editors) {
                for (let editor of window.editors) {
                    let fileContent = editor[1].getValue();

                    let data = await fetch(window.location.origin + '/exform/admin/api/saveform.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json; charset=UTF-8' },
                        body: JSON.stringify({ content: fileContent, theme_and_file: editor[0] })
                    });
                    data = await data.json();

                    if (!data.success) {
                        errors = true;
                    }
                }

                if (!errors) {
                    alert('Формы сохранены');
                }
            }

            if (window.globalConfig) {
                let data = await fetch(window.location.origin + '/exform/admin/api/saveconfig.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json; charset=UTF-8' },
                    body: JSON.stringify({ content: window.globalConfig.getValue() })
                });
                data = await data.json();

                if (data.success) {
                    alert('Глобальные настройки сохранены');
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

            document.addEventListener('keydown', async (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    await this.saveFiles();
                }
            });
        },
        async fetchGlobalConfig() {
            setTimeout(async () => {
                this.isLoading = true;
                let data = await fetch(window.location.origin + '/exform/admin/api/globalconfig.php', { method: 'POST' });
                data = await data.json();
                this.globalConfig = data.data;
                this.isLoading = false;


                let el = document.querySelector('.global-config-editor');
                el.innerHTML = this.globalConfig;
                let editor = ace.edit(el);
                editor.setTheme("ace/theme/chrome");
                editor.session.setMode("ace/mode/ini");
                window.globalConfig = editor;
                
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