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
            formData.append('z_index', Exform.findHighestZIndex());

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
            Exform.openBgModal();
        }

        document.body.insertAdjacentHTML('beforeend', msgForm);
        Exform.setElementScreenCenter(document.querySelector('.exform-wrapper.is_modal'));
    }
  }

  // Закрывает все модалки Exform
  static closeAllModals() {
    document.querySelectorAll('.exform-wrapper.is_modal').forEach(el => {
        Exform.fadeOut(el, 100);
        Exform.fadeOut(document.querySelector('.bg-exform'), 200);
    });
  }

  // Создание заднего фона
  static openBgModal() {
    let bgResult = document.querySelectorAll('.bg-exform').length;
    let zIndex = Exform.findHighestZIndex() + 10;

    if (!bgResult) {
        document.body.insertAdjacentHTML('afterend', `<div class="bg-exform" style="display: none;z-index:${zIndex};"></div>`);
    }

    Exform.fadeIn(document.querySelector('.bg-exform'));
  }

  // Получить форму с сервера
  async getFormFromServer() {
    let result = await fetch(window.exform.getExformUrn() + '/api/getform.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'renderForm', theme: this.theme.name, z_index: Exform.findHighestZIndex() })
    });

    result = await result.json();
    return result.data.form;
  }

  // Ищем максимальный z-index, который есть на странице
  static findHighestZIndex() {
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

  // Установка элемента по центру
  static setElementScreenCenter(element) {
    let elRec = element.getBoundingClientRect();
    element.style.top = `calc(50% - ${elRec.height / 2}px)`;
    element.style.left = `calc(50% - ${elRec.width / 2}px)`;
  }

  // Установка прослушивателя события для центрирования элемента по центру
  static addCenterElementListner(formElement) {
    Exform.setElementScreenCenter(formElement);
    window.addEventListener('resize', () => {
        Exform.setElementScreenCenter(formElement);
    });
  }

  // Рендер формы / отображение
  async renderForm() {
    if (!!this.theme.config.is_modal) {
        Exform.closeAllModals();
        Exform.openBgModal();

        let formCode = await this.getFormFromServer();
        document.body.insertAdjacentHTML('beforeend', formCode);
        let form = document.querySelector('.exform-wrapper.' + this.theme.name);
        Exform.fadeIn(form, 1);

        Exform.addCenterElementListner(form);
        this.addSubmitListner(form);
    } else {
        let selector = document.querySelector(this.theme.config.selector);
        let form = await this.getFormFromServer();
        selector.innerHTML = form;
        form = selector.querySelector('.exform-wrapper');
        Exform.fadeIn(form, 1);
        this.addSubmitListner(form);
    }
  }

  static fadeIn(el, opacityValue = 0.25) {
    el.style.opacity = 0;
    el.style.display = "block";
    let last = +new Date();
    let tick = function() {
        el.style.opacity = +el.style.opacity + (new Date() - last) / 400;
        last = +new Date();
        if (+el.style.opacity < opacityValue) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
        }
    };
    tick();
  }

  static fadeOut(el, duration = 100, isRemove = true) {
    let opacity = el?.style?.opacity ? Number.parseFloat(el.style.opacity) : 1;
    let start = null;

    function step(timestamp) {
        if (!start) start = timestamp;
        let progress = timestamp - start;
        
        opacity = opacity - (progress / duration);
        el.style.opacity = opacity;

        if (progress < duration) {
            requestAnimationFrame(step);
        } else {
            el.style.opacity = 0;
            el.style.display = 'none';
            
            if (isRemove) {
                el.remove();
            }
        }
    }
    requestAnimationFrame(step);
  }
}

/*
    Инициализируем менеджер exform
*/
window.exform = new ExformManager();
await window.exform.init();

window.Exform = Exform;

/*
    Создаем формы
*/
Object.values(window.exform.getConfigServer().themes).forEach(async theme => {
    let exf = new Exform(theme);
    await exf.init();
});