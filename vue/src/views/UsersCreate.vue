<template>
    <h1>Usuário</h1>
    <form>
        <iInput 
            :label="userForm.NAME.LABEL"
            :placeholder="userForm.NAME.PLACEHOLDER"
            :icon="userForm.NAME.ICON"
            :type="userForm.NAME.TYPE"
            :name="userForm.NAME.NAME"
            :errors="name.errors"
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
            v-model="email.value"
            @validate="verifyUser"
        />
        <iInput 
            :label="userForm.PASSWORD.LABEL"
            :placeholder="userForm.PASSWORD.PLACEHOLDER"
            :icon="userForm.PASSWORD.ICON"
            :type="userForm.PASSWORD.TYPE"
            :name="userForm.PASSWORD.NAME"
            :errors="password.errors"
            v-model="password.value"
            @validate="verifyUser"
        />
        <iInput 
            :label="userForm.CONFIRM_PASSWORD.LABEL"
            :placeholder="userForm.CONFIRM_PASSWORD.PLACEHOLDER"
            :icon="userForm.CONFIRM_PASSWORD.ICON"
            :type="userForm.CONFIRM_PASSWORD.TYPE"
            :name="userForm.CONFIRM_PASSWORD.NAME"
            :errors="confirmPassword.errors"
            v-model="confirmPassword.value"
            @validate="verifyUser"
        />
        <iSelect
            :opts="opts"
            :label="userForm.TYPE.LABEL"
            :placeholder="type.text"
            :NAME="userForm.TYPE.NAME"
            :errors="type.errors"
            icon="id-card-solid"
            v-model="type.value"
        />
        <button type="button" @click="create">Salvar</button>
    </form>
</template>

<script>
import mixins from "@/common/mixins";
import iInput from "@/components/common/iInput.vue";
import { verifyUser } from "@/common/validators";
import iSelect from "@/components/common/iSelect.vue";
import { getErrors, getValues } from "@/common/utils";


export default {
    mixins: [mixins],
    data: () => ({
        opts: [
            {
                value: "common",
                text: "Comum",
                icon: "helmet-safety-solid"
            },
            {
                value: "admin",
                text: "Administrador",
                icon: "user-tie-solid"
            },
        ],

        [mixins.data().keys.EMAIL]: {
            errors: [],
            value: ""
        },
        [mixins.data().keys.NAME]: {
            errors: [],
            value: ""
        },
        [mixins.data().keys.PASSWORD]: {
            errors: [],
            value: ""
        },
        [mixins.data().keys.CONFIRM_PASSWORD]: {
            errors: [],
            value: ""
        },
        [mixins.data().keys.TYPE.THIS]: {
            errors: [],
            value: "",
            text: mixins.data().userForm.TYPE.PLACEHOLDER,
        },
    }),
    components: { iInput, iSelect },
    methods: {
        create() {
            const { type, email, name, password, confirmPassword } = this;
            const data = { type, email, name, password, confirmPassword };
            if (getErrors(data)) return alert("Ajuste os erros antes de continuar");
            console.log(data);
            this.$store.dispatch("userMod/createUser", getValues(data));
        },
        verifyUser
    }
};
</script>