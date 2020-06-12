<template>
    <div class="p-5">
        <div class="row d-flex justify-content-between">
            <div class="col-md-4">
                test
            </div>
            <div class="col-md-4">
                testo
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-2 border-right">
                                <MainMenu/>
                            </div>
                            <div class="col-md-10">
                                <router-view></router-view>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MainMenu from './MainMenu'

    export default {
        name: 'App',
        components: {
            MainMenu
        }
    }
</script>
<style lang="scss">
    // Import Bulma's core
    /*@import '~buefy/src/scss/buefy';*/
    @import "~bulma/sass/utilities/_all";

    $primary: #27AE60;
    $primary-invert: findColorInvert($primary);
    $primary-agakdark:#219652;

    $colors: (
        "white": ($white, $black),
        "black": ($black, $white),
        "light": ($light, $light-invert),
        "dark": ($dark, $dark-invert),
        "primary": ($primary, $primary-invert),
        "info": ($info, $info-invert),
        "success": ($success, $success-invert),
        "warning": ($warning, $warning-invert),
        "danger": ($danger, $danger-invert)
    );

    $menu-item-active-background-color: $primary;
    $pagination-current-background-color: $primary;
    $pagination-current-border-color: $primary-agakdark;
    $pagination-focus-border-color: $primary-agakdark;

    @import "~bulma";
    @import "~buefy/src/scss/buefy";

</style>
