<template>
    <h1>Criar Usuário</h1>
    <form @submit.prevent="create">
        <section>
            <iInput 
                :label="userForm.NAME.LABEL"
                :placeholder="userForm.NAME.PLACEHOLDER"
                :icon="userForm.NAME.ICON"
                :type="userForm.NAME.TYPE"
                :name="userForm.NAME.NAME"
                :errors="name.errors"
                :maxlength="limits.user.name.max"
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
                :maxlength="limits.user.email.max"
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
                :maxlength="limits.user.password.max"
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
                :maxlength="limits.user.password.max"
                v-model="confirmPassword.value"
                @validate="verifyUser"
            />
            <iInput 
                :label="userForm.PHOTO.LABEL"
                :placeholder="userForm.PHOTO.PLACEHOLDER"
                :icon="userForm.PHOTO.ICON"
                :type="userForm.PHOTO.TYPE"
                :name="userForm.PHOTO.NAME"
                :accept="userForm.PHOTO.ACCEPT"
                :errors="photo.errors"
                v-model="photo.fakeValue"
                @validate="verifyUser"
                @loadFile="loadFile"
            />
            <iSelect
                :opts="opts"
                :label="userForm.TYPE.LABEL"
                icon="id-card-solid"
                v-model="type.value"
            />
            <div class="btn">
                <button 
                    class="bButton" 
                    type="submit"
                    :style="{backgroundColor: 'var(--green-2)'}"
                >
                Salvar
                </button>
            </div>
        </section>
    </form>
</template>

<script>
import mixins from "@/common/mixins";
import iInput from "@/components/common/iInput.vue";
import { verifyUser } from "@/common/validators";
import iSelect from "@/components/common/iSelect.vue";
import { getValues } from "@/common/utils";


export default {
    mixins: [mixins],
    data: () => ({
        opts: [
            {
                id: "common",
                name: "Comum",
            },
            {
                id: "admin",
                name: "Administrador",
            },
        ],

        [mixins.data().cUser.keys.EMAIL]: {
            errors: [],
            value: ""
        },
        [mixins.data().cUser.keys.NAME]: {
            errors: [],
            value: ""
        },
        [mixins.data().cUser.keys.PASSWORD]: {
            errors: [],
            value: ""
        },
        [mixins.data().cUser.keys.CONFIRM_PASSWORD]: {
            errors: [],
            value: ""
        },
        [mixins.data().cUser.keys.TYPE.THIS]: {
            errors: [],
            value: "common",
        },
        [mixins.data().cUser.keys.PHOTO]: {
            errors: [],
            value: "",
            fakeValue: "",
        },
    }),
    components: { iInput, iSelect },
    methods: {
        async create() {
            const { type, email, name, password, confirmPassword, photo } = this;
            const data = { type, email, name, password, confirmPassword, photo };
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyUser(key, this)));
            if (errors.flat().filter(Boolean).length) return this.$store.state.iChoice.open("Ajuste os erros antes de continuar", true);
            const continues = await this.$store.state.iChoice.open("Esta operação não poderá ser desfeita. Deseja continuar?");
            if (!continues) return;
            const handleData = getValues(data);
            handleData.photo = handleData.photo || null;
            this.$store.dispatch("userMod/createUser", handleData);
        },
        loadFile(b64) {
            this.photo.value = b64;
        },
        verifyUser
    },
    beforeCreate() {
        window.scrollTo(0,0);
        this.$store.dispatch("userMod/storeUsers");
    }
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
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