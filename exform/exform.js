class ExformManager {
    #exformUrn;
    #configServer;
    #forms = [];

    constructor(exformUrn = '/exform') {
        if (!ExformManager.instance) {
            ExformManager.instance = this;
            window.exform = ExformManager.instance;
        }

        this.#exformUrn = exformUrn;
        return ExformManager.instance;
    }

    async init() {
        let result = await fetch(this.#exformUrn + '/api/config.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });

        this.#configServer = await result.json();
    }

    getConfigServer() {
        return this.#configServer;
    }

    addForm(form) {
        this.#forms.push(form);
    }

    getForms() {
        return this.#forms;
    }

    getFormByName(themeName) {
        for (let theme of Object.values(this.getConfigServer().themes_array)) {
            if (theme.name === themeName) {
                return theme; 
            }
        }
    }

    setExformUrn(urn) {
        this.#exformUrn = urn;
    }

    getExformUrn() {
        return this.#exformUrn;
    }
}

class Exform {

  constructor(themeObjectFromConfigServer = null) {
    this.theme = themeObjectFromConfigServer;
  }

  async init() {
    if (!window.exform) {
        window.exform = new ExformManager();
        await window.exform.init();
    }
    window.exform.addForm(this);
    this.includeCss();
    this.includeYaCaptchaScript();
    this.initForm();
  }

  // Подключение стилей формы
  includeCss() {
    let cssThemePath = window.exform.getExformUrn() + '/themes/' + this.theme.name + '/assets/style.css';

    let head = document.querySelector('head');
    let findLinkResult = head.querySelectorAll(`link[href="${cssThemePath}"]`).length;

    if (!findLinkResult) {
        head.insertAdjacentHTML('beforeend', `<link rel="stylesheet" type="text/css" href="${cssThemePath}" />`);
    }
  }

  // Подключение яндекс капчи
  includeYaCaptchaScript() {
    let head = document.querySelector('head');
    const scriptsResult = document.querySelectorAll('script[src^="https://smartcaptcha"]').length;

    if (!!this.theme.config.ya_captha && !scriptsResult) {
        head.insertAdjacentHTML('beforeend', '<script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>');
    }
  }

  // Отображение формы
  initForm() {
    let selector = document.querySelector(this.theme.config.selector);

    if (!!this.theme.config.is_modal && selector) {
        selector.addEventListener('click', e => {
            console.log(e);
        });
    }

    
  }

}

/*
    Инициализируем менеджер exform
*/
window.exform = new ExformManager();
await window.exform.init();

/*
    Создаем формы
*/
Object.values(window.exform.getConfigServer().themes_array).forEach(async theme => {
    let exf = new Exform(theme);
    await exf.init();
});