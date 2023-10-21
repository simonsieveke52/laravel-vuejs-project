<template>
    <ul class="nav navbar-nav">
        <li v-for="(item, i) in items" :class="classes(item)">
            <a 
            	class="jq-category-click d-block mb-1" 
            	href="#"
            	:data-slug="item.slug"
				:data-depth="item.depth"
				:href="route('category.filter', item.slug)" 
            >
                <span class="title position-relative">{{ item.name }}</span>
            </a>
            <div v-if="item.children !== undefined && item.children.length > 0" :id="''+item.id+'-dropdown-element'">
                <category-nav-item :items="item.children"></category-nav-item>
            </div>
        </li>
    </ul>
</template>
<script>
export default {
    props: {
        items: {
            type: Array,
            default: [],
        }
    },
    methods: {
        
        classes: function(item) {
            var classes = [];
            
            classes.push('pl-' + (item.depth * 2))

            return classes.join(' ');
        }
    }
};
</script>
