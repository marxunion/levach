<script lang="ts">
    import "./scss/Header.scss";
    import DropDown from "./DropDown.vue";

    export default {
        name: "Header",
        components: {
            DropDown,
        },
    };
</script>

<template>    
    <header class="header lock_padding">
        <div class="header__block">
            <a class="header__logo" href="/">
                <img src="../assets/img/logo.png" alt="LOGO" class="header__logo__icon">
            </a>
            <div class="header__bar">
                <div class="header__bar__block">
                    <div class="header__bar__search">
                        <input placeholder="Поиск..." type="text" class="header__bar__search__input">
                        <a href="/" class="header__bar__search__button"></a>
                    </div>
                    <div class="header__bar__subbar">
                        <a href="/createarticle" class="header__bar__subbar__createarticle"><p>Написать статью</p></a>
                        <DropDown
                        :options="['RU', 'EN']"
                        :default="'RU'"
                        class="header__bar__subbar__select"
                        @input=""
                        />
                        <a class="header__bar__subbar__searchmobile">
                            <div class="header__bar__subbar__searchmobile__icon"></div>
                        </a>
                        <a href="/newarticle" class="header__bar__subbar__createarticlemobile">
                            <div class="header__bar__subbar__createarticlemobile__icon"></div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="header__burger header-burger">
                <div class="header__burger__lines header-burger"></div>
            </div>
        </div>
    </header>
</template>