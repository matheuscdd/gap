<template>
    <h1>Criar Manutenção</h1>
    <form @submit.prevent="create">
        <section>
            <iInput 
                label="Número"
                placeholder="Indefinido"
                readonly="true"
                icon="file-contract-solid"
                type="text"
                name="identifier"
                :errors="[]"
            />
            <iSearch
                :name="maintenanceForm.TRUCK.NAME"
                :icon="maintenanceForm.TRUCK.ICON"
                :label="maintenanceForm.TRUCK.LABEL"
                :errors="truck.errors"
                v-model="truck.value"
                :opts="trucksOpts"
                @validate="verifyMaintenance"
            />
            <iInput 
                :label="maintenanceForm.SERVICE.LABEL"
                :placeholder="maintenanceForm.SERVICE.PLACEHOLDER"
                :icon="maintenanceForm.SERVICE.ICON"
                :type="maintenanceForm.SERVICE.TYPE"
                :name="maintenanceForm.SERVICE.NAME"
                :maxlength="limits.maintenance.service.max"
                :errors="service.errors"
                v-model="service.value"
                @validate="verifyMaintenance"
            />
            <iInput 
                :label="maintenanceForm.DATE.LABEL"
                :placeholder="maintenanceForm.DATE.PLACEHOLDER"
                :icon="maintenanceForm.DATE.ICON"
                :type="maintenanceForm.DATE.TYPE"
                :name="maintenanceForm.DATE.NAME"
                :errors="date.errors"
                v-model="date.value"
                @validate="verifyMaintenance"
            />
            <iInput 
                :label="maintenanceForm.KM.LABEL"
                :placeholder="maintenanceForm.KM.PLACEHOLDER"
                :icon="maintenanceForm.KM.ICON"
                :type="maintenanceForm.KM.TYPE"
                :name="maintenanceForm.KM.NAME"
                :errors="km.errors"
                v-model="km.value"
                @validate="verifyMaintenance"
            />
            <iInput 
                :label="maintenanceForm.COST.LABEL"
                :placeholder="maintenanceForm.COST.PLACEHOLDER"
                :icon="maintenanceForm.COST.ICON"
                :type="maintenanceForm.COST.TYPE"
                :name="maintenanceForm.COST.NAME"
                :errors="cost.errors"
                v-model="cost.value"
                @validate="verifyMaintenance"
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
import { verifyMaintenance } from "@/common/validators";
import iInput from "@/components/common/iInput.vue";
import { getValues } from "@/common/utils";
import iSearch from "@/components/common/iSearch.vue";

export default {
    data: () => ({
        [mixins.data().maintenance.keys.SERVICE]: {
            errors: [],
            value: ""
        },
        [mixins.data().maintenance.keys.TRUCK]: {
            errors: [],
            value: ""
        },
        [mixins.data().maintenance.keys.COST]: {
            errors: [],
            value: "",
        },
        [mixins.data().maintenance.keys.DATE]: {
            errors: [],
            value: "",
        },
        [mixins.data().maintenance.keys.KM]: {
            errors: [],
            value: "",
        },
        trucksOpts: [],
    }),

    mixins: [mixins],
    components: {
        iInput,
        iSearch,
    },
    methods: {
        create() {
            const { service, truck, cost, date, km } = this;
            const data = { service, truck, cost, date, km };
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyMaintenance(key, this)));
            if (errors.flat().filter(Boolean).length) return alert("Ajuste os erros antes de continuar");
            const continues = confirm("Esta operação não poderá ser desfeita. Deseja continuar?");
            if (!continues) return;
            this.$store.dispatch("maintenanceMod/createMaintenance", getValues(data));
        },
        verifyMaintenance
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        await this.$store.dispatch("truckMod/storeTrucks");
        this.trucksOpts = this.$store.state.truckMod.trucks.map(el => ({id: el.id, name:`${el.plate} - ${el.axis}`}));
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