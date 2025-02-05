<template>
    <h1>Garagem</h1>
    <main>
        <section>
            <h2>Caminhões</h2>
            <div class="btns">
                <RouterLink v-show="TRACKER_LOCALIZATION" :href="TRACKER_LOCALIZATION" target="_blank" class="integration">
                    Localização
                    <iSvg 
                        :src="require('@/assets/icons/map-location-dot-solid.svg')"
                        width="16" 
                        height="16"
                        fill="white"
                    />
                </RouterLink>
                <RouterLink v-show="ROUTE_CALCULATOR" :href="ROUTE_CALCULATOR" target="_blank" class="calc">
                    Calcular 
                    <iSvg 
                        :src="require('@/assets/icons/calculator-solid.svg')"
                        width="16" 
                        height="16"
                        fill="white"
                    />
                </RouterLink>
            </div>
            <ul v-if="$store.state.truckMod.trucks.length">
                <iTruck
                    v-for="el in $store.state.truckMod.trucks" 
                    :key="el.id"
                    :id="el.id"
                    :plate="el.plate"
                    :photo="el.photo"
                    :axis="el.axis"
                    :maintenances="el.maintenances"
                    :createdAt="el.created_at"
                    @del="delTruck"
                    @edit="editTruck"
                />
            </ul>
        </section>
        <section>
            <h2>Manutenções</h2>
            <div class="header">
                <div>Número</div>
                <div>Custo</div>
                <div>Serviço</div>
                <div>Data</div>
                <div>Quilometragem</div>
                <div>Caminhão</div>
                <div>Ações</div>
            </div>
            <ul v-if="$store.state.maintenanceMod.maintenances.length">
                <iMaintenance
                    v-for="el in $store.state.maintenanceMod.maintenances" 
                    :key="el.id"
                    :id="el.id"
                    :km="el.km"
                    :cost="el.cost"
                    :date="el.date"
                    :service="el.service"
                    :plate="$store.state.truckMod.trucks.find(x => el.truck === x.id).plate"
                    @del="delMaintenance"
                    @edit="editMaintenance"
                />
            </ul>
        </section>
    </main>
</template>

<script>
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";
import iTruck from "@/components/garage/iTruck.vue";
import iMaintenance from "@/components/garage/iMaintenance.vue";
import { RouterLink } from "vue-router";

console.log(process.env.VUE_APP_TRACKER_LOCALIZATION);

export default {
    data: () => ({
        TRACKER_LOCALIZATION: process.env.VUE_APP_TRACKER_LOCALIZATION,
        ROUTE_CALCULATOR: process.env.VUE_APP_ROUTE_CALCULATOR,
    }),
    mixins: [mixins],
    async beforeCreate() {
        window.scrollTo(0,0);
        await Promise.all([
            this.$store.dispatch("truckMod/storeTrucks"),
            this.$store.dispatch("maintenanceMod/storeMaintenances"),
        ]);
    },
    components: {
        iTruck, 
        iMaintenance,
        RouterLink,
    },
    methods: {
        delTruck(id) {
            const continues = confirm("Tem certeza que deseja excluir?");
            if (!continues) return;
            this.$store.dispatch("truckMod/delTruck", id);
        },
        delMaintenance(id) {
            const continues = confirm("Tem certeza que deseja excluir?");
            if (!continues) return;
            this.$store.dispatch("maintenanceMod/delMaintenance", id);
            
        },
        editTruck(id) {
            this.$router.push(endpoints.routes.TRUCK_EDIT.replace(":id", id));
        },
        editMaintenance(id) {
            this.$router.push(endpoints.routes.MAINTENANCE_EDIT.replace(":id", id));
        }
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

main {
    display: grid;
    grid-template-columns: 330px 1fr;
    column-gap: 25px;
}

h2 {
    font-size: 25px;
    text-align: center;
    margin-bottom: 15px;
}

.header {
    display: grid;
    grid-auto-rows: 40px;
    grid-template-columns: 10% 10% 25% 10% 15% 20% 10%;
    padding: 0 10px;
    margin-bottom: 10px;
    text-align: center;
    justify-items: center;
    align-items: center;
}

.btns {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.btns a {
    text-decoration: none;
    color: white;
    padding: 8px;
    border-radius: 5px;
    cursor: pointer;
}

.btns .calc {
    background-color: var(--blue-1);
}

.btns .integration {
    background-color: var(--orange-1);
}
</style>