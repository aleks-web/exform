class ExformManager {
    #exformUrn;
    #configServer;

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

    // Закрыть все модалки
    closeAllModals() {
        document.querySelectorAll('.exform-wrapper.is_modal').forEach(el => {
            el.remove();
            document.querySelector('.bg-exform')?.remove();
        });
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
            this.renderForm();
        });
    }

    if (!(!!this.theme.config.is_modal) && selector) {
        this.renderForm();
    }
  }

  addSubmitListner(form) {
    let submitBtn = form.querySelector("[type='submit']");
    form = form.querySelector("form");

    try {
        submitBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            let formData = new FormData(form);
            formData.append('z_index', this.findHighestZIndex());

            const response = await fetch(window.exform.getExformUrn() + '/api/sendform.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            this.openMsg(result.data.msg);
        });
    } catch(er) {
        console.log("Не удалось задать прослушивателя события для кнопки отправки");
        console.log(er);
    }
  }

  openMsg(msgForm) {
    if (msgForm) {
        document.querySelectorAll('.exform-wrapper.is_modal').forEach(el => {
            el.remove();
        });

        if (!(!!this.theme.config.is_modal)) {
            this.createBgModal();
        }

        document.body.insertAdjacentHTML('beforeend', msgForm);
        this.setElementScreenCenter(document.querySelector('.exform-wrapper.is_modal'));
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

  setElementScreenCenter(element) {
    let elRec = element.getBoundingClientRect();
    element.style.top = `calc(50% - ${elRec.height / 2}px)`;
    element.style.left = `calc(50% - ${elRec.width / 2}px)`;
  }

  addCenterFormListner(formElement) {
    this.setElementScreenCenter(formElement);
    window.addEventListener('resize', () => {
        this.setElementScreenCenter(formElement);
    });
  }

  // Рендер формы / отображение
  async renderForm() {
    if (!!this.theme.config.is_modal) {
        window.exform.closeAllModals();
        this.createBgModal();

        let formCode = await this.getFormFromServer();
        document.body.insertAdjacentHTML('beforeend', formCode);
        let form = document.querySelector('.exform-wrapper.' + this.theme.name);

        this.addCenterFormListner(form);
        this.addSubmitListner(form);
    } else {
        let selector = document.querySelector(this.theme.config.selector);
        let form = await this.getFormFromServer();
        selector.innerHTML = form;
        form = selector.querySelector('.exform-wrapper');
        this.addSubmitListner(form);
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
Object.values(window.exform.getConfigServer().themes).forEach(async theme => {
    let exf = new Exform(theme);
    await exf.init();
});