<template>
    <section>
        <b-tabs v-model="activeTab">
            <b-tab-item label="Product">
                <FarmVariety v-if="activeTab == 0" />
            </b-tab-item>
            <b-tab-item label="Variety">
                <Variety v-if="activeTab == 1" />
            </b-tab-item>
            <b-tab-item label="Animal">
                <Animal v-if="activeTab == 2" />
            </b-tab-item>
        </b-tabs>
    </section>
</template>
<script>
    import Animal from './Product/Animal';
    import Variety from './Product/Variety';
    import FarmVariety from './Product/FarmVariety';

    export default {
        components: {
            Animal,
            Variety,
            FarmVariety
        },
        data() {
            return {
                activeTab:0
            }
        },
        methods: {

        }
    }
</script>
