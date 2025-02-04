<template>
    <h1>Dashboard</h1>
    <iTreemap :data="$store.state.deliveryMod.treemap"/>
    <div v-show="$store.state.deliveryMod.scatter.length">
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
            :data="$store.state.deliveryMod.scatter" 
            :indexPositive="0" 
            :name="chartTitle"
        />
    </div>
</template>

<script>
import { handleDate } from "@/common/utils";
import iInput from "@/components/common/iInput.vue";
import iSelect from "@/components/common/iSelect.vue";
import iScatter from "@/components/deliveries/iScatter.vue";
import iTreemap from "@/components/deliveries/iTreemap.vue";
 // tODO ter o filtro por caminhão no dash de caminhões

function getChartTitle(start_date, end_date) {
    const start = handleDate(start_date).toLocaleDateString("pt-BR");
    const end = handleDate(end_date).toLocaleDateString("pt-BR");
    return `Entregas entre ${start} a ${end}`;
}

export default {
    data: () => ({
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
     }),
    components: {
        iTreemap,
        iScatter,
        iInput,
        iSelect,
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        const start_date = new Date(new Date() - 7 * 24 * 60 * 60 * 1000).toLocaleDateString("sv");
        const end_date = new Date().toLocaleDateString("sv");
        this.start_date = start_date;
        this.end_date = end_date;
        this.chartTitle = getChartTitle(start_date, end_date);
        await Promise.all([
            this.$store.dispatch("deliveryMod/storeTreemap"),
            this.$store.dispatch("deliveryMod/storeScatter", { start_date, end_date }),
        ]);
    }, 
    methods: {
        updateSelect() {
            this.period = 0;
        },
        updateRange() {
            const { start_date, end_date } = this;
            this.chartTitle = getChartTitle(start_date, end_date);
            this.$store.dispatch("deliveryMod/storeScatter", { start_date, end_date });
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