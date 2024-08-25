<template>
    <h1>Home</h1>
    <div>
        {{ $store.state.userMod.logged }}
    </div>
</template>

<script>
export default {
    beforeCreate() {
        this.$store.dispatch("userMod/storeLogged");
    }
};
</script>