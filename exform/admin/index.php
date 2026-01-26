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

        <main class="container">
            <div class="exform-main" x-data x-init="$store.apiThemes.fetchThemes()" :class="{ 'exform-main_loading': $store.apiThemes.isLoading }">
                <div class="exform-sidebar">
                    <div class="exform-sidebar__name">Формы:</div>
                    <template x-for="theme in $store.apiThemes.themes">
                        <span x-text="theme.name" x-init="$nextTick(() => { $store.apiThemes.editorInit() })" @click="$store.apiThemes.activeTheme = theme.name;" class="exform-sidebar__tab" :class="{'exform-sidebar__tab_active': $store.apiThemes.activeTheme === theme.name}"></span>
                    </template>
                </div>


                <div class="exform-content">
                    <template x-for="theme in $store.apiThemes.themes">
                        <div class="exform-content__tab" :class="{'exform-content__tab_active': $store.apiThemes.activeTheme === theme.name}">
                            <div x-text="theme.files.form" :id="$id('exform-content')"></div>
                        </div>
                    </template>
                </div>

                <div id="editor">sad</div>
            </div>
        </main>
    </body>
</html>