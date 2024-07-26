<template>
    <h1>Home</h1>
    <div>
        {{ $store.state.userMod.user }}
    </div>
</template>

<script>
export default {
    beforeCreate() {
        this.$store.dispatch("userMod/storeUser");
    }
};
</script>