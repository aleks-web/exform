class ExformManager {
    #forms = [];

    constructor() {
        if (!ExformManager.instance) {
            ExformManager.instance = this;
            window.exform = ExformManager.instance;
        }
        return ExformManager.instance;
    }

    addForm(form) {
        this.#forms.push(form);
    }

    getForms() {
        return this.#forms;
    }
}

class Exform {
  #serverConfig = {};

  constructor(element = null, theme = 'callback', wrapperSelector = 'body', path = '/exform') {
    this.path = path;
    this.element = element;
    this.wrapper = wrapperSelector;
    this.theme = theme;
  }

  async init() {
    this.includeCss();
    
    if (!window.exform) {
        window.exform = new ExformManager();
    }

    window.exform.addForm(this);

    await this.getServerConfig();
  }

  // Подключение стилей формы
  includeCss() {
    let cssThemePath = this.path + '/themes/' + this.theme + '/assets/style.css';

    let head = document.querySelector('head');
    let findLinkResult = head.querySelectorAll(`link[href="${cssThemePath}"]`).lenght;

    if (!findLinkResult) {
        head.insertAdjacentHTML('beforeend', `<link rel="stylesheet" type="text/css" href="${cssThemePath}" />`);
    }
  }

  async getServerConfig() {

    let result = await fetch('/exform/api/config.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    });

    this.#serverConfig = await result.json();
    console.log(this.#serverConfig);
  }

}

let s = new Exform();
await s.init();