<template>
    <h1>Dashboard</h1>
    <div>
        <div class="container-filters">
            <iInput 
                label="Início"
                icon="calendar-solid"
                type="date"
                name="start_date"
                :errors="[]"
                v-model="start_date"
                @validate="updateSelect"
            />
            <iInput 
                label="Final"
                icon="calendar-solid"
                type="date"
                name="end_date"
                :errors="[]"
                v-model="end_date"
                @validate="updateSelect"
            />
            <iSelect
                :opts="dateOpts"
                label="Período"
                name="period"
                :errors="[]"
                icon="clock-solid"
                v-model="period"
            />
            <iSearch
                name="truck"
                icon="truck-solid"
                label="Caminhão"
                :errors="[]"
                v-model="truck"
                :opts="trucksOpts"
                :edit="true"
            />
            <button
                @click="updateRange"
            >
                <iSvg
                    :src="require('@/assets/icons/magnifying-glass-solid.svg')"
                    width="16"
                    height="16"
                    fill="white"
                />
            </button>
        </div>
        <iScatter 
            :data="$store.state.maintenanceMod.scatter" 
            :name="chartTitle"
        />
    </div>
</template>

<script>
import { handleDate } from "@/common/utils";
import iInput from "@/components/common/iInput.vue";
import iSearch from "@/components/common/iSearch.vue";
import iSelect from "@/components/common/iSelect.vue";
import iScatter from "@/components/deliveries/iScatter.vue";


function getChartTitle(start_date, end_date, truck=0, opts=[]) {
    const start = handleDate(start_date).toLocaleDateString("pt-BR");
    const end = handleDate(end_date).toLocaleDateString("pt-BR");
    const plate = opts.find(el => el?.id === truck)?.name;
    return `Manutenções realizadas entre ${start} e ${end}` + (plate ? ` com o caminhão ${plate}` : "");
}

export default {
    data: () => ({
        // TODO globalizar essas opções 
        dateOpts: [
            {
                id: 0,
                name: "Sem filtro",
            },
            {
                id: 7,
                name: "Últimos 7 dias",
            },
            {
                id: 15,
                name: "Últimos 15 dias",
            },
            {
                id: 30,
                name: "Últimos 30 dias",
            },
        ],
        period: 0,
        truck: "",
        trucksOpts: [],
     }),
    components: {
        iScatter,
        iInput,
        iSelect,
        iSearch,
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        const start_date = new Date(new Date() - 3 * 24 * 60 * 60 * 1000).toLocaleDateString("sv");
        const end_date = new Date().toLocaleDateString("sv");
        this.start_date = start_date;
        this.end_date = end_date;
        this.chartTitle = getChartTitle(start_date, end_date);
        await Promise.all([
            this.$store.dispatch("truckMod/storeTrucks"),
            this.$store.dispatch("maintenanceMod/storeScatter", { start_date, end_date }),
        ]);
        this.trucksOpts = this.$store.state.truckMod.trucks
            .map(el => ({id: el.id, name:`${el.plate} - ${el.axis}`}));
        this.trucksOpts.push({id: 0, name: "Sem filtro"});
    }, 
    methods: {
        updateSelect() {
            this.period = 0;
        },
        updateRange() {
            const { start_date, end_date, truck, trucksOpts } = this;
            this.chartTitle = getChartTitle(start_date, end_date, truck, trucksOpts);
            this.$store.dispatch("maintenanceMod/storeScatter", { start_date, end_date, truck });
        },
    },
    watch: {
        period(newValue, oldValue) {
            if (!Number(newValue)) return;
            this.end_date = new Date().toLocaleDateString("sv");
            this.start_date = new Date(new Date() - Number(newValue) * 24 * 60 * 60 * 1000).toLocaleDateString("sv");
        }
    }
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-top: 20px;
}

button {
    background-color: var(--blue-1);
    cursor: pointer;
    padding: 5px;
    border-radius: 100%;
    border: none;
    display: flex;
    align-items: center;
    width: 58px;
    height: 58px;
    justify-content: center;
}

button:hover:not(:disabled), button:active:not(:disabled) {
    transition: 0.3s;
    filter: brightness(1.3);
}

.container-filters {
    background-color: #FFF;
    margin-top: 50px;
    display: flex;
    gap: 20px;
    padding: 0 20px;
}
</style>