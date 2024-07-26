<template>
    <h1>Login</h1>
    {{ $store.state.userMod.user }}
    <form>
        <iInput 
            :label="trans.EMAIL"
            placeholder="Digite seu email"
            icon="envelope-solid"
            type="text"
            :name="keys.EMAIL"
            :errors="email.errors"
            v-model="email.value"
            @validate="verify"
        />
        <iInput 
            :label="trans.PASSWORD"
            placeholder="Digite sua senha"
            icon="key-solid"
            type="password"
            :name="keys.PASSWORD"
            :errors="password.errors"
            v-model="password.value"
            @validate="verify"
        />
        <button type="button" @click="login">Logar</button>
    </form>

</template>

<script>
import iInput from "@/components/common/iInput.vue";
import * as validator from "@/common/validators";
import mixins from "@/common/mixins";


export default {
    mixins: [mixins],
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
    computed: {
        rola() {
            return this.$store.state.userMod.user;
        }
    },
    methods: {
        login() {
            console.log(this.$store);
            this.$store.dispatch("userMod/storeLogin", {
                email: this.email.value, 
                password: this.password.value,
            });
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