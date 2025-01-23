<template>
    <h1>Editar Caminhão</h1>
    <form @submit.prevent="edit">
        <section>
            <iInput 
                :label="truckForm.PLATE.LABEL"
                :placeholder="truckForm.PLATE.PLACEHOLDER"
                :icon="truckForm.PLATE.ICON"
                :type="truckForm.PLATE.TYPE"
                :name="truckForm.PLATE.NAME"
                :maxlength="limits.truck.plate.size"
                :errors="plate.errors"
                v-model="plate.value"
                @validate="verifyTruck"
            />
            <iInput 
                :label="truckForm.AXIS.LABEL"
                :placeholder="truckForm.AXIS.PLACEHOLDER"
                :icon="truckForm.AXIS.ICON"
                :type="truckForm.AXIS.TYPE"
                :name="truckForm.AXIS.NAME"
                :errors="axis.errors"
                v-model="axis.value"
                @validate="verifyTruck"
            />
            <iInput 
                :label="truckForm.PHOTO.LABEL"
                :placeholder="truckForm.PHOTO.PLACEHOLDER"
                :icon="truckForm.PHOTO.ICON"
                :type="truckForm.PHOTO.TYPE"
                :name="truckForm.PHOTO.NAME"
                :accept="truckForm.PHOTO.ACCEPT"
                :errors="photo.errors"
                :savedFilename="photo.value"
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
import { verifyTruck } from "@/common/validators";
import iInput from "@/components/common/iInput.vue";
import { getValues } from "@/common/utils";

export default {
    data: () => ({
        [mixins.data().truck.keys.PLATE]: {
            errors: [],
            value: ""
        },
        [mixins.data().truck.keys.AXIS]: {
            errors: [],
            value: ""
        },
        [mixins.data().truck.keys.PHOTO]: {
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
        edit() {
            const { plate, axis, photo } = this;
            const rawData = { axis, photo };
            const { truck } = this.$store.state.truckMod;
            const errors = [];
            if (truck.plate !== plate.value) {
                rawData.plate = plate;
                rawData.plate.value = rawData.plate.value.toUpperCase();
            }
            Object.keys(rawData).forEach(key => errors.push(verifyTruck(key, this)));
            if (errors.flat().filter(Boolean).length) return alert("Ajuste os erros antes de continuar");
            const handleData = getValues(rawData);
            handleData.photo = handleData.photo || null;
            this.$store.dispatch("truckMod/editTruck", {
                ...handleData,
                id: this.$route.params.id
            });
        },
        loadFile(b64) {
            this.photo.value = b64;
        },
        verifyTruck
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        const id = this.$route.params.id;
        await this.$store.dispatch("truckMod/storeTruck", id);
        const { truck } = this.$store.state.truckMod;
        for (const key in truck) {
            if (!this[key]) continue;
            this[key].value = truck[key];
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