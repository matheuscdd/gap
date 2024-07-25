<template>
    <h1>Login</h1>
    <form>
        {{ email }}
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
import { user as cUser } from "@/common/consts";
import * as validator from "@/common/validators";
import iInput from "@/components/common/iInput.vue";


export default {
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
            const { email, password } = this;
            this.$store.dispatch("storeLogin", {email, password});
        },
        user() {
            this.$store.dispatch("storeUser");
        },
        verify(name) {
            const field = this[name];
            const schema = validator.login.pick({[name]: true});
            const errors = validator.validate(schema, {[name]: field.value})[name];
            field.errors = errors;
        }
    },
    computed: {
        EMAIL() {
            return cUser.keys.EMAIL;
        },
        PASSWORD() {
            return cUser.keys.PASSWORD;
        }
    }
};
</script>