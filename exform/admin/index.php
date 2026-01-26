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


                        </div>
                    </template>
                </div>
            </div>

            <button class="btn" @click="$store.apiThemes.saveFiles()">Соранить</button>

        </main>
    </body>
</html>