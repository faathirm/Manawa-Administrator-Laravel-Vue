<template>
    <div class="pl-5 pr-5 pb-5 pt-3">
        <div class="row d-flex justify-content-between">
            <div class="col-md-2 p-0 d-flex justify-content-start align-items-center">
                <p class="title is-3 ml-4 has-text-white font-weight-bold">Manawa</p>
            </div>
            <div class="col-md-4 p-0 ">
                <b-dropdown aria-role="list" style="display: block;" position="is-bottom-left">
                <div class="row d-flex justify-content-end" slot="trigger" slot-scope="{ active }">
                    <div class="col-md-2 d-flex justify-content-end">
                        <avatar :username="name" :size="48" backgroundColor="#e74c3c" color="white" :inline="true"></avatar>
                    </div>
                    <div class="col-md-6">
                        <p class="title is-5 has-text-white">{{ name }}</p>
                        <p class="subtitle is-5 has-text-white">Administrator</p>
                    </div>
                </div>
                    <b-dropdown-item aria-role="listitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</b-dropdown-item>
                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                        <input type="hidden" name="_token" :value="csrf">
                    </form>
                </b-dropdown>
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
    import {Avatar} from "vue-avatar";

    export default {
        name: 'App',
        components: {
            MainMenu,
            Avatar
        },
        data(){
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                name: '',
            }
        },
        mounted() {
            this.name = this.$userId;
        }
    }
</script>
<style lang="scss">
    // Import Bulma's core
    /*@import '~buefy/src/scss/buefy';*/
    @import "~bulma/sass/utilities/_all";

    $sidebar-width: 400px;
    $primary: #27AE60;
    $primary-invert: findColorInvert($primary);
    $primary-agakdark:#219652;
    $snackbar-background-color:$danger;
    $snackbar-color:$danger-invert;

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
