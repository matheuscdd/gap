<template>
    <h1>Editar Usuário</h1>
    <form @submit.prevent="edit">
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
                :label="userForm.PHOTO.LABEL"
                :placeholder="userForm.PHOTO.PLACEHOLDER"
                :icon="userForm.PHOTO.ICON"
                :type="userForm.PHOTO.TYPE"
                :name="userForm.PHOTO.NAME"
                :accept="userForm.PHOTO.ACCEPT"
                :errors="photo.errors"
                :savedFilename="photo.value"
                v-model="photo.fakeValue"
                @validate="verifyUser"
                @loadFile="loadFile"
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
import { getValues } from "@/common/utils";
import { userForm } from "@/common/consts";


export default {
    mixins: [mixins],
    data: () => ({
        [mixins.data().cUser.keys.EMAIL]: {
            errors: [],
            value: ""
        },
        [mixins.data().cUser.keys.NAME]: {
            errors: [],
            value: ""
        },
        [mixins.data().cUser.keys.PHOTO]: {
            errors: [],
            value: "",
            fakeValue: "",
        },
    }),

    components: { iInput },

    methods: {
        async edit() {
            const { name, email, photo } = this;
            const data = { name, email, photo };
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyUser(key, this)));
            if (errors.flat().filter(Boolean).length) return this.$store.state.iChoice.open("Ajuste os erros antes de continuar", true);
            const continues = await this.$store.state.iChoice.open("Esta operação não poderá ser desfeita. Deseja continuar?");
            if (!continues) return;
            const handleData = getValues(data);
            handleData.photo = handleData.photo || null;
            if (this.$store.state.userMod.user.photo === handleData.photo) {
                delete handleData.photo;
            }
            this.$store.dispatch("userMod/editUser", {
                ...handleData, 
                id: this.$route.params.id
            });
        },
        loadFile(b64) {
            this.photo.value = b64;
        },
        verifyUser
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        const id = this.$route.params.id;
        await this.$store.dispatch("userMod/storeUser", id);
        const { user } = this.$store.state.userMod;
        for (const key in user) {
            if (!this[key]) continue;
            this[key].value = user[key];
        }
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