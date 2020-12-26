<template>
	<div>
        <div v-if="isLoggedIn" :class="containerClass" @click="onWrapperClick">
            <AppTopBar @menu-toggle="onMenuToggle" />

            <transition name="layout-sidebar">
                <div :class="sidebarClass" @click="onSidebarClick" v-show="isSidebarVisible()">
                    <div class="layout-logo">
                        <router-link to="/">
                            <font-awesome-icon :icon="['fas', 'pen-nib']" size="lg" style="color: #fff;"/>
                            <span style="color: #fff;">{{ appName }}</span>
                        </router-link>
                    </div>

                    <AppProfile />
                    <AppMenu :model="menu" @menuitem-click="onMenuItemClick" />
                </div>
            </transition>

            <div class="layout-main">
                
                <router-view />
                <h1></h1>
            </div>

            <AppFooter />
        </div>
        <div v-else>
            <router-view />
        </div>
        <!-- set progressbar -->
        <vue-progress-bar></vue-progress-bar>
        <FlashMessage :position="'right bottom'" ></FlashMessage>
        <!-- confirm dialog box -->
        <vue-confirm-dialog></vue-confirm-dialog>
    </div>
</template>

<script>
import AppTopBar from './AppTopbar.vue';
import AppProfile from './AppProfile.vue';
import AppMenu from './AppMenu.vue';
import AppFooter from './AppFooter.vue';

export default {
    data () {
        return {
            appName: '',
            layoutMode: 'static',
            layoutColorMode: 'dark',
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            menu : [
                {label: 'Dashboard', icon: 'home', to: '/'},
                {label: 'Articles', icon: 'newspaper', to: '/articles'},
                {label: 'Subjects', icon: 'newspaper', to: '/subjects'},
                {label: 'Topics', icon: 'newspaper', to: '/topics'},
                {
                    label: 'Extras', icon: 'list',
                    items: [
                        {label: 'Topics', icon: 'suitcase', to: '/topics' },
                        {label: 'Subjects', icon: 'book', to: '/subjects' },
                    ]
                },
            ]
        }
    },
    watch: {
        $route() {
            this.menuActive = false;
        }
    },
    created () {
        this.appName = this.$appName;
    },
    methods: {
        onWrapperClick() {
            if (!this.menuClick) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }

            this.menuClick = false;
        },
        onMenuToggle() {
            this.menuClick = true;

            if (this.isDesktop()) {
                if (this.layoutMode === 'overlay') {
                    this.overlayMenuActive = !this.overlayMenuActive;
                }
                else if (this.layoutMode === 'static') {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            }
            else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }

            event.preventDefault();
        },
        onSidebarClick() {
            this.menuClick = true;
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
        addClass(element, className) {
            if (element.classList)
                element.classList.add(className);
            else
                element.className += ' ' + className;
        },
        removeClass(element, className) {
            if (element.classList)
                element.classList.remove(className);
            else
                element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        },
        isDesktop() {
            return window.innerWidth > 1024;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === 'static')
                    return !this.staticMenuInactive;
                else if (this.layoutMode === 'overlay')
                    return this.overlayMenuActive;
                else
                    return true;
            }
            else {
                return true;
            }
        },
    },
    computed: {
        containerClass() {
            return ['layout-wrapper', {
                'layout-overlay': this.layoutMode === 'overlay',
                'layout-static': this.layoutMode === 'static',
                'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
                'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
                'layout-mobile-sidebar-active': this.mobileMenuActive
            }];
        },
        sidebarClass() {
            return ['layout-sidebar', {
                'layout-sidebar-dark': this.layoutColorMode === 'dark',
                'layout-sidebar-light': this.layoutColorMode === 'light'
            }];
        },
        logo() {
            return (this.layoutColorMode === 'dark') ? "images/admin/logo-white.svg" : "images/admin/logo.svg";
        },
        isLoggedIn() { 
            return this.$store.getters.isAuthenticated;
        }   
    },
    beforeUpdate() {
        if (this.mobileMenuActive)
            this.addClass(document.body, 'body-overflow-hidden');
        else
            this.removeClass(document.body, 'body-overflow-hidden');
    },
    components: {
        'AppTopBar': AppTopBar,
        'AppProfile': AppProfile,
        'AppMenu': AppMenu,
        'AppFooter': AppFooter,
    }
}
</script>

<style lang="scss">
@import './App.scss';
</style>
