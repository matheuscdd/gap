<template>
    <h1>Home</h1>
    <div>
        Sejam bem-vindo
    </div>
    <div>
        Novos recursos estarão disponíveis aqui futuramente...
    </div>
</template>

<script>
export default {
    beforeCreate() {
        window.scrollTo(0,0);
    }
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

div {
    text-align: center;
}

form {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.btn {
    margin-top: 25px;
    display: flex;
    justify-content: center;
}
</style>