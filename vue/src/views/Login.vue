<template>
    <h1>Login</h1>
    <form>
        <iInput 
            label="Email"
            placeholder="Digite seu email"
            icon="envelope-solid"
            type="text"
            :name="EMAIL"
            :errors="email.errors"
            v-model="email.value"
            @validate="verify"
        />
        <iInput 
            label="Senha"
            placeholder="Digite sua senha"
            icon="key-solid"
            type="password"
            :name="PASSWORD"
            :errors="password.errors"
            v-model="password.value"
            @validate="verify"
        />
        <button type="button" @click="login">Logar</button>
        <button type="button" @click="user">User</button>
    </form>

</template>

<script>
import iInput from "@/components/common/iInput.vue";
import * as validator from "@/common/validators";
import consts from "@/common/mixins";


export default {
    mixins: [consts],
    components: {
        iInput
    },
    data: () => ({
        email: {
            value: "",
            errors: []
        },
        password: {
            value: "",
            errors: []
        },
    }),
    methods: {
        login() {
            this.$store.dispatch("storeLogin", {
                email: this.email.value, 
                password: this.password.value,
            });
        },
        user() {
            this.$store.dispatch("storeUser");
        },
        verify(name) {
            const field = this[name];
            const schema = validator.login.pick({[name]: true});
            const errors = validator.validate(schema, {[name]: field.value})[name];
            field.errors = errors || {};
        }
    },
};
</script>