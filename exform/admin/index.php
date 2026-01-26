<?php
    require_once(realpath(__DIR__ . '../../core/bootstrap.php'));
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Админка Exform</title>

        <script src="index.js" type="module"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.43.3/ace.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.15.0/cdn.min.js" defer></script>

        <link rel="stylesheet" href="style.css" />


        <style type="text/css" media="screen">
            #editor { 
                width: 100%;
                height: 500px;
            }
        </style>

    </head>

    <body>
        <header>
            <div class="container">Exform</div>
        </header>

        <main class="container" x-data x-init="$store.apiThemes.fetchThemes()">
            <div class="exform-main" :class="{ 'exform-main_loading': $store.apiThemes.isLoading }">
                <div class="exform-sidebar">
                    <div class="exform-sidebar__name">Формы:</div>
                    <template x-for="theme in $store.apiThemes.themes">
                        <span x-text="theme.name" x-init="$nextTick(() => { $store.apiThemes.editorInit() })" @click="$store.apiThemes.activeTheme = theme.name;" class="exform-sidebar__tab" :class="{'exform-sidebar__tab_active': $store.apiThemes.activeTheme === theme.name}"></span>
                    </template>
                </div>


                <div class="exform-content">
                    <template x-for="theme in $store.apiThemes.themes">
                        <div class="exform-content__tab" :class="{'exform-content__tab_active': $store.apiThemes.activeTheme === theme.name}">
                            
                            

                                <div x-data="accordion" class="accordion">
                                    <div @click="toggle" class="accordion__btn" :class="{'accordion__btn_open': open}">Файл формы (form.php)</div>

                                    <div x-show="open" x-cloak>
                                        <div x-text="theme.files.form" class="exform-content__ace exform-content__file exform-content__file-form"  data-type="php" :data-theme="theme.name" data-file="form"></div>
                                    </div>
                                </div>

                                <div x-data="accordion" class="accordion">
                                    <div @click="toggle" class="accordion__btn" :class="{'accordion__btn_open': open}">Файл стилей</div>

                                    <div x-show="open" x-cloak>
                                        <div x-text="theme.files.style" class="exform-content__ace exform-content__file exform-content__file-style" data-type="css" :data-theme="theme.name" data-file="style"></div>
                                    </div>
                                </div>

                                <div x-data="accordion" class="accordion">
                                    <div @click="toggle" class="accordion__btn" :class="{'accordion__btn_open': open}">Файл настроек</div>

                                    <div x-show="open" x-cloak>
                                        <div x-text="theme.files.config" class="exform-content__ace exform-content__file exform-content__file-style" data-type="ini" :data-theme="theme.name" data-file="config"></div>
                                    </div>
                                </div>

                                <div x-data="accordion" class="accordion">
                                    <div @click="toggle" class="accordion__btn" :class="{'accordion__btn_open': open}">Сообщение об успешной отправке</div>

                                    <div x-show="open" x-cloak>
                                        <div x-text="theme.files.success_msg" class="exform-content__ace exform-content__file exform-content__file-style" data-type="php" :data-theme="theme.name" data-file="success-msg"></div>
                                    </div>
                                </div>

                                <div x-data="accordion" class="accordion">
                                    <div @click="toggle" class="accordion__btn" :class="{'accordion__btn_open': open}">Сообщение с ошибкой</div>

                                    <div x-show="open" x-cloak>
                                        <div x-text="theme.files.error_msg" class="exform-content__ace exform-content__file exform-content__file-style" data-type="php" :data-theme="theme.name" data-file="error-msg"></div>
                                    </div>
                                </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="global-config" x-data x-init="$store.apiThemes.fetchGlobalConfig()">
                <div class="global-config-title">Глобальные настройки:</div>
                <div class="global-config-editor"></div>
            </div>

            <button :class="{ 'btn_active': !$store.apiThemes.isLoading }" class="btn" style="display: none;" @click="$store.apiThemes.saveFiles()">Сохранить (Ctrl + S)</button>
        </main>
    </body>
</html>