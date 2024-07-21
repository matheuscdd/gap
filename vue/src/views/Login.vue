<template>
    <h1>Login</h1>
    <form>
        {{ $store.state.user }}
        <iInput 
            label="Email"
            placeholder="Digite seu email"
            icon="envelope-solid"
            name="email"
            type="text"
            v-model="email"
        />
        <iInput 
            label="Senha"
            placeholder="Digite sua senha"
            icon="key-solid"
            name="password"
            type="password"
            v-model="password"
        />
        <button type="button" @click="login">Logar</button>
        <button type="button" @click="user">User</button>
    </form>

</template>

<script>
import iInput from "@/components/iInput.vue";

export default {
    components: {
        iInput
    },
    data: () => ({
        email: "",
        password: ""
    }),
    methods: {
        login() {
            const { email, password } = this;
            this.$store.dispatch("storeLogin", {email, password});
        },
        user() {
            this.$store.dispatch("storeUser");
        }
    }
};
</script>