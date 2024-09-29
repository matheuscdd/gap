<template>
    <h1>Home</h1>
    <div>
        Sejam bem-vindo
    </div>
</template>

<script>
export default {
    beforeCreate() {
        window.scrollTo(0,0);
        this.$store.dispatch("userMod/storeLogged");
    }
};
</script>