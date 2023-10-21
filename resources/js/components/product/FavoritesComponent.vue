<template>
    <span class="fa-layers fa-fw m-0 position-relative d-flex flex-column-reverse w-100 positon-relative">
        <i :class="'fas fa-heart mb-0 ' + cssClass"></i>
        <span
            v-if="favoritesCounter > 0" 
            class="fa-layers-counter text-muted-2 text-hover-darker d-flex flex-column align-items-center justify-content-center position-absolute small"
            style="top: -14px; right: 0; left: 0;"
        >
            <span class="d-flex font-size-0-7rem">
                {{ favoritesCounter }}
            </span>
        </span>
    </span>
</template>
<script>
export default {

    props: {
        cssClass: {
            type: String,
            default: 'text-highlight'
        }
    },

    data() {
        return {
            favoritesCounter: 0,
        }
    },

    mounted() {
        this.favoritesCounter = this.getFavoritesCounter();

        this.$root.$on('favorites_updated', () => {
            this.favoritesCounter = this.getFavoritesCounter();            
        })
    },

    methods : {
        getFavoritesCounter() {
            try {
                let favorites = localStorage.getItem('favorites')
                if (favorites === null || favorites === undefined) {
                    localStorage.setItem('favorites', JSON.stringify([]))
                    return 0;
                }
                return JSON.parse(favorites).length;
            } catch (e) {
                return 0;
            }
        }
    },
};
</script>