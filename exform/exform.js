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
        this.includeMainCss();
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
        for (let theme of Object.values(this.getConfigServer().themes)) {
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

    // Подключение главных стилей
    includeMainCss() {
        let cssPath = this.getExformUrn() + '/exform.css';

        let head = document.querySelector('head');
        let findLinkResult = head.querySelectorAll(`link[href="${cssPath}"]`).length;

        if (!findLinkResult) {
            head.insertAdjacentHTML('beforeend', `<link rel="stylesheet" type="text/css" href="${cssPath}" />`);
        }
    }
}

class Exform {
  #zIndex;

  constructor(themeObjectFromConfigServer = null) {
    this.theme = themeObjectFromConfigServer;
    this.#zIndex = this.findHighestZIndex();
  }

  async init() {
    if (!window.exform) {
        window.exform = new ExformManager();
        await window.exform.init();
    }
    window.exform.addForm(this);
    this.includeCss();
    this.includeYaCaptchaScript();
    await this.initForm();
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
    console.log(this.theme);
    let head = document.querySelector('head');
    const scriptsResult = document.querySelectorAll('script[src^="https://smartcaptcha"]').length;

    if (!!this.theme.config.ya_captha && !scriptsResult) {
        head.insertAdjacentHTML('beforeend', '<script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>');
    }
  }

  // Отображение формы
  async initForm() {
    let selector = document.querySelector(this.theme.config.selector);

    if (!!this.theme.config.is_modal && selector) {
        selector.addEventListener('click', async e => {
            this.createBgModal();
            await this.getAndInsertForm();
        });
    }
  }

  // Создание заднего фона
  createBgModal() {
    let bgResult = document.querySelectorAll('.bg-exform').length;
    let zIndex = this.#zIndex + 10;

    if (!bgResult) {
        document.body.insertAdjacentHTML('afterend', `<div class="bg-exform" style="display: none;z-index:${zIndex};"></div>`);
    }
  }

  // Получить форму с сервера
  async getFormFromServer() {
    let result = await fetch(window.exform.getExformUrn() + '/api/getform.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'renderForm', theme: this.theme.name, z_index: this.findHighestZIndex() })
    });

    result = await result.json();
    return result.data.form;
  }

  async getAndInsertForm() {
    document.body.insertAdjacentHTML('beforeend', await this.getFormFromServer());
  }

  // Ищем максимальный z-index, который есть на странице
  findHighestZIndex() {
    let maxZIndex = 0;
    const allElements = document.querySelectorAll('*');

    allElements.forEach(element => {
        const style = window.getComputedStyle(element);
        const zIndex = parseInt(style.zIndex, 10);

        if (!isNaN(zIndex) && zIndex > maxZIndex) {
            maxZIndex = zIndex;
        }
    });

    return maxZIndex;
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
Object.values(window.exform.getConfigServer().themes).forEach(async theme => {
    let exf = new Exform(theme);
    await exf.init();
});