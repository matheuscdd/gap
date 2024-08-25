<template>
    <h1>Usuário</h1>
    {{ $store.state.userMod.user }}
    <form>
        <iInput 
            :label="userForm.NAME.LABEL"
            :placeholder="userForm.NAME.PLACEHOLDER"
            :icon="userForm.NAME.ICON"
            :type="userForm.NAME.TYPE"
            :name="userForm.NAME.NAME"
            :errors="name.errors"
            :maxlength="limits.user.name"
            v-model="name.value"
            @validate="verifyUser"
        />
        <iInput 
            :label="userForm.EMAIL.LABEL"
            :placeholder="userForm.EMAIL.PLACEHOLDER"
            :icon="userForm.EMAIL.ICON"
            :type="userForm.EMAIL.TYPE"
            :name="userForm.EMAIL.NAME"
            :errors="email.errors"
            :maxlength="limits.user.email"
            v-model="email.value"
            @validate="verifyUser"
        />
        <button type="button" @click="edit">Salvar</button>
    </form>
</template>

<script>
import mixins from "@/common/mixins";
import iInput from "@/components/common/iInput.vue";
import { verifyUser } from "@/common/validators";
import { getErrors, getValues } from "@/common/utils";


export default {
    mixins: [mixins],
    data: () => ({
        [mixins.data().user.keys.EMAIL]: {
            errors: [],
            value: ""
        },
        [mixins.data().user.keys.NAME]: {
            errors: [],
            value: ""
        },
    }),

    components: { iInput },

    mounted() {
        this.fill();
    },

    methods: {
        async fill() {
            const id = this.$route.params.id;
            await this.$store.dispatch("userMod/storeUser", id);
            const user = this.$store.state.userMod.user;
            this.name.value = user.name;
            this.email.value = user.email;
        },

        edit() {
            const { name, email } = this;
            const data = { name };
            if (this.$store.state.userMod.user.email !== email.value) {
                data.email = email;
            }
            if (getErrors(data)) return alert("Ajuste os erros antes de continuar");
            this.$store.dispatch("userMod/editUser", {
                ...getValues(data), 
                id: this.$route.params.id
            });
        },
        verifyUser
    }
};
</script>