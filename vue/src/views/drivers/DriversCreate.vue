<template>
    <h1>Criar Motorista</h1>
    <form @submit.prevent="create">
        <section>
            <iInput 
                :label="driverForm.NAME.LABEL"
                :placeholder="driverForm.NAME.PLACEHOLDER"
                :icon="driverForm.NAME.ICON"
                :type="driverForm.NAME.TYPE"
                :name="driverForm.NAME.NAME"
                :maxlength="limits.driver.name.max"
                :errors="name.errors"
                v-model="name.value"
                @validate="verifyDriver"
            />
            <iInput 
                :label="driverForm.CPF.LABEL"
                :placeholder="driverForm.CPF.PLACEHOLDER"
                :icon="driverForm.CPF.ICON"
                :type="driverForm.CPF.TYPE"
                :name="driverForm.CPF.NAME"
                :maxlength="limits.driver.CPF.size"
                :errors="CPF.errors"
                v-model="CPF.value"
                @validate="verifyDriver"
            />
            <iInput 
                :label="driverForm.PHOTO.LABEL"
                :placeholder="driverForm.PHOTO.PLACEHOLDER"
                :icon="driverForm.PHOTO.ICON"
                :type="driverForm.PHOTO.TYPE"
                :name="driverForm.PHOTO.NAME"
                :accept="driverForm.PHOTO.ACCEPT"
                :errors="photo.errors"
                v-model="photo.fakeValue"
                @validate="verifyTruck"
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
import { verifyDriver } from "@/common/validators";
import iInput from "@/components/common/iInput.vue";
import { getValues } from "@/common/utils";

export default {
    data: () => ({
        [mixins.data().cDriver.keys.NAME]: {
            errors: [],
            value: ""
        },
        [mixins.data().cDriver.keys.CPF]: {
            errors: [],
            value: ""
        },
        [mixins.data().cDriver.keys.PHOTO]: {
            errors: [],
            value: "",
            fakeValue: "",
        },
    }),
    mixins: [mixins],
    components: {
        iInput
    },
    methods: {
        async create() {
            const { name, CPF, photo } = this;
            const data = { name, CPF, photo };
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyDriver(key, this)));
            if (errors.flat().filter(Boolean).length) return this.$store.state.iChoice.open("Ajuste os erros antes de continuar", true);
            const continues = await this.$store.state.iChoice.open("Esta operação não poderá ser desfeita. Deseja continuar?");
            if (!continues) return;
            const handleData = getValues(data);
            handleData.photo = handleData.photo || null;
            this.$store.dispatch("driverMod/createDriver", handleData);
        },
        loadFile(b64) {
            this.photo.value = b64;
        },
        verifyDriver
    },
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