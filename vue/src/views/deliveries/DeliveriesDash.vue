<template>
    <h1>Dashboard</h1>
    <iTreemap :data="$store.state.deliveryMod.treemap"/>
</template>

<script>
import iTreemap from "@/components/deliveries/iTreemap.vue";



export default {
    components: {
        iTreemap,
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        await this.$store.dispatch("deliveryMod/storeTreemap");
    }
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-top: 20px;
}
</style>